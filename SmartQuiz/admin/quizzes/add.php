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
            $_POST = []; // clear form
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
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
  }

  .container {
    max-width: 700px;
    margin-top: 50px;
  }

  .card {
    border: 1px solid #eaeaea;
    border-radius: 12px;
    background-color: #fff;
    padding: 35px 30px;
  }

  h2 {
    font-weight: 600;
    font-size: 1.6rem;
    text-align: center;
    margin-bottom: 25px;
  }

  label {
    font-weight: 500;
    margin-bottom: 6px;
  }

  input, textarea {
    border-radius: 6px !important;
    border: 1px solid #ccc !important;
    box-shadow: none !important;
    transition: border 0.2s ease;
  }

  input:focus, textarea:focus {
    border-color: #0d6efd !important;
    outline: none;
  }

  .btn-primary, .btn-success {
    border-radius: 6px;
    font-weight: 500;
    transition: background 0.2s ease, transform 0.2s ease;
  }

  .btn-primary:hover, .btn-success:hover {
    transform: translateY(-1px);
  }

  .btn-secondary {
    border-radius: 6px;
    font-weight: 500;
  }

  .alert {
    border-radius: 6px;
    padding: 10px 15px;
    margin-bottom: 20px;
    font-size: 0.95rem;
  }

  .form-footer {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 25px;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .container {
      margin-top: 30px;
      padding: 0 10px;
    }
    .card {
      padding: 25px 20px;
    }
  }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
  <div class="card">
    <h2>Add New Quiz</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?>
          <div>• <?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
        <input 
          type="text" 
          class="form-control" 
          id="title" 
          name="title" 
          value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" 
          placeholder="Enter quiz title"
          required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Quiz Description</label>
        <textarea 
          class="form-control" 
          id="description" 
          name="description" 
          rows="4"
          placeholder="Write a short description (optional)"
        ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label for="time_limit" class="form-label">Time Limit (minutes) <span class="text-danger">*</span></label>
        <input 
          type="number" 
          class="form-control" 
          id="time_limit" 
          name="time_limit" 
          value="<?= htmlspecialchars($_POST['time_limit'] ?? 5) ?>" 
          min="1" 
          required>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-success px-4">Add Quiz</button>
        <a href="manage.php" class="btn btn-secondary px-4">Back</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
