<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

//fetching issued books
$stmt = $pdo->query(
    "SELECT issued_books.issue_id, students.name, books.title, issued_books.issue_date
     FROM issued_books
     JOIN students ON issued_books.student_id = students.student_id
     JOIN books ON issued_books.book_id = books.book_id
     WHERE issued_books.status = 'issued'"
);
$issuedBooks = $stmt->fetchAll();

//return books
if (isset($_GET['return'])) {
    $issue_id = $_GET['return'];

    $stmt = $pdo->prepare(
        "SELECT book_id FROM issued_books WHERE issue_id = ?"
    );
    $stmt->execute([$issue_id]);
    $book = $stmt->fetch();

    $updateIssue = $pdo->prepare(
        "UPDATE issued_books
         SET status='returned', return_date=CURDATE()
         WHERE issue_id=?"
    );
    $updateIssue->execute([$issue_id]);

    $updateBook = $pdo->prepare(
        "UPDATE books SET quantity = quantity + 1 WHERE book_id=?"
    );
    $updateBook->execute([$book['book_id']]);

    header("Location: return_book.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="wrapper">

<div class="sidebar">
    <h2>Library Management System</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="students.php">Manage Students</a></li>
        <li><a href="categories.php">Manage Categories</a></li>
        <li><a href="books.php">Manage Books</a></li>
        <li><a href="issue_book.php">Issue Books</a></li>
        <li><a href="return_book.php">Return Books</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">

<div class="header">
    <h1>Return Book</h1>
</div>

<div class="card">
<table>
<tr>
    <th>Student Name</th>
    <th>Book Title</th>
    <th>Issue Date</th>
    <th>Action</th>
</tr>

<?php foreach ($issuedBooks as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['title']); ?></td>
    <td><?= htmlspecialchars($row['issue_date']); ?></td>
    <td class="action-links">
        <a href="?return=<?= $row['issue_id']; ?>"
           onclick="return confirm('Return this book?')">Return</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>

</body>
</html>