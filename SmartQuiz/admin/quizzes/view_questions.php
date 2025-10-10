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
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8fafc;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 950px;
        margin: 60px auto;
    }

    .card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
    }

    h2 {
        font-weight: 600;
        color: #333;
    }

    .btn-add {
        float: right;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    table {
        border-radius: 10px;
        overflow: hidden;
    }

    thead {
        background-color: #111827;
        color: #fff;
    }

    tbody tr {
        transition: all 0.2s ease-in-out;
    }

    tbody tr:hover {
        background-color: #f1f5f9;
    }

    .action-btns .btn {
        margin-right: 5px;
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 4px 10px;
    }

    .btn-secondary {
        border-radius: 8px;
    }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Questions — <?= htmlspecialchars($quiz['title']) ?></h2>
            <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="btn btn-success btn-add">+ Add Question</a>
        </div>

        <?php if (empty($questions)): ?>
            <p class="text-center text-muted my-5">No questions have been added yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
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
                                <div>A: <?= htmlspecialchars($q['option_a']) ?></div>
                                <div>B: <?= htmlspecialchars($q['option_b']) ?></div>
                                <div>C: <?= htmlspecialchars($q['option_c']) ?></div>
                                <div>D: <?= htmlspecialchars($q['option_d']) ?></div>
                            </td>
                            <td><strong><?= strtoupper($q['correct_option']) ?></strong></td>
                            <td class="action-btns">
                                <a href="edit_question.php?quiz_id=<?= $quiz_id ?>&question_id=<?= $q['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_question.php?quiz_id=<?= $quiz_id ?>&question_id=<?= $q['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="manage.php" class="btn btn-secondary px-4">Back to Quizzes</a>
        </div>
    </div>
</div>

</body>
</html>
