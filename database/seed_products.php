<?php
/**
 * Shton produkte fillestare nëse tabela products është bosh.
 * Ekzekutoni një herë: http://localhost/projektiairlugina/database/seed_products.php
 */
$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
    $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    if ($stmt->fetchColumn() > 0) {
        echo 'Ekzistojnë tashmë produkte. Nuk u shtuan të reja.';
        return;
    }

    $pdo->exec("INSERT INTO products (title, description, from_location, to_location, price, image_path, created_by) VALUES 
        ('Fluturim Tirana – Dubai', 'Fluturim direkt me Emirates. Dubai – Palm View City. Oferte e kufizuar.', 'Tirana', 'Dubai', 299.00, 'assets/Images/dubai-palm-city.jpg', 1),
        ('Fluturim Tirana – Doha', 'Qatar Airways, ndalesë të minimale. Qyteti i Dohës – rezervo tani.', 'Tirana', 'Doha', 349.00, 'assets/Images/doha-oferta.png', 1),
        ('Fluturim Tirana – Abu Dhabi', 'Ofertë: Fluturim me Etihad Airways për Abu Dhabi. Çmim i volitshëm – nga €319. Rezervo tani.', 'Tirana', 'Abu Dhabi', 319.00, 'assets/Images/abu-dhabi-oferta.png', 1),
        ('Oferte Fluturimesh Europiane', 'Disa destinacione në Europë me çmime të volitshme.', 'Tirana', 'Milan / Roma', 89.00, 'assets/Images/milano-oferta.png', 1),
        ('Paketë Dubai – 5 netë', 'Hotel 4 yje + fluturim. Përfshirë transferet.', 'Dubai', 'Tirana', 599.00, 'assets/Images/burj-khalifa-oferta.png', 1)");

    echo 'U shtuan 5 produkte fillestare. <a href="../products.php">Shiko Produktet</a>';
} catch (PDOException $e) {
    die('Gabim: ' . $e->getMessage());
}
