<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MarketDepotRepository;
use App\Repositories\MarketListingRepository;

final class FleamarketReturnListingToDepotService
{
    private $marketDepotRepository;
    private $marketListingRepository;

    public function __construct()
    {
        $this->marketDepotRepository = new MarketDepotRepository();
        $this->marketListingRepository = new MarketListingRepository();
    }

    public function finalizeReturnToDepot($accountId, $listingId)
    {
        $accountId = (int) $accountId;
        $listingId = (int) $listingId;

        if ($accountId < 1 || $listingId < 1) {
            return array(
                'success' => false,
                'message' => 'Date invalide pentru finalizarea retragerii.',
            );
        }

        $listing = $this->marketListingRepository->findActiveListingByIdAndAccountId($listingId, $accountId);

        if (!$listing) {
            return array(
                'success' => false,
                'message' => 'Oferta nu exista sau nu iti apartine.',
            );
        }

        if (empty($listing['withdraw_status']) || $listing['withdraw_status'] !== 'pending') {
            return array(
                'success' => false,
                'message' => 'Oferta nu este in retragere.',
            );
        }

        $secondsLeft = $this->marketListingRepository->getWithdrawSecondsLeft($listing);
        if ($secondsLeft > 0) {
            return array(
                'success' => false,
                'message' => 'Timerul de retragere nu a expirat inca.',
            );
        }

        $created = $this->marketDepotRepository->createFromListingItem($listing);
		if (!$created) {
			return array(
				'success' => false,
				'message' => 'Itemul nu a putut fi mutat inapoi in depozit.',
			);
		}

		$deleted = $this->marketListingRepository->deleteByIdAndAccountId($listingId, $accountId);
		if (!$deleted) {
			return array(
				'success' => false,
				'message' => 'Itemul a fost mutat in depozit, dar listingul nu a putut fi sters.',
			);
		}

		return array(
			'success' => true,
			'message' => 'Itemul a fost mutat inapoi in depozitul FleaMarket.',
		);
    }
}