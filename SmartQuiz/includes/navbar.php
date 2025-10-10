<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_role = $_SESSION['user_role'] ?? 'user';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Minimalist Light Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="<?= $base_url ?>">
            <?= ($user_role === 'admin') ? "QuizMaster Admin" : "QuizMaster" ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($is_logged_in && $user_role === 'user'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'quiz_list.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>user/quiz_list.php">Take Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'results.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>user/results.php">Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'leaderboard.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>leaderboard.php">Leaderboard</a>
                    </li>
                <?php elseif ($is_logged_in && $user_role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'manage.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>admin/quizzes/manage.php">Manage Quizzes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'admin_results.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>admin/admin_results.php">Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'leaderboard.php') ? 'active fw-bold' : '' ?>" href="<?= $base_url ?>leaderboard.php">Leaderboard</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Right User Section -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item me-2">
                        <span class="navbar-text text-secondary">
                            Hi, <?= htmlspecialchars($user_name) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger btn-sm" href="<?= $base_url ?>logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-primary fw-semibold" href="<?= $base_url ?>login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.navbar-nav .nav-link {
    color: #4b5563;
    transition: color 0.2s;
}
.navbar-nav .nav-link:hover {
    color: #1d4ed8;
}
.navbar-nav .nav-link.active {
    color: #1d4ed8;
    border-bottom: 2px solid #1d4ed8;
    border-radius: 0;
}
.navbar-brand {
    font-size: 1.25rem;
}
</style>
