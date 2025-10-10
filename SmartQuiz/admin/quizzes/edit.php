<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only allow admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if (!$quiz_id) {
    header("Location: manage.php");
    exit();
}

// Fetch existing quiz
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $time_limit = intval($_POST['time_limit'] ?? 5);

    if (!$title) $errors[] = "Quiz title is required.";
    if ($time_limit <= 0) $errors[] = "Time limit must be greater than 0.";

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ?, time_limit = ? WHERE id = ?");
            $stmt->execute([$title, $description, $time_limit, $quiz_id]);
            $success = "Quiz updated successfully!";
            // Refresh quiz data
            $stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
            $stmt->execute([$quiz_id]);
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Quiz | Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8fafc;
        margin: 0;
        padding: 0;
    }

    .form-container {
        max-width: 700px;
        margin: 60px auto;
    }

    .card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 35px;
    }

    h2 {
        text-align: center;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 25px;
    }

    label {
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.15rem rgba(59,130,246,0.25);
    }

    .alert {
        border-radius: 10px;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-primary {
        background-color: #2563eb;
        border: none;
        border-radius: 8px;
        padding: 10px 30px;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background-color: #1e40af;
        transform: translateY(-1px);
    }

    .btn-secondary {
        border-radius: 8px;
        padding: 10px 25px;
        background-color: #6b7280;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="form-container">
    <div class="card">
        <h2>Edit Quiz</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err) echo "<p class='mb-0'>$err</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title"
                       value="<?= htmlspecialchars($quiz['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Quiz Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($quiz['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="time_limit" class="form-label">Time Limit (minutes) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="time_limit" name="time_limit"
                       value="<?= intval($quiz['time_limit'] ?? 5) ?>" min="1" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary me-2">Update Quiz</button>
                <a href="manage.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
