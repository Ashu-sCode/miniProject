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

// Initialize result variables
$show_result = false;
$score = 0;
$total = count($questions);

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Show result on same page
    $show_result = true;
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
        .card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 20px; }
        /* Floating result card */
        .result-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            width: 400px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            padding: 30px;
            text-align: center;
            display: none;
        }
        .result-popup h3 { margin-bottom: 20px; }
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 1040;
        }
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

<!-- Result Popup -->
<div class="overlay" id="overlay"></div>
<div class="result-popup" id="resultPopup">
    <h3>Quiz Completed!</h3>
    <p><strong>Score:</strong> <?= $score ?> / <?= $total ?></p>
    <p><strong>Correct Answers:</strong> <?= $score ?></p>
    <p><strong>Total Questions:</strong> <?= $total ?></p>
    <button class="btn btn-primary" id="closePopup">Close</button>
</div>

<script>
    <?php if ($show_result): ?>
    // Show result popup
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('resultPopup').style.display = 'block';

    document.getElementById('closePopup').onclick = function() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('resultPopup').style.display = 'none';
        // Optionally redirect back to quiz list
        window.location.href = 'quiz_list.php';
    };
    <?php endif; ?>
</script>

</body>
</html>
