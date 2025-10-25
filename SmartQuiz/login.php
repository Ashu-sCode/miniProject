<?php
session_start();
require_once "includes/config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") 
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "⚠️ Please enter both email and password.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Login success → store session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: user/dashboard.php");
                    exit;
                }
            } else {
                $message = "❌ Invalid email or password.";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SmartQuiz | Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #5563DE, #74ABE2);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .login-box {
      background: white;
      padding: 2.5rem;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
    }
    h2 {
      color: #333;
      margin-bottom: 1rem;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.8rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      transition: all 0.3s ease;
    }
    input:focus {
      border-color: #5563DE;
      box-shadow: 0 0 5px rgba(85,99,222,0.4);
    }
    button {
      background-color: #5563DE;
      color: white;
      border: none;
      padding: 0.8rem 1.5rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 1rem;
      width: 100%;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #3742fa;
    }
    .msg {
      margin-bottom: 1rem;
      color: #333;
      font-weight: 500;
    }
    .link {
      display: block;
      margin-top: 1rem;
      color: #5563DE;
      text-decoration: none;
    }
    .link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Welcome Back 👋</h2>
    <p style="color:gray;">Login to your SmartQuiz account</p>

    <?php if ($message): ?>
      <div class="msg"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <a class="link" href="register.php">Don’t have an account? Register</a>
  </div>

</body>
</html>
