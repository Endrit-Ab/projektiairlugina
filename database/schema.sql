CREATE DATABASE IF NOT EXISTS airlugina_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE airlugina_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT,
    image_path VARCHAR(255),
    pdf_path VARCHAR(255),
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    from_location VARCHAR(100),
    to_location VARCHAR(100),
    price DECIMAL(10,2),
    image_path VARCHAR(255),
    pdf_path VARCHAR(255),
    created_by INT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    subtitle VARCHAR(255),
    image_path VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES 
('admin@airlugina.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin');

INSERT INTO pages (slug, title, content) VALUES 
('home', 'Welcome to AirLugina', 'Special offers to suit your plan. Live & Travel with us.'),
('about', 'About AirLugina', 'AirLugina is your trusted partner for flights and travel. We offer competitive prices and excellent service.');

INSERT INTO slider (title, subtitle, image_path, sort_order) VALUES 
('Helping Others', 'LIVE & TRAVEL', 'assets/Images/backroung.png', 1),
('Explore the World', 'Best Flight Deals', 'assets/Images/dubai.png', 2);

INSERT INTO products (title, description, from_location, to_location, price, image_path, created_by) VALUES 
('Fluturim Tirana – Dubai', 'Fluturim direkt me Emirates. Dubai – Palm View City. Oferte e kufizuar.', 'Tirana', 'Dubai', 299.00, 'assets/Images/dubai-palm-city.jpg', 1),
('Fluturim Tirana – Doha', 'Qatar Airways, ndalesë të minimale. Qyteti i Dohës – rezervo tani.', 'Tirana', 'Doha', 349.00, 'assets/Images/doha-oferta.png', 1),
('Fluturim Tirana – Abu Dhabi', 'Ofertë: Fluturim me Etihad Airways për Abu Dhabi. Çmim i volitshëm – nga €319. Rezervo tani.', 'Tirana', 'Abu Dhabi', 319.00, 'assets/Images/abu-dhabi-oferta.png', 1),
('Oferte Fluturimesh Europiane', 'Disa destinacione në Europë me çmime të volitshme.', 'Tirana', 'Milan / Roma', 89.00, 'assets/Images/milano-oferta.png', 1),
('Paketë Dubai – 5 netë', 'Hotel 4 yje + fluturim. Përfshirë transferet.', 'Dubai', 'Tirana', 599.00, 'assets/Images/burj-khalifa-oferta.png', 1);
