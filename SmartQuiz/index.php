<?php include('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SmartQuiz | Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #5563DE, #74ABE2);
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      overflow-x: hidden;
    }

    h1 {
      font-weight: 800;
      font-size: 3rem;
      margin-bottom: 20px;
      animation: fadeInDown 1s ease forwards;
    }

    p {
      font-size: 1.2rem;
      margin-bottom: 40px;
      color: rgba(255, 255, 255, 0.9);
      animation: fadeInUp 1s ease forwards;
    }

    .btn-home {
      margin: 10px;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 50px;
      transition: transform 0.2s, box-shadow 0.2s;
      font-size: 1rem;
      cursor: pointer;
    }

    .btn-login {
      background-color: #fff;
      color: #5563DE;
    }
    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    .btn-register {
      background-color: transparent;
      border: 2px solid #fff;
      color: #fff;
    }
    .btn-register:hover {
      background-color: #fff;
      color: #5563DE;
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    @keyframes fadeInDown {
      0% { opacity: 0; transform: translateY(-20px);}
      100% { opacity: 1; transform: translateY(0);}
    }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px);}
      100% { opacity: 1; transform: translateY(0);}
    }

    /* Slide-in panel */
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      display: none;
      z-index: 1000;
    }

    .slide-panel {
      position: fixed;
      top: 0; right: -450px;
      width: 450px; height: 100%;
      background: #fff;
      color: #333;
      padding: 2rem;
      box-shadow: -5px 0 25px rgba(0,0,0,0.3);
      transition: right 0.5s ease;
      overflow-y: auto;
      z-index: 1010;
      border-radius: 10px 0 0 10px;
    }

    .slide-panel.active { right: 0; }

    .slide-panel h2 { margin-bottom: 1rem; }
    .slide-panel input {
      width: 100%;
      padding: 0.7rem;
      margin: 0.5rem 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      outline: none;
    }
    .slide-panel button {
      width: 100%;
      padding: 0.8rem;
      margin-top: 1rem;
      border: none;
      border-radius: 50px;
      background: #5563DE;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
    }
    .slide-panel button:hover { background: #3742fa; }

    .slide-panel .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 1.5rem;
      cursor: pointer;
      color: #333;
    }

    .slide-panel .switch-link {
      display: block;
      margin-top: 1rem;
      color: #5563DE;
      text-decoration: none;
      cursor: pointer;
    }
    .slide-panel .switch-link:hover { text-decoration: underline; }

    @media(max-width: 480px){
      .slide-panel { width: 90%; }
    }
  </style>
</head>
<body>

  <h1>Welcome to SmartQuiz <i class="bi bi-patch-question-fill"></i></h1>
  <p>Test your knowledge and challenge yourself with interactive quizzes!</p>
  <div>
    <button class="btn btn-home btn-login" onclick="openPanel('login')">Login</button>
    <button class="btn btn-home btn-register" onclick="openPanel('register')">Register</button>
  </div>

  <!-- Overlay -->
  <div class="overlay" id="overlay" onclick="closePanel()"></div>



  <script>
    function openPanel(type){
      document.getElementById('overlay').style.display = 'block';
      if(type==='login'){
        document.getElementById('loginPanel').classList.add('active');
      } else {
        document.getElementById('registerPanel').classList.add('active');
      }
    }

    function closePanel(){
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('loginPanel').classList.remove('active');
      document.getElementById('registerPanel').classList.remove('active');
    }

    function switchPanel(type){
      if(type==='login'){
        document.getElementById('registerPanel').classList.remove('active');
        document.getElementById('loginPanel').classList.add('active');
      } else {
        document.getElementById('loginPanel').classList.remove('active');
        document.getElementById('registerPanel').classList.add('active');
      }
    }
  </script>
</body>
</html>



  <!-- Login Panel -->
  <div class="slide-panel" id="loginPanel">
    <span class="close-btn" onclick="closePanel()">&times;</span>
    <h2>Login</h2>
    <form method="POST" action="login.php">
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <a class="switch-link" onclick="switchPanel('register')">Don't have an account? Register</a>
  </div>

  <!-- Register Panel -->
  <div class="slide-panel" id="registerPanel">
    <span class="close-btn" onclick="closePanel()">&times;</span>
    <h2>Register</h2>
    <form method="POST" action="register.php">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Register</button>
    </form>
    <a class="switch-link" onclick="switchPanel('login')">Already have an account? Login</a>
  </div>