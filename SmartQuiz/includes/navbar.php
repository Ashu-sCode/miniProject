<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch global base URL
include_once 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_role = $_SESSION['user_role'] ?? 'user';

// Get current page filename for active highlight
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand fw-bold" href="<?= $base_url ?>">
        <?= ($user_role === 'admin') ? "QuizMaster Admin" : "QuizMaster" ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
            <?php if ($is_logged_in && $user_role === 'user'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>user/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'quiz_list.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>user/quiz_list.php">Take Quiz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'results.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>user/results.php">Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'leaderboard.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>leaderboard.php">Leaderboard</a>
                </li>
            <?php elseif ($is_logged_in && $user_role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>admin/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'manage.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>admin/quizzes/manage.php">Manage Quizzes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'admin_results.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>admin/admin_results.php">Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'leaderboard.php') ? 'active' : '' ?>" 
                       href="<?= $base_url ?>leaderboard.php">Leaderboard</a>
                </li>
            <?php endif; ?>
        </ul>

        <ul class="navbar-nav ms-auto">
            <?php if ($is_logged_in): ?>
                <li class="nav-item">
                    <span class="navbar-text text-white me-3">Hi, <?= htmlspecialchars($user_name) ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger btn-sm" href="<?= $base_url ?>logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
