<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);

if (!$quiz_id) {
    header("Location: manage.php");
    exit();
}

// Delete all questions for this quiz
$stmt = $conn->prepare("DELETE FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);

// Delete the quiz itself
$stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);

// Redirect back to manage quizzes page
header("Location: manage.php");
exit();
