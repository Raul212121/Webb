<?php
declare(strict_types=1);

namespace App\Core;

final class DatabaseHealthCheck
{
    public static function check(): bool
    {
        Database::connection();
        return true;
    }
}