<?php
class Database
{
    private static $instance = null;
    private static $config = [];

    private function __construct() {}

    public static function getConfig()
    {
        if (empty(self::$config)) {
            $path = dirname(__DIR__) . '/config/database.php';
            if (!is_file($path)) {
                die('Konfigurimi i databazes nuk u gjet: ' . $path);
            }
            self::$config = require $path;
        }
        return self::$config;
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            $c = self::getConfig();
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $c['host'],
                $c['dbname'],
                $c['charset']
            );
            try {
                self::$instance = new PDO($dsn, $c['username'], $c['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Lidhja me databazen deshtoi: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
