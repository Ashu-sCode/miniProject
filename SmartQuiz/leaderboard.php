<?php
include './includes/session_check.php';
include './includes/config.php'

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
body {
    font-family: 'Poppins', sans-serif;
    background: #f8fafc;
    margin: 0;
    padding: 0;
}

.leaderboard-container {
    max-width: 950px;
    margin: 60px auto;
}

.card {
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    padding: 30px;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #1f2937;
}

table {
    border-collapse: separate;
    border-spacing: 0 10px;
}

thead th {
    background: #1f2937;
    color: #fff;
    border: none;
    padding: 12px 15px;
    border-radius: 8px;
}

tbody td {
    background: #fff;
    padding: 12px 15px;
    border-radius: 8px;
    vertical-align: middle;
}

tbody tr:hover td {
    background-color: #e2e8f0;
    transition: background 0.2s;
}

.highlight td {
    background-color: #fef3c7 !important;
    font-weight: 500;
}

.rank-badge {
    display: inline-block;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 10px;
    color: #fff;
    text-align: center;
    min-width: 28px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.first { background: linear-gradient(45deg, #FFD700, #FFC700); }
.second { background: linear-gradient(45deg, #C0C0C0, #A9A9A9); }
.third { background: linear-gradient(45deg, #CD7F32, #B87333); }

.progress {
    height: 14px;
    border-radius: 10px;
    background: #e5e7eb;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, #3b82f6, #60a5fa);
    transition: width 0.4s ease-in-out;
}
</style>
</head>
<body>
<?php include './includes/navbar.php'; ?>

<div class="leaderboard-container">
    <div class="card">
        <h2>🏆 Quiz Leaderboard</h2>

        <?php if (empty($leaderboard)): ?>
            <p class="text-center text-muted mt-4">No results available yet.</p>
        <?php else: ?>
            <table class="table">
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
                    <?php foreach ($leaderboard as $index => $row): 
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
                        <td><span class="rank-badge <?= $rank_class ?>"><?= $index + 1 ?></span></td>
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
