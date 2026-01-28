<?php
require_once "../config/db.php";

$term = "%" . $_GET['term'] . "%";

$stmt = $pdo->prepare(
    "SELECT books.book_id, books.title, books.author, categories.category_name
     FROM books
     LEFT JOIN categories ON books.category_id = categories.category_id
     WHERE books.title LIKE ?
        OR books.author LIKE ?
        OR categories.category_name LIKE ?"
);

$stmt->execute([$term, $term, $term]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array_map(function ($b) {
    return [
        "book_id" => $b["book_id"],
        "title"   => $b["title"],
        "author"  => $b["author"],
        "category"=> $b["category_name"]
    ];
}, $books));
