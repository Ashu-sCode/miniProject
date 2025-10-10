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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { 
    background: #f0f4f8;
    font-family: 'Poppins', sans-serif; 
    color: #333;
}

h2 { font-weight: 600; color: #1d4ed8; }

.quiz-card {
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.25s, box-shadow 0.25s;
    cursor: pointer;
    position: relative;
    color: #1f2937;
    min-height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
}

.quiz-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
}

.quiz-card .card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.quiz-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.quiz-desc {
    font-size: 0.95rem;
    margin-bottom: 1.2rem;
    flex-grow: 1;
    color: #4b5563;
}

.badge {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 12px;
    font-weight: bold;
    background: rgba(255,255,255,0.8);
    color: #1d4ed8;
}

.start-btn {
    width: 100%;
    font-weight: 600;
    background-color: #5563DE;
    color: #fff;
    transition: background 0.25s, transform 0.25s;
}

.start-btn:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
}

@media (max-width: 992px) { .quiz-card { min-height: 200px; } }
@media (max-width: 576px) { 
    .quiz-card { min-height: 180px; }
    .quiz-title { font-size: 1.1rem; }
    .quiz-desc { font-size: 0.9rem; }
}
</style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Available Quizzes 🎮</h2>
    <div class="row g-4">
        <?php if ($quizzes): ?>
            <?php foreach ($quizzes as $quiz): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="quiz-card">
                        <span class="badge"><i class="fa fa-clock"></i> <?= $quiz['time_limit'] ?> min</span>
                        <div class="card-body">
                            <div>
                                <div class="quiz-title"><?= htmlspecialchars($quiz['title']) ?></div>
                                <div class="quiz-desc"><?= htmlspecialchars($quiz['description'] ?? 'Challenge yourself!') ?></div>
                            </div>
                            <a href="quiz.php?quiz_id=<?= $quiz['id'] ?>" class="btn start-btn mt-2">Start Quiz <i class="fa fa-arrow-right"></i></a>
                        </div>
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
