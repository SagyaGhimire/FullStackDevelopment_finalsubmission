<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

//fetching student data
$students = $pdo->query("SELECT * FROM students")->fetchAll();

//fetching the available books
$books = $pdo->query("SELECT * FROM books WHERE quantity > 0")->fetchAll();
//issuing books
if (isset($_POST['issue_book'])) {
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

    // successful popup message
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
<form method="post">
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

</div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<script>
    alert("<?= $_SESSION['success']; ?>");
</script>
<?php unset($_SESSION['success']); endif; ?>

</body>
</html>