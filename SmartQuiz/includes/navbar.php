<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_role = $_SESSION['user_role'] ?? 'user';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand fw-bold" href="#">
        <?php echo ($user_role === 'admin') ? "QuizMaster Admin" : "QuizMaster"; ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
            <?php if ($is_logged_in && $user_role === 'user'): ?>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/user/dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/user/quiz_list.php">Take Quiz</a></li>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/user/results.php">Results</a></li>
            <?php elseif ($is_logged_in && $user_role === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/admin/dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/admin/manage_quiz.php">Manage Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/admin/manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link" href="/SmartQuiz/admin/admin_results.php">Results</a></li>
            <?php endif; ?>
        </ul>

        <ul class="navbar-nav ms-auto">
            <?php if ($is_logged_in): ?>
                <li class="nav-item">
                    <span class="navbar-text text-white me-3">Hi, <?php echo htmlspecialchars($user_name); ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger btn-sm" href="../logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
