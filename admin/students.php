<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

//adding students
if (isset($_POST['add_student'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO students (name, email, roll_no, department)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['roll_no'],
        $_POST['department']
    ]);
    header("Location: students.php");
    exit;
}

//Deleting students
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: students.php");
    exit;
}

// Fetching students 
$students = $pdo->query("SELECT * FROM students")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
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

<div class="header"><h1>Manage Students</h1></div>

<div class="card">
<h3>Add Student</h3>
<form method="post">
    <input type="text" name="name" placeholder="Student Name" required>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="roll_no" placeholder="Roll Number">
    <input type="text" name="department" placeholder="Department">
    <button type="submit" name="add_student">Add Student</button>
</form>
</div>

<div class="card">
<h3>Student List</h3>
<table>
<tr>
    <th>ID</th><th>Name</th><th>Email</th><th>Roll No</th><th>Department</th><th>Action</th>
</tr>
<?php foreach ($students as $s): ?>
<tr>
    <td><?= $s['student_id'] ?></td>
    <td><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['email']) ?></td>
    <td><?= htmlspecialchars($s['roll_no']) ?></td>
    <td><?= htmlspecialchars($s['department']) ?></td>
    <td class="action-links">
        <a href="edit_student.php?id=<?= $s['student_id'] ?>">Edit</a>|
        <a href="?delete=<?= $s['student_id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>
</body>
</html>
