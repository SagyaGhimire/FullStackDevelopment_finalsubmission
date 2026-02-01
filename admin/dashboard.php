<?php
require_once "../includes/auth.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            <h1>Welcome to Library Management System, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
        </div>

        <div class="card">
            <h3>Search Books</h3>
            <div class="search-box">
                <input type="text" id="searchBox" placeholder="Authorname, Bookname or Category">
            </div>
            <ul id="result"></ul>
        </div>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Sagya Ghimire Library Management System
        </div>

    </div>
</div>

<script>
document.getElementById("searchBox").addEventListener("keyup", function () {
    let term = this.value.trim();

    if (term.length < 2) {
        document.getElementById("result").innerHTML = "";
        return;
    }

    fetch("../ajax/search_books.php?term=" + encodeURIComponent(term))
        .then(res => res.json())
        .then(data => {
            let list = "";

            data.forEach(item => {
                list += `
                    <li>
                        <a href="book_details.php?id=${item.book_id}">
                            <strong>${item.title}</strong>
                        </a><br>
                        <small>
                            Author Name: ${item.author} |
                            Genre: ${item.category}
                        </small>
                    </li>
                `;
            });

            document.getElementById("result").innerHTML = list;
        });
});
</script>

</body>
</html>
