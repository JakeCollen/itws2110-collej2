<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = trim($_POST['userId']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header("Location: register.php?newUserId=" . urlencode($userId));
        exit;
    }

    if (password_verify($password, $user['hashed'])) {
        $_SESSION['userId'] = $userId;
        header("Location: index.php");
        exit;
    } else {
        $message = "Incorrect password. Try again.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <p style="color:red;"><?= $message ?></p>

    <form method="POST">
        User ID: <input type="text" name="userId" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

</body>
</html>
