<?php
include '../includes/session_check.php';
include '../includes/config.php';

$logged_in_user_id = $_SESSION['user_id'] ?? null;

// Fetch all results with user info
$stmt = $conn->prepare("
    SELECT r.*, q.title AS quiz_title, u.name AS user_name
    FROM results r
    JOIN quizzes q ON r.quiz_id = q.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.attempted_at DESC
");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results | Quiz System</title>
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
        <h2 class="text-center">Quiz Results</h2>

        <?php if (empty($results)): ?>
            <p class="text-center text-muted mt-4">No quiz attempts yet.</p>
        <?php else: ?>
            <table class="table table-striped table-hover mt-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Quiz Title</th>
                        <th>Score</th>
                        <th>Correct Answers</th>
                        <th>Total Questions</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $r): ?>
                        <?php
                            // Highlight row if it belongs to logged-in user
                            $highlight_class = ($r['user_id'] == $logged_in_user_id) ? 'highlight' : '';
                        ?>
                        <tr class="<?= $highlight_class ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($r['user_name']) ?></td>
                            <td><?= htmlspecialchars($r['quiz_title']) ?></td>
                            <td><?= htmlspecialchars($r['score']) ?></td>
                            <td><?= htmlspecialchars($r['correct_answers']) ?></td>
                            <td><?= htmlspecialchars($r['total_questions']) ?></td>
                            <td><?= htmlspecialchars($r['attempted_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
