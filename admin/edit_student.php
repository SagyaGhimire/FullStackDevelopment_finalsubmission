<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$id = $_GET['id'];

// Fetch existing student
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

// Update student
if (isset($_POST['update_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $roll_no = $_POST['roll_no'];
    $department = $_POST['department'];

    $stmt = $pdo->prepare(
        "UPDATE students
         SET name = ?, email = ?, roll_no = ?, department = ?
         WHERE student_id = ?"
    );
    $stmt->execute([$name, $email, $roll_no, $department, $id]);

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

<h2>Edit Student</h2>

<form method="post">
    <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>"><br><br>
    <input type="text" name="roll_no" value="<?php echo htmlspecialchars($student['roll_no']); ?>"><br><br>
    <input type="text" name="department" value="<?php echo htmlspecialchars($student['department']); ?>"><br><br>
    <button type="submit" name="update_student">Update Student</button>
</form>

</body>
</html>
