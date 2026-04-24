<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class MarketDepotRepository
{
    public function getItemsByAccountId($accountId, $limit = 44, $offset = 0)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT
				id,
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
				created_at
			FROM market_depot_test
			WHERE account_id = :account_id
			ORDER BY id DESC
			LIMIT :limit OFFSET :offset
		');

		$statement->bindValue(':account_id', (int) $accountId, \PDO::PARAM_INT);
		$statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
		$statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

    public function findByIdAndAccountId($id, $accountId)
    {
        $connection = Database::connection('player');

        $statement = $connection->prepare('
            SELECT
                id,
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
                created_at
            FROM market_depot_test
            WHERE id = :id
              AND account_id = :account_id
            LIMIT 1
        ');

        $statement->execute(array(
            'id' => $id,
            'account_id' => $accountId,
        ));

        $item = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            return null;
        }

        return $item;
    }

	public function deleteByIdAndAccountId($id, $accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			DELETE FROM market_depot_test
			WHERE id = :id
			  AND account_id = :account_id
			LIMIT 1
		');

		return $statement->execute(array(
			'id' => (int) $id,
			'account_id' => (int) $accountId,
		));
	}

	public function createFromListingItem($listingItem)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			INSERT INTO market_depot_test (
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
				created_at
			) VALUES (
				:account_id,
				:player_id,
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
				:created_at
			)
		');

		return $statement->execute(array(
			'account_id' => (int) $listingItem['account_id'],
			'player_id' => isset($listingItem['player_id']) ? (int) $listingItem['player_id'] : 0,
			'owner_name' => $listingItem['owner_name'],
			'item_vnum' => (int) $listingItem['item_vnum'],
			'item_count' => (int) $listingItem['item_count'],
			'socket0' => (int) $listingItem['socket0'],
			'socket1' => (int) $listingItem['socket1'],
			'socket2' => (int) $listingItem['socket2'],
			'attrtype0' => (int) $listingItem['attrtype0'],
			'attrvalue0' => (int) $listingItem['attrvalue0'],
			'attrtype1' => (int) $listingItem['attrtype1'],
			'attrvalue1' => (int) $listingItem['attrvalue1'],
			'attrtype2' => (int) $listingItem['attrtype2'],
			'attrvalue2' => (int) $listingItem['attrvalue2'],
			'attrtype3' => (int) $listingItem['attrtype3'],
			'attrvalue3' => (int) $listingItem['attrvalue3'],
			'attrtype4' => (int) $listingItem['attrtype4'],
			'attrvalue4' => (int) $listingItem['attrvalue4'],
			'attrtype5' => (int) $listingItem['attrtype5'],
			'attrvalue5' => (int) $listingItem['attrvalue5'],
			'attrtype6' => (int) $listingItem['attrtype6'],
			'attrvalue6' => (int) $listingItem['attrvalue6'],
			'created_at' => date('Y-m-d H:i:s'),
		));
	}

	public function countItemsByAccountId($accountId)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			SELECT COUNT(*) AS total
			FROM market_depot_test
			WHERE account_id = :account_id
		');

		$statement->bindValue(':account_id', (int) $accountId, \PDO::PARAM_INT);
		$statement->execute();

		$row = $statement->fetch(\PDO::FETCH_ASSOC);

		if (!$row || !isset($row['total'])) {
			return 0;
		}

		return (int) $row['total'];
	}

}