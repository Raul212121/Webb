<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemProtoResolver
{
    private $itemMap = null;

    public function getItemByVnum($itemVnum)
    {
        $itemVnum = (int) $itemVnum;

        if ($itemVnum < 1) {
            return null;
        }

        $map = $this->getItemMap();

        if (!isset($map[$itemVnum])) {
            return null;
        }

        return $map[$itemVnum];
    }

    public function getItemSizeByVnum($itemVnum)
    {
        $item = $this->getItemByVnum($itemVnum);

        if (!$item || !isset($item['size'])) {
            return 1;
        }

        $size = (int) $item['size'];

        if ($size < 1) {
            return 1;
        }

        if ($size > 3) {
            return 3;
        }

        return $size;
    }

	public function getItemTypeByVnum($itemVnum)
	{
		$item = $this->getItemByVnum($itemVnum);

		if (!$item || !isset($item['item_type'])) {
			return null;
		}

		return $item['item_type'];
	}

    private function getItemMap()
    {
        if (is_array($this->itemMap)) {
            return $this->itemMap;
        }

        $this->itemMap = array();

        $filePath = BASE_PATH . '/storage/game_data/item_proto.txt';

        if (!is_file($filePath) || !is_readable($filePath)) {
            return $this->itemMap;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines) || count($lines) < 2) {
            return $this->itemMap;
        }

        foreach ($lines as $index => $line) {
            if ($index === 0) {
                continue;
            }

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\t+/', $line);

            if (!is_array($parts) || count($parts) < 5) {
                continue;
            }

            $vnum = (int) trim($parts[0]);
            $size = (int) trim($parts[4]);

            if ($vnum < 1) {
                continue;
            }

            $itemType = isset($parts[2]) ? trim($parts[2]) : '';
			$itemSubtype = isset($parts[3]) ? trim($parts[3]) : '';

			$this->itemMap[$vnum] = array(
				'vnum' => $vnum,
				'size' => $size,
				'item_type' => $itemType,
				'sub_type' => $itemSubtype,
				'addon_type0' => isset($parts[18]) ? trim($parts[18]) : '',
				'addon_value0' => isset($parts[19]) ? (int) trim($parts[19]) : 0,
				'addon_type1' => isset($parts[20]) ? trim($parts[20]) : '',
				'addon_value1' => isset($parts[21]) ? (int) trim($parts[21]) : 0,
				'addon_type2' => isset($parts[22]) ? trim($parts[22]) : '',
				'addon_value2' => isset($parts[23]) ? (int) trim($parts[23]) : 0,
				'value0' => isset($parts[24]) ? (int) trim($parts[24]) : 0,
				'value1' => isset($parts[25]) ? (int) trim($parts[25]) : 0,
				'value2' => isset($parts[26]) ? (int) trim($parts[26]) : 0,
				'value3' => isset($parts[27]) ? (int) trim($parts[27]) : 0,
				'value4' => isset($parts[28]) ? (int) trim($parts[28]) : 0,
				'value5' => isset($parts[29]) ? (int) trim($parts[29]) : 0,
			);
        }

        return $this->itemMap;
    }
}