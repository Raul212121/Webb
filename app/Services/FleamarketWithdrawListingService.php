<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Repositories\MarketListingRepository;

final class FleamarketWithdrawListingService
{
    private $accountRepository;
    private $marketListingRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->marketListingRepository = new MarketListingRepository();
    }

    public function startWithdrawTimer($accountId, $listingId)
    {
        $accountId = (int) $accountId;
        $listingId = (int) $listingId;

        if ($accountId < 1 || $listingId < 1) {
            return array(
                'success' => false,
                'message' => 'Date invalide pentru retragere.',
            );
        }

        $listing = $this->marketListingRepository->findActiveListingByIdAndAccountId($listingId, $accountId);

        if (!$listing) {
            return array(
                'success' => false,
                'message' => 'Oferta nu exista sau nu iti apartine.',
            );
        }

        if (!empty($listing['withdraw_status']) && $listing['withdraw_status'] === 'pending') {
            return array(
                'success' => false,
                'message' => 'Retragerea este deja in curs.',
            );
        }

        $marked = $this->marketListingRepository->markWithdrawPending($listingId, $accountId);

        if (!$marked) {
            return array(
                'success' => false,
                'message' => 'Nu s-a putut porni timerul de retragere.',
            );
        }

        $reputationUpdated = $this->accountRepository->decreaseFleaReputationById($accountId, 0.01);

        if (!$reputationUpdated) {
            return array(
                'success' => false,
                'message' => 'Timerul a pornit, dar reputatia nu a putut fi actualizata.',
            );
        }

        return array(
            'success' => true,
            'message' => 'Retragerea a fost pornita. Itemul va putea fi retras in 10 minute.',
        );
    }
}