<?php
/**
 * Përditëson imazhet e produkteve ekzistues (qytete – cilësi më e mirë).
 * Ekzekutoni një herë nëse e keni instaluar tashmë projektin.
 */
$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
    $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    /* Oferta e parë (Tirana Dubai) – mos prek */
    $pdo->exec("UPDATE products SET image_path = 'assets/Images/doha-oferta.png' WHERE to_location = 'Doha'");
    $pdo->exec("UPDATE products SET image_path = 'assets/Images/abu-dhabi-oferta.png' WHERE to_location = 'Abu Dhabi'");
    $pdo->exec("UPDATE products SET image_path = 'assets/Images/milano-oferta.png' WHERE to_location = 'Milan / Roma'");
    $pdo->exec("UPDATE products SET image_path = 'assets/Images/burj-khalifa-oferta.png' WHERE from_location = 'Dubai' AND to_location = 'Tirana'");

    echo 'Imazhet e produkteve u përditësuan. <a href="../products.php">Shiko Produktet</a>';
} catch (PDOException $e) {
    die('Gabim: ' . $e->getMessage());
}
