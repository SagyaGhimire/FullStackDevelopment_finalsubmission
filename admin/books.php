<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

if (isset($_POST['add_book'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO books (title, author, category_id, publication_year, quantity)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $_POST['title'],
        $_POST['author'],
        $_POST['category_id'],
        $_POST['publication_year'],
        $_POST['quantity']
    ]);
    header("Location: books.php");
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM books WHERE book_id=?")->execute([$_GET['delete']]);
    header("Location: books.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$books = $pdo->query(
    "SELECT books.*, categories.category_name
     FROM books LEFT JOIN categories
     ON books.category_id = categories.category_id"
)->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="wrapper">
<div class="sidebar">
    <h2>Library System</h2>
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
<div class="header"><h1>Manage Books</h1></div>

<div class="card">
<h3>Add Book</h3>
<form method="post">
    <input name="title" placeholder="Book Title" required>
    <input name="author" placeholder="Author" required>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $c): ?>
        <option value="<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="publication_year" placeholder="Year" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <button name="add_book">Add Book</button>
</form>
</div>

<div class="card">
<h3>Book List</h3>
<table>
<tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Year</th><th>Quantity</th><th>Action</th></tr>
<?php foreach ($books as $b): ?>
<tr>
<td><?= $b['book_id'] ?></td>
<td><?= htmlspecialchars($b['title']) ?></td>
<td><?= htmlspecialchars($b['author']) ?></td>
<td><?= htmlspecialchars($b['category_name']) ?></td>
<td><?= $b['publication_year'] ?></td>
<td><?= $b['quantity'] ?></td>
<td class="action-links">
<a href="edit_book.php?id=<?= $b['book_id'] ?>">Edit</a>|
<a href="?delete=<?= $b['book_id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>
</body>
</html>
