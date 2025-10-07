<?php
include '../includes/session_check.php';
include '../includes/config.php';

// Fetch all quizzes
try {
    $stmt = $conn->prepare("SELECT * FROM quizzes ORDER BY created_at DESC");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Quizzes | Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Poppins', sans-serif; }
        .quiz-card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform .2s; }
        .quiz-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Available Quizzes</h2>
        <div class="row g-4">
            <?php if ($quizzes): ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card quiz-card p-4 h-100">
                            <h4 class="card-title"><?= htmlspecialchars($quiz['title']) ?></h4>
                            <p class="card-text"><?= htmlspecialchars($quiz['description']) ?></p>
                            <a href="quiz.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-primary mt-2">Start Quiz</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No quizzes available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
