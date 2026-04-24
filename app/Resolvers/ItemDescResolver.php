<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemDescResolver
{
    private $descMap = null;

    public function getDescriptionByVnum($itemVnum)
    {
        $itemVnum = (int) $itemVnum;

        if ($itemVnum < 1) {
            return null;
        }

        $map = $this->getDescMap();

        if (!isset($map[$itemVnum])) {
            return null;
        }

        return $map[$itemVnum];
    }

    private function getDescMap()
    {
        if (is_array($this->descMap)) {
            return $this->descMap;
        }

        $this->descMap = array();

        $filePath = BASE_PATH . '/storage/game_data/item_desc.txt';

        if (!is_file($filePath) || !is_readable($filePath)) {
            return $this->descMap;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            return $this->descMap;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\t+/', $line, 3);

            if (!is_array($parts) || count($parts) < 2) {
                continue;
            }

            $vnum = (int) trim($parts[0]);
            $description = trim($parts[1]);

            if ($vnum < 1 || $description === '') {
                continue;
            }

            $this->descMap[$vnum] = $this->normalizeText($description);
        }

        return $this->descMap;
    }

    private function normalizeText($text)
    {
        $text = trim((string) $text);

        if ($text === '') {
            return $text;
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($text, 'UTF-8', 'UTF-8, ISO-8859-1, ISO-8859-2, Windows-1250, Windows-1252');
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('Windows-1250', 'UTF-8//IGNORE', $text);
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        return $text;
    }
}