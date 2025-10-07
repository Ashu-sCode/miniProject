<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only allow admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all quizzes
try {
    $stmt = $conn->prepare("SELECT * FROM quizzes ORDER BY created_at DESC");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Quizzes | Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
    .card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
    .action-btns a { margin-right: 5px; }
    .btn-add { float: right; }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4">Quizzes
            <a href="add.php" class="btn btn-success btn-add">+ Add New Quiz</a>
        </h2>

        <?php if (empty($quizzes)): ?>
            <p class="text-center text-muted">No quizzes available yet.</p>
        <?php else: ?>
            <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Time Limit (min)</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($quizzes as $index => $quiz): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($quiz['title']) ?></td>
                        <td><?= htmlspecialchars($quiz['description'] ?? '-') ?></td>
                        <td><?= intval($quiz['time_limit'] ?? 5) ?></td>
                        <td><?= $quiz['created_at'] ?></td>
                        <td class="action-btns">
                            <a href="edit.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="view_questions.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-info">Questions</a>
                            <a href="delete.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
