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
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; }
        .results-container { max-width: 900px; margin: 50px auto; }
        .card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 20px; }
        table { margin-top: 20px; }
        th, td { vertical-align: middle !important; }
        .highlight { background-color: #d1e7dd !important; font-weight: bold; }
    </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="results-container">
    <div class="card">
        <h2 class="text-center">My Quiz Results</h2>

        <?php if (empty($results)): ?>
            <p class="text-center text-muted mt-4">You haven't attempted any quizzes yet.</p>
        <?php else: ?>
            <table class="table table-striped table-hover mt-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quiz Title</th>
                        <th>Score</th>
                        <th>Correct Answers</th>
                        <th>Total Questions</th>
                        <th>Date & Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $r): ?>
                        <?php
                            $highlight_class = 'highlight'; // since all rows are current user
                            $retake = ($r['score'] < ($r['total_questions'] / 2));
                        ?>
                        <tr class="<?= $highlight_class ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($r['quiz_title']) ?></td>
                            <td><?= htmlspecialchars($r['score']) ?></td>
                            <td><?= htmlspecialchars($r['correct_answers']) ?></td>
                            <td><?= htmlspecialchars($r['total_questions']) ?></td>
                            <td><?= htmlspecialchars($r['attempted_at']) ?></td>
                            <td>
                                <?php if ($retake): ?>
                                    <a href="quiz.php?quiz_id=<?= $r['quiz_id'] ?>" class="btn btn-sm btn-warning">Retake Quiz</a>
                                <?php else: ?>
                                    <span class="text-success">Passed ✅</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
