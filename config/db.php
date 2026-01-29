<?php
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');
if ($isLocal) {
    $host     = "localhost";
    $dbname   = "library_db";
    $username = "root";
    $password = "";
}
else {
    $host     = "localhost";
    $dbname   = "np03cs4s250084";
    $username = "np03cs4s250084";
    $password = "AGVgARAlsU";
}
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection unsuccessful.");
}