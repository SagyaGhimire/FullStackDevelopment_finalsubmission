<?php
session_start();
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["username"] = $admin["username"];
        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Admin Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-page">

    <div class="login-box">
        <h2>Library Admin Login</h2>

        <form method="post">
            <label>Enter your Username:</label>
            <input type="text" name="username" required>

            <label>Enter your Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <?php if ($error): ?>
            <p style="color:#b00020; font-size:13px; margin-top:10px; text-align:center;">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <div class="login-footer">
            ©<?php echo date("Y"); ?> Sagya Ghimire Library Management System
        </div>
    </div>

</div>
