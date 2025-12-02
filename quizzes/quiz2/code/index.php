<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container">
            <h1>Dashboard</h1>
            <nav>
                <a href="project.php">Add Project</a>
                <a href="project.php#viewProjects">View Projects</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['userId']) ?>!</h2>
            <p>Select an option from the navigation bar above.</p>
    </div>
</div>
</body>
</html>
