<?php
include '../../includes/session_check.php';
include '../../includes/config.php';

// Only admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);
$question_id = intval($_GET['question_id'] ?? 0);

if (!$quiz_id || !$question_id) {
    header("Location: view_questions.php?quiz_id=$quiz_id");
    exit();
}

// Delete the question
$stmt = $conn->prepare("DELETE FROM questions WHERE id = ? AND quiz_id = ?");
$stmt->execute([$question_id, $quiz_id]);

// Redirect back to questions list
header("Location: view_questions.php?quiz_id=$quiz_id");
exit();
