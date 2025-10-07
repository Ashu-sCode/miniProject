<?php
require_once "includes/config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "⚠️ All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $message = "❌ Passwords do not match!";
    } else {
        try {
            // Check if email exists
            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->rowCount() > 0) {
                $message = "⚠️ Email already registered!";
            } else {
                // Hash password & insert
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->execute([$name, $email, $hashed]);

                $message = "✅ Registration successful! <a href='login.php'>Login here</a>";
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
  <title>SmartQuiz | Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #74ABE2, #5563DE);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .register-box {
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
    input[type="text"],
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

  <div class="register-box">
    <h2>SmartQuiz Registration</h2>
    <?php if ($message): ?>
      <div class="msg"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Register</button>
    </form>

    <a class="link" href="login.php">Already have an account? Login</a>
  </div>

</body>
</html>
