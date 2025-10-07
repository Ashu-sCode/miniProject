<?php
include '../includes/session_check.php';
include '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard { max-width: 900px; margin: 50px auto; text-align: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform .2s; }
        .card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard">
        <h2 class="mt-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
        <p class="text-muted">Get ready to test your knowledge!</p>

        <div class="row mt-5 g-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white p-4">
                    <h4>🎯 Take a Quiz</h4>
                    <p>Start a quiz and challenge yourself.</p>
                    <a href="quiz_list.php" class="btn btn-light btn-sm mt-2">Start Now</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white p-4">
                    <h4>📊 My Results</h4>
                    <p>View your previous attempts and scores.</p>
                    <a href="results.php" class="btn btn-light btn-sm mt-2">View Results</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
