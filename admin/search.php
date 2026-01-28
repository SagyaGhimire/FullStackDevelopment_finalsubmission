<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

//fetching categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$books = [];

if (isset($_GET['search'])) {
    $title = "%" . $_GET['title'] . "%";
    $category = $_GET['category_id'];
    $year = $_GET['year'];

    $sql = "SELECT books.*, categories.category_name
            FROM books
            LEFT JOIN categories ON books.category_id = categories.category_id
            WHERE title LIKE ?";

    $params = [$title];

    if (!empty($category)) {
        $sql .= " AND books.category_id = ?";
        $params[] = $category;
    }

    if (!empty($year)) {
        $sql .= " AND publication_year = ?";
        $params[] = $year;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced Search</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Advanced Book Search</h2>
<a href="dashboard.php">Back to Dashboard</a>

<hr>

<form method="get">
    <input type="text" name="title" placeholder="Book Title">

    <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>">
                <?php echo htmlspecialchars($cat['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="year" placeholder="Year">

    <button type="submit" name="search">Search</button>
</form>

<hr>

<?php if ($books): ?>
<table border="1" cellpadding="5">
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Category</th>
    <th>Year</th>
    <th>Quantity</th>
</tr>

<?php foreach ($books as $b): ?>
<tr>
    <td><?php echo htmlspecialchars($b['title']); ?></td>
    <td><?php echo htmlspecialchars($b['author']); ?></td>
    <td><?php echo htmlspecialchars($b['category_name']); ?></td>
    <td><?php echo $b['publication_year']; ?></td>
    <td><?php echo $b['quantity']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</body>
</html>
