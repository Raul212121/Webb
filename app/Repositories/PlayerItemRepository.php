<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class PlayerItemRepository
{
    public function findNextMallPositionByOwnerId($ownerId)
    {
        $connection = Database::connection('player');

        $statement = $connection->prepare('
            SELECT pos
            FROM item
            WHERE owner_id = :owner_id
              AND window = :window
            ORDER BY pos ASC
        ');

        $statement->execute(array(
            'owner_id' => (int) $ownerId,
            'window' => 'MALL',
        ));

        $usedPositions = $statement->fetchAll(PDO::FETCH_COLUMN);

        $usedMap = array();
        foreach ($usedPositions as $pos) {
            $usedMap[(int) $pos] = true;
        }

        for ($pos = 0; $pos < 45; $pos++) {
            if (!isset($usedMap[$pos])) {
                return $pos;
            }
        }

        return null;
    }

    public function insertDepotItemIntoMall($depotItem, $ownerId, $mallPos)
    {
        $connection = Database::connection('player');

        $statement = $connection->prepare('
            INSERT INTO item (
                owner_id,
                window,
                pos,
                count,
                vnum,
                socket0,
                socket1,
                socket2,
                socket3,
                socket4,
                socket5,
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
                attrvalue6
            ) VALUES (
                :owner_id,
                :window,
                :pos,
                :count,
                :vnum,
                :socket0,
                :socket1,
                :socket2,
                0,
                0,
                0,
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
                :attrvalue6
            )
        ');

        return $statement->execute(array(
            'owner_id' => (int) $ownerId,
            'window' => 'MALL',
            'pos' => (int) $mallPos,
            'count' => (int) $depotItem['item_count'],
            'vnum' => (int) $depotItem['item_vnum'],
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
        ));
    }

	public function insertListingItemIntoMall($listingItem, $ownerId, $mallPos)
	{
		$connection = Database::connection('player');

		$statement = $connection->prepare('
			INSERT INTO item (
				owner_id,
				window,
				pos,
				count,
				vnum,
				socket0,
				socket1,
				socket2,
				socket3,
				socket4,
				socket5,
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
				attrvalue6
			) VALUES (
				:owner_id,
				:window,
				:pos,
				:count,
				:vnum,
				:socket0,
				:socket1,
				:socket2,
				0,
				0,
				0,
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
				:attrvalue6
			)
		');

		return $statement->execute(array(
			'owner_id' => (int) $ownerId,
			'window' => 'MALL',
			'pos' => (int) $mallPos,
			'count' => (int) $listingItem['item_count'],
			'vnum' => (int) $listingItem['item_vnum'],
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
		));
	}

}