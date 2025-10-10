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
    background: #f0f4f8;
    min-height: 100vh;
    margin: 0;
    color: #333;
}

.dashboard {
    max-width: 1000px;
    margin: 50px auto;
    text-align: center;
    padding: 0 15px;
}

h2 { font-weight: 700; margin-bottom: 5px; color: #1d4ed8; }
p.subtitle { color: #555; margin-bottom: 40px; }

.panel-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.panel {
    flex: 1 1 300px;
    min-width: 280px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 2rem;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    color: #1d4ed8;
}

.panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.panel h3 {
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.panel p {
    color: #1f2937;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.panel i {
    font-size: 3rem;
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0.15;
}

.panel a {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    background: #1d4ed8;
    color: #fff;
    font-weight: 600;
    transition: background 0.3s, transform 0.3s;
}

.panel a:hover {
    background: #1e40af;
    transform: translateY(-2px);
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
                <p>Challenge yourself with quizzes across various topics.</p>
                <a href="quiz_list.php">Start Now</a>
            </div>

            <div class="panel" onclick="location.href='results.php'">
                <i class="bi bi-bar-chart-fill"></i>
                <h3>My Results</h3>
                <p>Review your attempts and track your progress over time.</p>
                <a href="results.php">View Results</a>
            </div>
        </div>
    </div>
</body>
</html>
