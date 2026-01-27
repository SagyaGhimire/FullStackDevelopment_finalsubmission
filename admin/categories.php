<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

if (isset($_POST['add_category'])) {
    $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)")
        ->execute([$_POST['category_name']]);
    header("Location: categories.php");
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM categories WHERE category_id=?")
        ->execute([$_GET['delete']]);
    header("Location: categories.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
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
<div class="header"><h1>Manage Categories</h1></div>

<div class="card">
<form method="post">
    <input name="category_name" placeholder="Category Name" required>
    <button name="add_category">Add Category</button>
</form>
</div>

<div class="card">
<table>
<tr><th>ID</th><th>Name</th><th>Action</th></tr>
<?php foreach ($categories as $c): ?>
<tr>
<td><?= $c['category_id'] ?></td>
<td><?= htmlspecialchars($c['category_name']) ?></td>
<td class="action-links">
<a href="?delete=<?= $c['category_id'] ?>" onclick="return confirm('Delete category?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>
</body>
</html>
