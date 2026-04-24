<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class MarketListingRepository
{
    public function createFromDepotItem($depotItem, $priceMd, $priceJd)
    {
        $connection = Database::connection('player');

        $statement = $connection->prepare('
            INSERT INTO market_listings_test (
                depot_id,
                account_id,
                owner_name,
                item_vnum,
                item_count,
                socket0,
                socket1,
                socket2,
                attrtype0,
                attrvalue0,
                attrtype1,
                attrvalue1,
                attrtype2,
                attrvalue2,
                attrtype3,
                attrvalue3,
                attrtype4,
                attrvalue4,
                attrtype5,
                attrvalue5,
                attrtype6,
                attrvalue6,
                price_md,
                price_jd,
                status,
                created_at,
                updated_at
            ) VALUES (
                :depot_id,
                :account_id,
                :owner_name,
                :item_vnum,
                :item_count,
                :socket0,
                :socket1,
                :socket2,
                :attrtype0,
                :attrvalue0,
                :attrtype1,
                :attrvalue1,
                :attrtype2,
                :attrvalue2,
                :attrtype3,
                :attrvalue3,
                :attrtype4,
                :attrvalue4,
                :attrtype5,
                :attrvalue5,
                :attrtype6,
                :attrvalue6,
                :price_md,
                :price_jd,
                :status,
                :created_at,
                :updated_at
            )
        ');

        $now = date('Y-m-d H:i:s');

        return $statement->execute(array(
            'depot_id' => (int) $depotItem['id'],
            'account_id' => (int) $depotItem['account_id'],
            'owner_name' => $depotItem['owner_name'],
            'item_vnum' => (int) $depotItem['item_vnum'],
            'item_count' => (int) $depotItem['item_count'],
            'socket0' => (int) $depotItem['socket0'],
            'socket1' => (int) $depotItem['socket1'],
            'socket2' => (int) $depotItem['socket2'],
            'attrtype0' => (int) $depotItem['attrtype0'],
            'attrvalue0' => (int) $depotItem['attrvalue0'],
            'attrtype1' => (int) $depotItem['attrtype1'],
            'attrvalue1' => (int) $depotItem['attrvalue1'],
            'attrtype2' => (int) $depotItem['attrtype2'],
            'attrvalue2' => (int) $depotItem['attrvalue2'],
            'attrtype3' => (int) $depotItem['attrtype3'],
            'attrvalue3' => (int) $depotItem['attrvalue3'],
            'attrtype4' => (int) $depotItem['attrtype4'],
            'attrvalue4' => (int) $depotItem['attrvalue4'],
            'attrtype5' => (int) $depotItem['attrtype5'],
            'attrvalue5' => (int) $depotItem['attrvalue5'],
            'attrtype6' => (int) $depotItem['attrtype6'],
            'attrvalue6' => (int) $depotItem['attrvalue6'],
            'price_md' => (int) $priceMd,
            'price_jd' => (int) $priceJd,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ));
    }

	public function getActiveListingsByAccountId($accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT
				id,
				depot_id,
				account_id,
				owner_name,
				item_vnum,
				item_count,
				socket0,
				socket1,
				socket2,
				attrtype0,
				attrvalue0,
				attrtype1,
				attrvalue1,
				attrtype2,
				attrvalue2,
				attrtype3,
				attrvalue3,
				attrtype4,
				attrvalue4,
				attrtype5,
				attrvalue5,
				attrtype6,
				attrvalue6,
				price_md,
				price_jd,
				status,
				withdraw_status,
				withdraw_available_at,
				GREATEST(TIMESTAMPDIFF(SECOND, NOW(), withdraw_available_at), 0) AS withdraw_seconds_left,
				created_at,
				updated_at
			FROM market_listings_test
			WHERE account_id = :account_id
			  AND status = :status
			ORDER BY id DESC
		');

		$statement->execute(array(
			'account_id' => (int) $accountId,
			'status' => 'active',
		));

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function markWithdrawPending($listingId, $accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			UPDATE market_listings_test
			SET withdraw_status = :withdraw_status,
				withdraw_available_at = DATE_ADD(NOW(), INTERVAL 3 MINUTE),
				updated_at = NOW()
			WHERE id = :id
			  AND account_id = :account_id
			  AND status = :status
			LIMIT 1
		');

		return $statement->execute(array(
			'withdraw_status' => 'pending',
			'id' => (int) $listingId,
			'account_id' => (int) $accountId,
			'status' => 'active',
		));
	}

	public function findActiveListingByIdAndAccountId($listingId, $accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT
				id,
				depot_id,
				account_id,
				owner_name,
				item_vnum,
				item_count,
				socket0,
				socket1,
				socket2,
				attrtype0,
				attrvalue0,
				attrtype1,
				attrvalue1,
				attrtype2,
				attrvalue2,
				attrtype3,
				attrvalue3,
				attrtype4,
				attrvalue4,
				attrtype5,
				attrvalue5,
				attrtype6,
				attrvalue6,
				price_md,
				price_jd,
				status,
				withdraw_status,
				withdraw_available_at,
				GREATEST(TIMESTAMPDIFF(SECOND, NOW(), withdraw_available_at), 0) AS withdraw_seconds_left,
				created_at,
				updated_at
			FROM market_listings_test
			WHERE id = :id
			  AND account_id = :account_id
			  AND status = :status
			LIMIT 1
		');

		$statement->execute(array(
			'id' => (int) $listingId,
			'account_id' => (int) $accountId,
			'status' => 'active',
		));

		$row = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$row) {
			return null;
		}

		return $row;
	}

	public function getWithdrawSecondsLeft($listingRow)
	{
		if (isset($listingRow['withdraw_seconds_left'])) {
			$secondsLeft = (int) $listingRow['withdraw_seconds_left'];
			return $secondsLeft > 0 ? $secondsLeft : 0;
		}

		if (empty($listingRow['withdraw_available_at'])) {
			return 0;
		}

		$secondsLeft = strtotime($listingRow['withdraw_available_at']) - time();

		if ($secondsLeft < 0) {
			return 0;
		}

		return (int) $secondsLeft;
	}

	public function deleteByIdAndAccountId($listingId, $accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			DELETE FROM market_listings_test
			WHERE id = :id
			  AND account_id = :account_id
			LIMIT 1
		');

		return $statement->execute(array(
			'id' => (int) $listingId,
			'account_id' => (int) $accountId,
		));
	}

	public function getPublicActiveListings($limit = 10, $offset = 0)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT
				id,
				account_id,
				player_id,
				owner_name,
				item_vnum,
				item_count,
				socket0,
				socket1,
				socket2,
				attrtype0,
				attrvalue0,
				attrtype1,
				attrvalue1,
				attrtype2,
				attrvalue2,
				attrtype3,
				attrvalue3,
				attrtype4,
				attrvalue4,
				attrtype5,
				attrvalue5,
				attrtype6,
				attrvalue6,
				price_md,
				price_jd,
				status,
				withdraw_status,
				withdraw_available_at,
				created_at,
				updated_at
			FROM market_listings_test
			WHERE status = :status
				AND (
					withdraw_status = \'none\'
					OR (
						withdraw_status = \'pending\'
						AND withdraw_available_at > NOW()
					)
				  )
			ORDER BY id DESC
			LIMIT :limit OFFSET :offset
		');

		$statement->bindValue(':status', 'active', \PDO::PARAM_STR);
		$statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
		$statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findPublicActiveListingById($listingId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT
				id,
				depot_id,
				account_id,
				player_id,
				owner_name,
				item_vnum,
				item_count,
				socket0,
				socket1,
				socket2,
				attrtype0,
				attrvalue0,
				attrtype1,
				attrvalue1,
				attrtype2,
				attrvalue2,
				attrtype3,
				attrvalue3,
				attrtype4,
				attrvalue4,
				attrtype5,
				attrvalue5,
				attrtype6,
				attrvalue6,
				price_md,
				price_jd,
				status,
				withdraw_status,
				withdraw_available_at,
				created_at,
				updated_at
			FROM market_listings_test
			WHERE id = :id
				AND status = :status
				AND (
					withdraw_status = \'none\'
					OR (
						withdraw_status = \'pending\'
						AND withdraw_available_at > NOW()
					)
				  )
			LIMIT 1
		');

		$statement->execute(array(
			'id' => (int) $listingId,
			'status' => 'active',
		));

		$row = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$row) {
			return null;
		}

		return $row;
	}

	public function countPublicActiveListings()
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT COUNT(*) AS total
			FROM market_listings_test
			WHERE status = :status
				AND (
					withdraw_status = \'none\'
					OR (
						withdraw_status = \'pending\'
						AND withdraw_available_at > NOW()
					)
				  )
		');

		$statement->bindValue(':status', 'active', \PDO::PARAM_STR);
		$statement->execute();

		$row = $statement->fetch(\PDO::FETCH_ASSOC);

		if (!$row || !isset($row['total'])) {
			return 0;
		}

		return (int) $row['total'];
	}

}