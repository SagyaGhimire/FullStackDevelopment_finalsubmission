<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

$students = $pdo->query("SELECT * FROM students")->fetchAll();

$books = $pdo->query("SELECT * FROM books WHERE quantity > 0")->fetchAll();

$issuedBooks = $pdo->query(
    "SELECT issued_books.issue_id, students.name AS student_name,
            books.title AS book_title, issued_books.issue_date
     FROM issued_books
     JOIN students ON issued_books.student_id = students.student_id
     JOIN books ON issued_books.book_id = books.book_id
     WHERE issued_books.status = 'issued'
     ORDER BY issued_books.issue_date DESC"
)->fetchAll();

if (isset($_POST['issue_book'])) {
    verify_csrf_token($_POST['csrf_token']);

    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];
    $issue_date = date("Y-m-d");

    $stmt = $pdo->prepare(
        "INSERT INTO issued_books (student_id, book_id, issue_date, status)
         VALUES (?, ?, ?, 'issued')"
    );
    $stmt->execute([$student_id, $book_id, $issue_date]);

    $update = $pdo->prepare(
        "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?"
    );
    $update->execute([$book_id]);

    $_SESSION['success'] = "Book issued successfully!";
    header("Location: issue_book.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
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
    <h1>Issue Book</h1>
</div>

<div class="card">
<h3>Issue New Book</h3>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

    <label>Select Student</label>
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php foreach ($students as $s): ?>
            <option value="<?= $s['student_id']; ?>">
                <?= htmlspecialchars($s['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Select Book</label>
    <select name="book_id" required>
        <option value="">Select Book</option>
        <?php foreach ($books as $b): ?>
            <option value="<?= $b['book_id']; ?>">
                <?= htmlspecialchars($b['title']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="issue_book">Issue Book</button>
</form>
</div>

<div class="card">
<h3>Issued Books</h3>

<table>
<tr>
    <th>Student Name</th>
    <th>Book Name</th>
    <th>Issue Date</th>
</tr>

<?php if (count($issuedBooks) === 0): ?>
<tr>
    <td colspan="3">No books issued currently!</td>
</tr>
<?php else: ?>
<?php foreach ($issuedBooks as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['student_name']); ?></td>
    <td><?= htmlspecialchars($row['book_title']); ?></td>
    <td><?= htmlspecialchars($row['issue_date']); ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>
</div>
<div class="footer">
            &copy; <?php echo date("Y"); ?> Sagya Ghimire Library Management System
        </div>
</div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<script>
    alert("<?= $_SESSION['success']; ?>");
</script>
<?php unset($_SESSION['success']); endif; ?>

</body>
</html>
