<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemNameResolver
{
    private $nameMap = null;

    public function getNameByVnum($itemVnum)
    {
        $itemVnum = (int) $itemVnum;

        if ($itemVnum < 1) {
            return null;
        }

        $map = $this->getNameMap();

        if (!isset($map[$itemVnum])) {
            return null;
        }

        return $map[$itemVnum];
    }

    private function getNameMap()
    {
        if (is_array($this->nameMap)) {
            return $this->nameMap;
        }

        $this->nameMap = array();

        $filePath = BASE_PATH . '/storage/game_data/item_name.txt';

        if (!is_file($filePath) || !is_readable($filePath)) {
            return $this->nameMap;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            return $this->nameMap;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\t+/', $line, 2);

            if (!is_array($parts) || count($parts) < 2) {
                continue;
            }

            $vnum = (int) trim($parts[0]);
            $name = trim($parts[1]);

            if ($vnum < 1 || $name === '') {
                continue;
            }

            $this->nameMap[$vnum] = $this->normalizeName($name);
        }

        return $this->nameMap;
    }

    private function normalizeName($name)
	{
		$name = trim((string) $name);

		if ($name === '') {
			return $name;
		}

		if (function_exists('mb_check_encoding') && mb_check_encoding($name, 'UTF-8')) {
			return $name;
		}

		if (function_exists('mb_convert_encoding')) {
			$converted = @mb_convert_encoding($name, 'UTF-8', 'ISO-8859-2');
			if (is_string($converted) && $converted !== '') {
				return $converted;
			}

			$converted = @mb_convert_encoding($name, 'UTF-8', 'Windows-1250');
			if (is_string($converted) && $converted !== '') {
				return $converted;
			}

			$converted = @mb_convert_encoding($name, 'UTF-8', 'ISO-8859-1');
			if (is_string($converted) && $converted !== '') {
				return $converted;
			}

			$converted = @mb_convert_encoding($name, 'UTF-8', 'Windows-1252');
			if (is_string($converted) && $converted !== '') {
				return $converted;
			}
		}

		if (function_exists('iconv')) {
			$converted = @iconv('ISO-8859-2', 'UTF-8//IGNORE', $name);
			if (is_string($converted) && $converted !== '') {
				return $converted;
			}
		}

		return $name;
	}
}