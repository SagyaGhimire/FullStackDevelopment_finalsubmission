<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../includes/csrf.php";

/* Check if ID exists */
if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit;
}

$id = $_GET['id'];

/* Fetch existing student */
$stmt = $pdo->prepare(
    "SELECT * FROM students WHERE student_id = ?"
);
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student not found";
    exit;
}

/* Update student */
if (isset($_POST['update_student'])) {
    verify_csrf_token($_POST['csrf_token']);

    $stmt = $pdo->prepare(
        "UPDATE students
         SET name = ?, email = ?, roll_no = ?, department = ?
         WHERE student_id = ?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['roll_no'],
        $_POST['department'],
        $id
    ]);

    header("Location: students.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
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
    <h1>Edit Student</h1>
</div>

<div class="card">
<form method="post">

    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

    <label>Student Name</label>
    <input type="text" name="name"
           value="<?= htmlspecialchars($student['name']); ?>" required>

    <label>Email Address</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($student['email']); ?>">

    <label>Roll Number</label>
    <input type="text" name="roll_no"
           value="<?= htmlspecialchars($student['roll_no']); ?>">

    <label>Department of Student</label>
    <input type="text" name="department"
           value="<?= htmlspecialchars($student['department']); ?>">

    <button type="submit" name="update_student">Update Student</button>

</form>
</div>

</div>
</div>

</body>
</html>
