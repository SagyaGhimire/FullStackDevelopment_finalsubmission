<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

if (isset($_POST['add_category'])) {
    verify_csrf_token($_POST['csrf_token']);

    $pdo->prepare(
        "INSERT INTO categories (category_name) VALUES (?)"
    )->execute([$_POST['category_name']]);

    header("Location: categories.php");
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare(
        "DELETE FROM categories WHERE category_id=?"
    )->execute([$_GET['delete']]);

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
    <h1>Manage Categories</h1>
</div>

<div class="card">
    <h3>Add Category</h3>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>
</div>

<div class="card">
    <h3>Genre List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Genre Name</th>
            <th>Action</th>
        </tr>

        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['category_id']; ?></td>
            <td><?= htmlspecialchars($cat['category_name']); ?></td>
            <td class="action-links">
                <a href="?delete=<?= $cat['category_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="footer">
            &copy; <?php echo date("Y"); ?> Sagya Ghimire Library Management System
        </div>
</div>
</div>

</body>
</html>
