<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only allow admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
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
            $stmt = $conn->prepare("INSERT INTO quizzes (title, description, time_limit, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$title, $description, $time_limit]);
            $success = "Quiz added successfully!";
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
<title>Add Quiz | Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
    .form-container { max-width: 700px; margin: 50px auto; }
    .card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); padding: 30px; }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="form-container">
    <div class="card">
        <h2 class="mb-4 text-center">Add New Quiz</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Quiz Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="time_limit" class="form-label">Time Limit (minutes) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="time_limit" name="time_limit" value="<?= htmlspecialchars($_POST['time_limit'] ?? 5) ?>" min="1" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5">Add Quiz</button>
                <a href="manage.php" class="btn btn-secondary px-4">Back</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
