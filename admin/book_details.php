<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare(
    "SELECT books.*, categories.category_name
     FROM books
     LEFT JOIN categories ON books.category_id = categories.category_id
     WHERE books.book_id = ?"
);
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="wrapper">

<div class="sidebar">
    <h2>Library Management System</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="books.php">Manage Books</a></li>
        <li><a href="issue_book.php">Issue Books</a></li>
        <li><a href="return_book.php">Return Books</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Book Details</h1>
    </div>

    <div class="card">
        <p><strong>Book Title:</strong> <?= htmlspecialchars($book['title']) ?></p>
        <p><strong>Author Name:</strong> <?= htmlspecialchars($book['author']) ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($book['category_name']) ?></p>
        <p><strong>Published Year:</strong> <?= $book['publication_year'] ?></p>
        <p><strong>Book Quantity:</strong> <?= $book['quantity'] ?></p>

        <br>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <div class="footer">
            &copy; <?php echo date("Y"); ?> Sagya Ghimire Library Management System
        </div>
</div>


</div>


</body>
</html>
