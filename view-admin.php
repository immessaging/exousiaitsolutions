<?php

session_start();
require_once 'config.php'; // Database connection

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if ($admin && password_verify($password, $admin['password_hash'])) {
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];
    header("Location: admdashboard.php");
    exit();
} else {
    header("Location: admin.html?error=invalid_credentials");
    exit();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.html");
    exit();
}

$stmt = $pdo->query("SELECT * FROM admins");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Admins</title>
    <link rel="stylesheet" href="../css/admin111.css">
</head>
<body>
    <h2>Admin List</h2>                      <a href="logout.php" style="border: groove black 2px; border-radius:19px; color:white; background-color:black;">Logout</a>
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?= $admin['admin_id'] ?></td>
            <td><?= htmlspecialchars($admin['username']) ?></td>
            <td><?= htmlspecialchars($admin['email']) ?></td>
            <td><?= $admin['role'] ?></td>
            <td>
                <a href="edit-admin.php?id=<?= $admin['admin_id'] ?>">Edit</a>
                <a href="delete-admin.php?id=<?= $admin['admin_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>