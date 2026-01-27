<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Checking if the ID exists or not
if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit;
}

$id = $_GET['id'];

// Fetching book data
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found";
    exit;
}

// Fetching categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Updating books
if (isset($_POST['update_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category_id = $_POST['category_id'];
    $year = $_POST['publication_year'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare(
        "UPDATE books 
         SET title=?, author=?, category_id=?, publication_year=?, quantity=?
         WHERE book_id=?"
    );

    $stmt->execute([
        $title,
        $author,
        $category_id,
        $year,
        $quantity,
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

<h2>Edit Book</h2>

<form method="post">
    <label>Title</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br><br>

    <label>Author</label><br>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br><br>

    <label>Category</label><br>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>"
                <?php if ($cat['category_id'] == $book['category_id']) echo "selected"; ?>>
                <?php echo htmlspecialchars($cat['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Publication Year</label><br>
    <input type="number" name="publication_year"
           value="<?php echo $book['publication_year']; ?>" required><br><br>

    <label>Quantity</label><br>
    <input type="number" name="quantity"
           value="<?php echo $book['quantity']; ?>" required><br><br>

    <button type="submit" name="update_book">Update Book</button>
</form>

</body>
</html>
