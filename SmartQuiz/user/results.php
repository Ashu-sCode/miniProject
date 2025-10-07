<?php
include '../includes/session_check.php';
include '../includes/config.php';

$logged_in_user_id = $_SESSION['user_id'] ?? null;

// Fetch all results for the logged-in user
$stmt = $conn->prepare("
    SELECT r.*, q.title AS quiz_title
    FROM results r
    JOIN quizzes q ON r.quiz_id = q.id
    WHERE r.user_id = ?
    ORDER BY r.attempted_at DESC
");
$stmt->execute([$logged_in_user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Quiz Results | Quiz System</title>
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
.results-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 0 15px;
}
h2 { text-align: center; margin-bottom: 30px; font-weight: 700; }

/* Panel-style results */
.result-panel {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 1.5rem 2rem;
    margin-bottom: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}
.result-panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
.result-panel h4 {
    margin: 0;
    font-weight: 600;
}
.result-panel p {
    margin: 5px 0;
    color: rgba(255,255,255,0.85);
}
.result-panel .badge {
    font-size: 0.9rem;
    padding: 0.4rem 0.8rem;
    border-radius: 12px;
    font-weight: 600;
}
.result-panel .pass { background-color: #28a745; color: #fff; }
.result-panel .fail { background-color: #ffc107; color: #333; }
.result-panel a.btn {
    margin-top: 10px;
}

/* Icon decoration */
.result-panel i {
    font-size: 2.5rem;
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0.2;
}

@media(max-width: 576px){
    .result-panel { padding: 1rem 1.5rem; }
}
</style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="results-container">
    <h2>My Quiz Results</h2>

    <?php if (empty($results)): ?>
        <p class="text-center text-light mt-4">You haven't attempted any quizzes yet.</p>
    <?php else: ?>
        <?php foreach ($results as $r): 
            $retake = ($r['score'] < ($r['total_questions'] / 2));
            $status_class = $retake ? 'fail' : 'pass';
            $status_text = $retake ? 'Retake Recommended ⚠️' : 'Passed ✅';
        ?>
        <div class="result-panel">
            <i class="bi bi-journal-check"></i>
            <h4><?= htmlspecialchars($r['quiz_title']) ?></h4>
            <p>Score: <?= htmlspecialchars($r['score']) ?> / <?= htmlspecialchars($r['total_questions']) ?></p>
            <p>Correct Answers: <?= htmlspecialchars($r['correct_answers']) ?></p>
            <p>Date: <?= htmlspecialchars($r['attempted_at']) ?></p>
            <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
            <?php if ($retake): ?>
                <a href="quiz.php?quiz_id=<?= $r['quiz_id'] ?>" class="btn btn-light btn-sm">Retake Quiz</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
