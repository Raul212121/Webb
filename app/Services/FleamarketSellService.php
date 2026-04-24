<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MarketDepotRepository;
use App\Repositories\MarketListingRepository;
use App\Repositories\AccountRepository;

final class FleamarketSellService
{
    private $marketDepotRepository;
    private $marketListingRepository;
	private $accountRepository;

    public function __construct()
    {
        $this->marketDepotRepository = new MarketDepotRepository();
        $this->marketListingRepository = new MarketListingRepository();
		$this->accountRepository = new AccountRepository();
    }

    public function sellSelectedDepotItem($accountId, $depotItemId, $priceMd)
    {
        $accountId = (int) $accountId;
        $depotItemId = (int) $depotItemId;
        $priceMd = (int) $priceMd;
		

        if ($accountId < 1 || $depotItemId < 1) {
            return array(
                'success' => false,
                'message' => 'Date invalide pentru listare.',
            );
        }

        if ($priceMd < 1) {
            return array(
                'success' => false,
                'message' => 'Pretul minim este 1 MD.',
            );
        }

		$reputation = $this->accountRepository->getFleaReputationById($accountId);
		$maxListingSlots = min(20, 3 + (int) floor($reputation / 5));

		$activeListings = $this->marketListingRepository->getActiveListingsByAccountId($accountId);
		$activeListingCount = count($activeListings);

		if ($activeListingCount >= $maxListingSlots) {
			return array(
				'success' => false,
				'message' => 'Ai atins limita maxima de oferte disponibile.',
			);
		}

        $depotItem = $this->marketDepotRepository->findByIdAndAccountId($depotItemId, $accountId);

        if (!$depotItem) {
            return array(
                'success' => false,
                'message' => 'Itemul selectat nu exista in depozit.',
            );
        }

        $created = $this->marketListingRepository->createFromDepotItem($depotItem, $priceMd, 0);

        if (!$created) {
            return array(
                'success' => false,
                'message' => 'Itemul nu a putut fi listat.',
            );
        }

        $deleted = $this->marketDepotRepository->deleteByIdAndAccountId($depotItemId, $accountId);

        if (!$deleted) {
            return array(
                'success' => false,
                'message' => 'Itemul a fost listat, dar nu a putut fi eliminat din depozit.',
            );
        }

        return array(
            'success' => true,
            'message' => 'Itemul a fost listat cu succes.',
        );
    }
}