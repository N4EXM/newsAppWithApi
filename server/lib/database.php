<?php
// database.php
$host = 'localhost';      // or your server IP
$dbname = 'dailylens'; // exact database name
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        dsn: "mysql:host=$host;dbname=$dbname", 
        username: $username, 
        password: $password,
        options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>