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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #74ABE2, #5563DE);
        min-height: 100vh;
        margin: 0;
        color: #fff;
    }

    .dashboard {
        max-width: 1000px;
        margin: 50px auto;
        text-align: center;
        padding: 0 15px;
    }

    h2 { font-weight: 700; margin-bottom: 5px; }
    p.subtitle { color: rgba(255,255,255,0.85); margin-bottom: 40px; }

    .panel-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
    }

    .panel {
        flex: 1 1 300px;
        min-width: 280px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 2rem;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .panel:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .panel h3 {
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .panel p {
        color: rgba(255,255,255,0.8);
        margin-bottom: 20px;
    }

    .panel i {
        font-size: 3rem;
        position: absolute;
        top: 15px;
        right: 15px;
        opacity: 0.2;
    }

    .panel a {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        background: #fff;
        color: #5563DE;
        font-weight: 600;
        transition: background 0.3s, transform 0.3s;
    }

    .panel a:hover {
        background: #f0f0f0;
        transform: translateY(-3px);
    }

    @media(max-width: 768px){
        .panel-container { flex-direction: column; gap: 20px; }
    }
</style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
        <p class="subtitle">Get ready to test your knowledge!</p>

        <div class="panel-container">
            <div class="panel" onclick="location.href='quiz_list.php'">
                <i class="bi bi-patch-question-fill"></i>
                <h3>Take a Quiz</h3>
                <p>Start a quiz and challenge yourself with different topics.</p>
                <a href="quiz_list.php">Start Now</a>
            </div>

            <div class="panel" onclick="location.href='results.php'">
                <i class="bi bi-bar-chart-fill"></i>
                <h3>My Results</h3>
                <p>Check your previous attempts, scores, and performance trends.</p>
                <a href="results.php">View Results</a>
            </div>
        </div>
    </div>
</body>
</html>
