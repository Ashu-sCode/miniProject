<?php
include './includes/session_check.php';
include './includes/config.php';

$logged_in_user_id = $_SESSION['user_id'] ?? null;

// Fetch leaderboard: sum scores for all quizzes per user
$stmt = $conn->prepare("
    SELECT u.id AS user_id, u.name AS user_name, 
           SUM(r.score) AS total_score,
           SUM(r.correct_answers) AS total_correct,
           SUM(r.total_questions) AS total_questions
    FROM users u
    LEFT JOIN results r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY total_score DESC
");
$stmt->execute();
$leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard | Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
        .leaderboard-container { max-width: 900px; margin: 50px auto; }
        .card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 20px; }
        .highlight { background-color: #ffe4b5 !important; font-weight: bold; }
        .rank-badge { font-size: 1rem; font-weight: bold; padding: 5px 10px; border-radius: 10px; color: #fff; }
        .first { background: gold; }
        .second { background: silver; }
        .third { background: #cd7f32; }
        .progress { height: 15px; border-radius: 10px; }
    </style>
</head>
<body>
<?php include './includes/navbar.php'; ?>

<div class="leaderboard-container">
    <div class="card">
        <h2 class="text-center mb-4">🏆 Quiz Leaderboard</h2>

        <?php if (empty($leaderboard)): ?>
            <p class="text-center text-muted mt-4">No results available yet.</p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>
                        <th>Total Score</th>
                        <th>Correct Answers</th>
                        <th>Total Questions</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $index => $row): ?>
                        <?php
                            $highlight_class = ($row['user_id'] == $logged_in_user_id) ? 'highlight' : '';
                            $rank_class = '';
                            if ($index == 0) $rank_class = 'first';
                            elseif ($index == 1) $rank_class = 'second';
                            elseif ($index == 2) $rank_class = 'third';

                            $progress = ($row['total_questions'] > 0) 
                                ? ($row['total_correct'] / $row['total_questions']) * 100 
                                : 0;
                        ?>
                        <tr class="<?= $highlight_class ?>">
                            <td>
                                <span class="rank-badge <?= $rank_class ?>"><?= $index + 1 ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= $row['total_score'] ?? 0 ?></td>
                            <td><?= $row['total_correct'] ?? 0 ?></td>
                            <td><?= $row['total_questions'] ?? 0 ?></td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
