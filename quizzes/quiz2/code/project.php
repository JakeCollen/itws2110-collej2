<?php
session_start();

require_once "db.php";

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$users = [];
$result = $conn->query("SELECT userId, firstName, lastName FROM users ORDER BY lastName");

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['projectName']);
    $description = trim($_POST['description']);
    $members = $_POST['members'] ?? [];

    $stmt = $conn->prepare("SELECT * FROM projects WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    $stmt->close();

    if ($checkResult->num_rows > 0) {
        $error = "A project with that name already exists!";
    } else if (count($members) < 3) {
        $error = "You must select at least 3 members!";
    } else {

        $stmt = $conn->prepare("INSERT INTO projects (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $projectId = $conn->insert_id;
        $stmt->close();

        $stmt2 = $conn->prepare("INSERT INTO projectMembership (projectId, memberId) VALUES (?, ?)");

        foreach ($members as $m) {
            $stmt2->bind_param("is", $projectId, $m);
            $stmt2->execute();
        }

        $stmt2->close();

        header("Location: project.php?added=" . $projectId);
        exit();
    }
}

$projects = [];
$result = $conn->query("SELECT * FROM projects ORDER BY projectId DESC");

if ($result) {
    $projects = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Add a Project</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="project.php">
    <label>Project Name:</label><br>
    <input type="text" name="projectName" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="50" required></textarea><br><br>

    <h3>Select Project Members (minimum 3):</h3>

    <?php foreach ($users as $u): ?>
        <label>
            <input type="checkbox" name="members[]" value="<?= $u['userId'] ?>">
            <?= htmlspecialchars($u['firstName'] . " " . $u['lastName']) ?>
        </label><br>
    <?php endforeach; ?>

    <button type="submit">Add Project</button>
</form>

<h2 id="viewProjects">Existing Projects</h2>

<?php foreach ($projects as $p):
    $highlight = "";
    if (isset($_GET['added']) && $_GET['added'] == $p['projectId']) {
        $highlight = "highlight";
    }
?>
    <div class="project-box <?= $highlight ?>">
        <strong><?= htmlspecialchars($p['name']) ?></strong><br>
        <?= htmlspecialchars($p['description']) ?><br><br>

        <em>Members:</em>
        <ul class="member-list">
            <?php
                $membersStmt = $conn->prepare("
                    SELECT u.firstName, u.lastName 
                    FROM users u
                    JOIN projectMembership pm ON u.userId = pm.memberId
                    WHERE pm.projectId = ?
                ");
                $membersStmt->bind_param("i", $p['projectId']);
                $membersStmt->execute();
                $membersResult = $membersStmt->get_result();
                $projectMembers = $membersResult->fetch_all(MYSQLI_ASSOC);

                foreach ($projectMembers as $m):
            ?>
                <li><?= htmlspecialchars($m['firstName'] . " " . $m['lastName']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>

</body>
</html>
