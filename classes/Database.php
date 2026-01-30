<?php
/**
 * Klasa Database - lidhje me MySQL (OOP)
 * AirLugina Faza 2
 */

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    private function __construct() {}

    public static function getConfig(): array
    {
        if (empty(self::$config)) {
            $path = dirname(__DIR__) . '/config/database.php';
            if (!is_file($path)) {
                throw new \RuntimeException('Konfigurimi i databazës nuk u gjet: ' . $path);
            }
            self::$config = require $path;
        }
        return self::$config;
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $c = self::getConfig();
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $c['host'],
                $c['dbname'],
                $c['charset'] ?? 'utf8mb4'
            );
            try {
                self::$instance = new PDO($dsn, $c['username'], $c['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException('Lidhja me databazën dështoi: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function closeConnection(): void
    {
        self::$instance = null;
    }
}
