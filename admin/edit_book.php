<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

/* Check if ID exists */
if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit;
}
$id = $_GET['id'];

/* Fetch book data */
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found";
    exit;
}

/* Fetch categories */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

/* Update book */
if (isset($_POST['update_book'])) {
    verify_csrf_token($_POST['csrf_token']);

    $stmt = $pdo->prepare(
        "UPDATE books 
         SET title=?, author=?, category_id=?, publication_year=?, quantity=?
         WHERE book_id=?"
    );

    $stmt->execute([
        $_POST['title'],
        $_POST['author'],
        $_POST['category_id'],
        $_POST['publication_year'],
        $_POST['quantity'],
        $id
    ]);

    header("Location: books.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
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
    <h1>Edit Book</h1>
</div>

<div class="card">
<form method="post">

    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

    <label>Title</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($book['title']); ?>" required>

    <label>Author</label>
    <input type="text" name="author"
           value="<?= htmlspecialchars($book['author']); ?>" required>

    <label>Category</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id']; ?>"
                <?= ($cat['category_id'] == $book['category_id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($cat['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Publication Year</label>
    <input type="number" name="publication_year"
           value="<?= $book['publication_year']; ?>" required>

    <label>Quantity</label>
    <input type="number" name="quantity"
           value="<?= $book['quantity']; ?>" required>

    <button type="submit" name="update_book">Update Book</button>

</form>
</div>

</div>
</div>

</body>
</html>
