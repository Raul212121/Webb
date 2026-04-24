<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemIconResolver
{
    private $iconMap = null;

    public function getIconFilenameByVnum($itemVnum)
    {
        $itemVnum = (int) $itemVnum;

        if ($itemVnum < 1) {
            return null;
        }

        $map = $this->getIconMap();

        if (!isset($map[$itemVnum])) {
            return null;
        }

        return $map[$itemVnum];
    }

    public function getIconPathByVnum($itemVnum)
    {
        $filename = $this->getIconFilenameByVnum($itemVnum);

        if (!$filename) {
            return null;
        }

        return '/assets/img/item/' . $filename;
    }

    private function getIconMap()
    {
        if (is_array($this->iconMap)) {
            return $this->iconMap;
        }

        $this->iconMap = array();

        $filePath = BASE_PATH . '/storage/game_data/item_icon.txt';

        if (!is_file($filePath) || !is_readable($filePath)) {
            return $this->iconMap;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            return $this->iconMap;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\t+/', $line);

            if (!is_array($parts) || count($parts) < 2) {
                continue;
            }

            $vnum = (int) trim($parts[0]);
            $filename = trim($parts[1]);

            if ($vnum < 1 || $filename === '') {
                continue;
            }

            $this->iconMap[$vnum] = $filename;
        }

        return $this->iconMap;
    }
}