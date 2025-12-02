<?php
session_start();
require_once "db.php";

$newUserId = $_GET['newUserId'] ?? "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $userId = $_POST['userId'];
    $first = $_POST['firstName'];
    $last = $_POST['lastName'];
    $nick = $_POST['nickName'];
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (userId, firstName, lastName, nickName, hashed)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        $message = "Database prepare error.";
    } else {
        $stmt->bind_param("sssss", $userId, $first, $last, $nick, $hash);

        if ($stmt->execute()) {
            $_SESSION['userId'] = $userId;
            $_SESSION['firstName'] = $first;
            $_SESSION['lastName'] = $last;
            $_SESSION['nickName'] = $nick;
            header("Location: index.php");
            exit;
        } else {
            $message = "Error: This user ID may already exist.";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Register New User</h2>

    <p style="color:red;"><?= $message ?></p>

    <form method="POST">
        User ID: <input type="text" name="userId" value="<?= $newUserId ?>" required><br><br>
        First Name: <input type="text" name="firstName" required><br><br>
        Last Name: <input type="text" name="lastName" required><br><br>
        Nickname: <input type="text" name="nickName"><br><br>
        Password: <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>