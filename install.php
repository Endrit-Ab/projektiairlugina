<?php
/**
 * Instalim i databazës dhe përdoruesit admin (ekzekuto një herë)
 * Krijon databazën, tabelat dhe admin: admin@airlugina.com / admin123
 */
require_once __DIR__ . '/init.php';

$config = Database::getConfig();
try {
    $pdo = new PDO(
        'mysql:host=' . $config['host'] . ';charset=' . $config['charset'],
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Lidhja me MySQL dështoi. Kontrollo config/database.php: ' . $e->getMessage());
}
$pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $config['dbname'] . '`');
$pdo->exec('USE `' . $config['dbname'] . '`');

$sql = file_get_contents(__DIR__ . '/database/schema.sql');
$sql = preg_replace('/--.*$/m', '', $sql);
$statements = array_filter(array_map('trim', explode(';', $sql)));
foreach ($statements as $stmt) {
    if ($stmt !== '' && stripos($stmt, 'INSERT INTO') === false) {
        try {
            $pdo->exec($stmt);
        } catch (PDOException $e) {
            // Tabela mund të ekzistojë
            if (strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }
    }
}
// Insert pages dhe slider nga schema (nëse nuk ekzistojnë)
$pdo->exec("INSERT IGNORE INTO pages (slug, title, content) VALUES ('about', 'About Us', 'AirLugina helps you live and travel. We offer special deals to suit your plan.'), ('home', 'Live & Travel', 'Helping others LIVE & TRAVEL. Special offers to suit your plan.')");
$pdo->exec("INSERT IGNORE INTO slider (title, subtitle, image_path, sort_order) VALUES ('Helping Others', 'LIVE & TRAVEL', 'Assets/Images/backroung.png', 0)");

$user = new User();
if (!$user->findByEmail('admin@airlugina.com')) {
    $user->create('admin@airlugina.com', 'admin123', 'Admin', 'AirLugina', 'admin');
    echo 'Instalimi përfundoi. Admin: admin@airlugina.com / admin123';
} else {
    echo 'Databaza ekziston. Admin tashmë është i regjistruar (admin@airlugina.com / admin123).';
}
