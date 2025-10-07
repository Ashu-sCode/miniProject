<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if (!$quiz_id) {
    header("Location: manage.php");
    exit();
}

// Fetch quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) die("Quiz not found.");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questions = $_POST['question_text'] ?? [];
    $option_a = $_POST['option_a'] ?? [];
    $option_b = $_POST['option_b'] ?? [];
    $option_c = $_POST['option_c'] ?? [];
    $option_d = $_POST['option_d'] ?? [];
    $correct = $_POST['correct_option'] ?? [];

    $added = 0;
    foreach ($questions as $i => $q_text) {
        if (trim($q_text) === '') continue; // skip empty

        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $quiz_id,
            trim($q_text),
            trim($option_a[$i]),
            trim($option_b[$i]),
            trim($option_c[$i]),
            trim($option_d[$i]),
            strtoupper($correct[$i])
        ]);
        $added++;
    }

    if ($added > 0) {
        header("Location: view_questions.php?quiz_id=$quiz_id");
        exit();
    } else {
        $error = "No valid questions were added.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Questions | <?= htmlspecialchars($quiz['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
.container { max-width: 900px; margin: 50px auto; }
.card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
.remove-btn { margin-top: 30px; }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="mb-4">Add Questions to Quiz: <?= htmlspecialchars($quiz['title']) ?></h2>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

        <form method="POST" id="questionsForm">
            <div id="questionsContainer">
                <!-- Question block template -->
                <div class="question-block mb-4 p-3 border rounded">
                    <div class="mb-3">
                        <label>Question Text</label>
                        <input type="text" name="question_text[]" class="form-control" required>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label>Option A</label>
                            <input type="text" name="option_a[]" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Option B</label>
                            <input type="text" name="option_b[]" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label>Option C</label>
                            <input type="text" name="option_c[]" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Option D</label>
                            <input type="text" name="option_d[]" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Correct Option</label>
                        <select name="correct_option[]" class="form-select" required>
                            <option value="">Select Correct Option</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger remove-btn">Remove Question</button>
                </div>
            </div>
            <div class="mb-3">
                <button type="button" id="addQuestionBtn" class="btn btn-secondary">+ Add Another Question</button>
            </div>
            <div>
                <button type="submit" class="btn btn-success btn-lg">Save Questions</button>
                <a href="view_questions.php?quiz_id=<?= $quiz_id ?>" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

<script>
// Dynamic question blocks
const container = document.getElementById('questionsContainer');
const addBtn = document.getElementById('addQuestionBtn');

addBtn.addEventListener('click', () => {
    const block = container.children[0].cloneNode(true);
    // Clear values
    block.querySelectorAll('input').forEach(input => input.value = '');
    block.querySelector('select').value = '';
    container.appendChild(block);
});

// Remove question block
container.addEventListener('click', e => {
    if (e.target.classList.contains('remove-btn')) {
        if (container.children.length > 1) {
            e.target.closest('.question-block').remove();
        } else {
            alert("At least one question is required.");
        }
    }
});
</script>

</body>
</html>
