<?php

namespace app;

use app\Interfaces\DBConnectionInterface;

class ConnectionFactory
{
    private static DBConnectionInterface|null $db = null;

    public static function getDatabase(string $DBType): DBConnectionInterface
    {
        if (self::$db) {
            return self::$db;
        }

        $dbConfig = require dirname(__DIR__) . '/db.php';

        if (array_key_exists($DBType, $dbConfig)) {
            try {
                $class = $dbConfig[$DBType]['class'];

                /** @var DBConnectionInterface $class db */
                self::$db = new $class();
                self::$db->connect($dbConfig[$DBType]);

                return self::$db;
            } catch (\Exception $exception) {
                die(var_export($exception->getMessage()));
            }
        }
        throw new \Exception("Not found config to init db");
    }
}
