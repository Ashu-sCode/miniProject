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
    <style>
        .dashboard { max-width: 1100px; margin: 50px auto; text-align: center; }
        .card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform .2s; }
        .card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard">
        <h2 class="mt-4">Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
        <p class="text-muted">Manage your quizzes and users efficiently.</p>

        <div class="row mt-5 g-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-4">
                    <h4>📝 Manage Quizzes</h4>
                    <p>Add, edit or delete quizzes.</p>
                    <a href="quizzes/manage.php" class="btn btn-light btn-sm mt-2">Open</a>
                </div>
            </div>
   
            <div class="col-md-4">
                <div class="card bg-warning text-dark p-4">
                    <h4>📈 View Results</h4>
                    <p>Track user performance and quiz stats.</p>
                    <a href="admin_results.php" class="btn btn-dark btn-sm mt-2">Open</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
