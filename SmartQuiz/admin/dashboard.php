<?php
include '../includes/session_check.php';
include '../includes/config.php';

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Quiz System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f0f4f8, #d9e4ec);
      font-family: 'Poppins', sans-serif;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .dashboard {
      max-width: 1000px;
      margin: 80px auto;
      padding: 40px;
      text-align: center;
    }

    .dashboard h2 {
      font-weight: 600;
      color: #222;
    }

    .dashboard p {
      color: #6c757d;
      margin-top: 5px;
    }

    .card {
      border: none;
      border-radius: 18px;
      backdrop-filter: blur(15px);
      background: rgba(255, 255, 255, 0.6);
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      padding: 30px;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .card h4 {
      font-size: 1.3rem;
      font-weight: 500;
      margin-bottom: 10px;
    }

    .card p {
      font-size: 0.9rem;
      color: #555;
    }

    .btn-modern {
      border-radius: 25px;
      padding: 8px 20px;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn-modern:hover {
      transform: scale(1.05);
    }

    .emoji {
      font-size: 1.8rem;
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
      .dashboard {
        padding: 20px;
        margin-top: 60px;
      }
    }
  </style>
</head>
<body>
  <?php include '../includes/navbar.php'; ?>

  <div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
    <p class="mb-5">Manage your quizzes and monitor performance effortlessly.</p>

    <div class="row g-4 justify-content-center">
      <div class="col-md-5 col-sm-10">
        <div class="card">
          <div class="emoji mb-2">📝</div>
          <h4>Manage Quizzes</h4>
          <p>Create, edit, or delete quizzes with ease.</p>
          <a href="quizzes/manage.php" class="btn btn-primary btn-modern mt-2">Open</a>
        </div>
      </div>

      <div class="col-md-5 col-sm-10">
        <div class="card">
          <div class="emoji mb-2">📈</div>
          <h4>View Results</h4>
          <p>Analyze quiz results and user stats.</p>
          <a href="admin_results.php" class="btn btn-success btn-modern mt-2">Open</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
