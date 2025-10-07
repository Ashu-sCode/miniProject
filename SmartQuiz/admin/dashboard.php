<?php
include '../includes/session_check.php';
include '../includes/config.php';

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard text-center mt-4">
        <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
        <p class="text-muted">Manage your quizzes and users efficiently.</p>
        <!-- Cards -->
    </div>
</body>
</html>
