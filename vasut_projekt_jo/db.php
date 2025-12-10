<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "vasut_projekt";
$port = 3306;

$conn = new mysqli($host, $user, $password, null, $port);

if ($conn->connect_error) {
    die("Hiba: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS jarat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jarat_szam VARCHAR(20) NOT NULL,
    indulasi_allomas VARCHAR(100) NOT NULL,
    erkezesi_allomas VARCHAR(100) NOT NULL,
    indulasi_ido DATETIME NOT NULL, 
    erkezesi_ido DATETIME NOT NULL,
    ar INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS jegyek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    jarat_id INT,
    vasarlas_ideje TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (jarat_id) REFERENCES jarat(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$result = $conn->query("SELECT * FROM jarat");

if ($result->num_rows < 5) {
    
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    $conn->query("TRUNCATE TABLE jarat");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    $sql = "INSERT INTO jarat (jarat_szam, indulasi_allomas, erkezesi_allomas, indulasi_ido, erkezesi_ido, ar) VALUES 
    ('IC 700 Napfény', 'Budapest', 'Szeged', '2025-12-09 05:53:00', '2025-12-09 08:15:00', 3100),
    ('IC 702 Napfény', 'Budapest', 'Szeged', '2025-12-09 07:53:00', '2025-12-09 10:15:00', 3100),
    ('IC 704 Napfény', 'Budapest', 'Szeged', '2025-12-09 09:53:00', '2025-12-09 12:15:00', 3100),
    ('IC 706 Napfény', 'Budapest', 'Szeged', '2025-12-09 11:53:00', '2025-12-09 14:15:00', 3500),
    ('Gyors 7018',     'Budapest', 'Szeged', '2025-12-09 14:00:00', '2025-12-09 16:45:00', 2800),
    ('IC 717 Napfény', 'Szeged', 'Budapest', '2025-12-09 06:45:00', '2025-12-09 09:07:00', 3100),
    ('IC 727 Napfény', 'Szeged', 'Budapest', '2025-12-09 10:45:00', '2025-12-09 13:07:00', 3100),
    ('IC 560 Tokaj',   'Budapest', 'Debrecen', '2025-12-09 06:23:00', '2025-12-09 09:00:00', 3400),
    ('IC 562 Tokaj',   'Budapest', 'Debrecen', '2025-12-09 08:23:00', '2025-12-09 11:00:00', 3400),
    ('S 50 Személy',   'Budapest', 'Debrecen', '2025-12-09 12:00:00', '2025-12-09 15:30:00', 2600),
    ('IC 802 Mecsek',  'Budapest', 'Pécs',     '2025-12-09 05:53:00', '2025-12-09 08:45:00', 3600),
    ('IC 804 Mecsek',  'Budapest', 'Pécs',     '2025-12-09 09:53:00', '2025-12-09 12:45:00', 3600),
    ('RJX 60',         'Budapest', 'Győr',     '2025-12-09 06:40:00', '2025-12-09 08:00:00', 2900),
    ('S 10 Személy',   'Budapest', 'Győr',     '2025-12-09 08:20:00', '2025-12-09 10:10:00', 2200),
    ('S 20',           'Szeged',    'Kecskemét','2025-12-09 14:15:00', '2025-12-09 15:00:00', 1100),
    ('S 20',           'Kecskemét', 'Budapest', '2025-12-09 15:10:00', '2025-12-09 16:30:00', 1500)";

    $conn->query($sql);
}
?>