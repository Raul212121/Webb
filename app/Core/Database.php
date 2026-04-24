<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;
use App\Core\Database;

final class Database
{
    private static $connections = array();

    public static function connection($name = 'default')
    {
        if (isset(self::$connections[$name]) && self::$connections[$name] instanceof PDO) {
            return self::$connections[$name];
        }

        if ($name === 'player') {
            $config = require BASE_PATH . '/config/database_player.php';
        } else {
            $config = require BASE_PATH . '/config/database.php';
        }

        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $host = isset($config['host']) ? $config['host'] : '127.0.0.1';
        $port = isset($config['port']) ? $config['port'] : 3306;
        $database = isset($config['database']) ? $config['database'] : '';
        $username = isset($config['username']) ? $config['username'] : '';
        $password = isset($config['password']) ? $config['password'] : '';
        $charset = isset($config['charset']) ? $config['charset'] : 'utf8mb4';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $driver,
            $host,
            $port,
            $database,
            $charset
        );

        try {
            self::$connections[$name] = new PDO($dsn, $username, $password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ));
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed: ' . $exception->getMessage());
        }

        return self::$connections[$name];
    }
}