<?php
include '../includes/session_check.php';
include '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($_GET['quiz_id'])) {
    header("Location: quiz_list.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch questions for this quiz
$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($questions);

    foreach ($questions as $question) {
        $q_id = $question['id'];
        $user_answer = $_POST['question'][$q_id] ?? '';

        if (strtoupper($user_answer) === strtoupper($question['correct_option'])) {
            $score++;
        }
    }

    // Save result
    $stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score, total_questions, correct_answers) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $quiz_id, $score, $total, $score]);

    header("Location: results.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quiz['title']) ?> | Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; }
        .quiz-container { max-width: 800px; margin: 50px auto; }
        .card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="quiz-container">
        <h2 class="text-center mb-4"><?= htmlspecialchars($quiz['title']) ?></h2>
        <p class="text-center text-muted mb-5"><?= htmlspecialchars($quiz['description']) ?></p>

        <form method="POST" action="">
            <?php foreach ($questions as $index => $q): ?>
                <div class="card p-4 mb-4">
                    <h5>Q<?= $index + 1 ?>. <?= htmlspecialchars($q['question_text']) ?></h5>

                    <?php
                    $options = [
                        'A' => $q['option_a'],
                        'B' => $q['option_b'],
                        'C' => $q['option_c'],
                        'D' => $q['option_d']
                    ];
                    ?>

                    <?php foreach ($options as $key => $text): ?>
                        <?php if ($text): ?>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="question[<?= $q['id'] ?>]" id="q<?= $q['id'] ?>_<?= $key ?>" value="<?= $key ?>" required>
                                <label class="form-check-label" for="q<?= $q['id'] ?>_<?= $key ?>">
                                    <?= htmlspecialchars($text) ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">Submit Quiz</button>
            </div>
        </form>
    </div>
</body>
</html>
