<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MarketDepotRepository;
use App\Repositories\PlayerItemRepository;

final class FleamarketWithdrawService
{
    private $marketDepotRepository;
    private $playerItemRepository;

    public function __construct()
    {
        $this->marketDepotRepository = new MarketDepotRepository();
        $this->playerItemRepository = new PlayerItemRepository();
    }

    public function withdrawDepotItemToMall($accountId, $depotItemId)
    {
        $accountId = (int) $accountId;
        $depotItemId = (int) $depotItemId;

        if ($accountId < 1 || $depotItemId < 1) {
            return array(
                'success' => false,
                'message' => 'Date invalide pentru retragere.',
            );
        }

        $depotItem = $this->marketDepotRepository->findByIdAndAccountId($depotItemId, $accountId);

        if (!$depotItem) {
            return array(
                'success' => false,
                'message' => 'Itemul selectat nu exista in depozit.',
            );
        }

        $mallPos = $this->playerItemRepository->findNextMallPositionByOwnerId($accountId);

        if ($mallPos === null) {
            return array(
                'success' => false,
                'message' => 'Depozitul Mall este plin.',
            );
        }

        $inserted = $this->playerItemRepository->insertDepotItemIntoMall($depotItem, $accountId, $mallPos);

        if (!$inserted) {
            return array(
                'success' => false,
                'message' => 'Itemul nu a putut fi trimis in Mall.',
            );
        }

        $deleted = $this->marketDepotRepository->deleteByIdAndAccountId($depotItemId, $accountId);

        if (!$deleted) {
            return array(
                'success' => false,
                'message' => 'Itemul a fost trimis in Mall, dar nu a putut fi eliminat din depozit.',
            );
        }

        return array(
            'success' => true,
            'message' => 'Itemul a fost trimis in Mall.',
        );
    }
}