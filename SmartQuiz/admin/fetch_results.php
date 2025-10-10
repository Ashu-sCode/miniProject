<?php
include '../includes/session_check.php';
include '../includes/config.php';

if ($_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    exit("Access denied");
}

$search = $_GET['search'] ?? '';
$quiz_id = $_GET['quiz_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$min_score = $_GET['min_score'] ?? '';
$max_score = $_GET['max_score'] ?? '';

try {
    $sql = "
        SELECT r.id, r.score, r.correct_answers, r.total_questions, 
               r.attempted_at,
               u.name AS user_name, q.title AS quiz_title
        FROM results r
        INNER JOIN users u ON r.user_id = u.id
        INNER JOIN quizzes q ON r.quiz_id = q.id
        WHERE 1
    ";

    $params = [];

    if ($search) {
        $sql .= " AND (u.name LIKE :search OR q.title LIKE :search OR r.attempted_at LIKE :search)";
        $params['search'] = "%$search%";
    }
    if ($quiz_id) {
        $sql .= " AND r.quiz_id = :quiz_id";
        $params['quiz_id'] = $quiz_id;
    }
    if ($start_date) {
        $sql .= " AND r.attempted_at >= :start_date";
        $params['start_date'] = $start_date . ' 00:00:00';
    }
    if ($end_date) {
        $sql .= " AND r.attempted_at <= :end_date";
        $params['end_date'] = $end_date . ' 23:59:59';
    }
    if ($min_score !== '') {
        $sql .= " AND r.score >= :min_score";
        $params['min_score'] = $min_score;
    }
    if ($max_score !== '') {
        $sql .= " AND r.score <= :max_score";
        $params['max_score'] = $max_score;
    }

    $sql .= " ORDER BY r.attempted_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo '<p class="text-center text-muted mt-3">No quiz results found.</p>';
        exit;
    }

    echo '<div class="table-responsive">';
    echo '<table class="table table-hover align-middle" style="border-radius:12px; overflow:hidden;">';
    echo '<thead style="background:#111827; color:#fff;">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Quiz</th>
                <th>Score</th>
                <th>Correct Answers</th>
                <th>Total Questions</th>
                <th>Date Taken</th>
            </tr>
          </thead><tbody>';
    foreach ($results as $index => $r) {
        echo '<tr style="transition: all 0.2s;">
                <td>'.($index+1).'</td>
                <td>'.htmlspecialchars($r['user_name']).'</td>
                <td>'.htmlspecialchars($r['quiz_title']).'</td>
                <td>'.intval($r['score']).'</td>
                <td>'.intval($r['correct_answers']).'</td>
                <td>'.intval($r['total_questions']).'</td>
                <td>'.date('d M Y, H:i', strtotime($r['attempted_at'])).'</td>
              </tr>';
    }
    echo '</tbody></table></div>';

} catch (PDOException $e) {
    echo '<p class="text-danger">Database error: '.htmlspecialchars($e->getMessage()).'</p>';
}
?>
<style>
    .table-responsive {
        margin-top: 20px;
    }
    table tbody tr:hover {
        background-color: #f1f5f9;
    }
    table th, table td {
        vertical-align: middle;
        padding: 12px 15px;
    }
</style>
