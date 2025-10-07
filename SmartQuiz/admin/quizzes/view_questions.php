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

// Fetch questions
$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Questions | <?= htmlspecialchars($quiz['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
.container { max-width: 900px; margin: 50px auto; }
.card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
.action-btns a { margin-right: 5px; }
.btn-add { float: right; }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
    <div class="card p-4">
        <h2 class="mb-4">Questions for Quiz: <?= htmlspecialchars($quiz['title']) ?>
            <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="btn btn-success btn-add">+ Add New Question</a>
        </h2>

        <?php if (empty($questions)): ?>
            <p class="text-center text-muted">No questions added yet.</p>
        <?php else: ?>
            <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Correct</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($questions as $index => $q): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($q['question_text']) ?></td>
                        <td>
                            A: <?= htmlspecialchars($q['option_a']) ?><br>
                            B: <?= htmlspecialchars($q['option_b']) ?><br>
                            C: <?= htmlspecialchars($q['option_c']) ?><br>
                            D: <?= htmlspecialchars($q['option_d']) ?>
                        </td>
                        <td><?= strtoupper($q['correct_option']) ?></td>
                        <td class="action-btns">
                            <a href="edit_question.php?quiz_id=<?= $quiz_id ?>&question_id=<?= $q['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete_question.php?quiz_id=<?= $quiz_id ?>&question_id=<?= $q['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
        <div class="mt-3">
            <a href="manage.php" class="btn btn-secondary">Back to Quizzes</a>
        </div>
    </div>
</div>

</body>
</html>
