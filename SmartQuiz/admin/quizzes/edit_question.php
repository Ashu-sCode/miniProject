<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);
$question_id = intval($_GET['question_id'] ?? 0);

if (!$quiz_id || !$question_id) {
    header("Location: view_questions.php?quiz_id=$quiz_id");
    exit();
}

// Fetch quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) die("Quiz not found.");

// Fetch question info
$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ? AND quiz_id = ?");
$stmt->execute([$question_id, $quiz_id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$question) die("Question not found.");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q_text = trim($_POST['question_text'] ?? '');
    $option_a = trim($_POST['option_a'] ?? '');
    $option_b = trim($_POST['option_b'] ?? '');
    $option_c = trim($_POST['option_c'] ?? '');
    $option_d = trim($_POST['option_d'] ?? '');
    $correct = strtoupper($_POST['correct_option'] ?? '');

    if ($q_text && $option_a && $option_b && $option_c && $option_d && in_array($correct, ['A','B','C','D'])) {
        $stmt = $conn->prepare("UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE id = ? AND quiz_id = ?");
        $stmt->execute([$q_text, $option_a, $option_b, $option_c, $option_d, $correct, $question_id, $quiz_id]);

        header("Location: view_questions.php?quiz_id=$quiz_id");
        exit();
    } else {
        $error = "All fields are required and correct option must be valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Question | <?= htmlspecialchars($quiz['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
.container { max-width: 800px; margin: 50px auto; }
.card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); padding: 20px; }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="mb-4">Edit Question for Quiz: <?= htmlspecialchars($quiz['title']) ?></h2>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Question Text</label>
                <input type="text" name="question_text" class="form-control" required value="<?= htmlspecialchars($question['question_text']) ?>">
            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label>Option A</label>
                    <input type="text" name="option_a" class="form-control" required value="<?= htmlspecialchars($question['option_a']) ?>">
                </div>
                <div class="col">
                    <label>Option B</label>
                    <input type="text" name="option_b" class="form-control" required value="<?= htmlspecialchars($question['option_b']) ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label>Option C</label>
                    <input type="text" name="option_c" class="form-control" required value="<?= htmlspecialchars($question['option_c']) ?>">
                </div>
                <div class="col">
                    <label>Option D</label>
                    <input type="text" name="option_d" class="form-control" required value="<?= htmlspecialchars($question['option_d']) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label>Correct Option</label>
                <select name="correct_option" class="form-select" required>
                    <option value="">Select Correct Option</option>
                    <?php foreach (['A','B','C','D'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $question['correct_option'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-success">Update Question</button>
                <a href="view_questions.php?quiz_id=<?= $quiz_id ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
