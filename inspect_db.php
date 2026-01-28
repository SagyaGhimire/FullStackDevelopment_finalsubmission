<?php
require_once "config/db.php";

try {
    $stmt = $pdo->query("DESCRIBE issued_books");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>