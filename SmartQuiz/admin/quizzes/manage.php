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
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
  }

  .container {
    max-width: 1100px;
    margin-top: 40px;
  }

  .card {
    border: 1px solid #eaeaea;
    border-radius: 12px;
    background-color: #fff;
    padding: 25px;
  }

  h2 {
    font-weight: 600;
    font-size: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }

  .btn-add {
    background-color: #0d6efd;
    color: #fff;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 0.9rem;
    font-weight: 500;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-add:hover {
    background-color: #0b5ed7;
  }

  .table {
    border-collapse: collapse;
    width: 100%;
  }

  thead {
    background-color: #2b9c93ff;
  }

  thead th {
    font-weight: 600;
    font-style: Bold;
    color: #333;
    border: none;
    padding: 12px;
  }

  tbody tr {
    border-bottom: 1px solid #eee;
    transition: background 0.2s ease;
  }

  tbody tr:hover {
    background-color: #fafafa;
  }

  tbody td {
    padding: 12px;
    vertical-align: middle;
    font-size: 0.95rem;
  }

  .action-btns a {
    margin-right: 6px;
    font-size: 0.85rem;
    border-radius: 5px;
  }

  .btn-sm {
    padding: 4px 10px;
  }

  .no-quizzes {
    text-align: center;
    color: #888;
    font-style: italic;
    padding: 30px 0;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .container {
      margin-top: 20px;
    }

    h2 {
      flex-direction: column;
      gap: 10px;
      text-align: center;
    }

    .btn-add {
      width: 100%;
    }

    .table {
      font-size: 0.85rem;
    }
  }
</style>
</head>
<body>
<?php include '../../includes/navbar.php'; ?>

<div class="container">
  <div class="card">
    <h2>
      Manage Quizzes
      <a href="add.php" class="btn-add">+ Add New</a>
    </h2>

    <?php if (empty($quizzes)): ?>
      <p class="no-quizzes">No quizzes found. Add your first quiz to get started!</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Description</th>
              <th>Time Limit</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($quizzes as $index => $quiz): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($quiz['title']) ?></td>
                <td><?= htmlspecialchars($quiz['description'] ?? '-') ?></td>
                <td><?= intval($quiz['time_limit'] ?? 5) ?> min</td>
                <td><?= date('d M Y', strtotime($quiz['created_at'])) ?></td>
                <td class="action-btns">
                  <a href="edit.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                  <a href="view_questions.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-outline-secondary">Questions</a>
                  <a href="delete.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this quiz?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
