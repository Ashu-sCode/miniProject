<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "smartquiz_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$base_url = "/php-projects/Projects/MiniProjectBCA/SmartQuiz/";

?>
<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "smartquiz_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$base_url = "/php-projects/Projects/MiniProjectBCA/SmartQuiz/";

?>
