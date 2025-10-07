<?php
include '../includes/session_check.php';
include '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($_GET['quiz_id'])) {
    header("Location: quiz_list.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) die("Quiz not found.");

// Fetch questions
$stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$show_result = false;
$score = 0;
$total = count($questions);

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($questions as $q) {
        $q_id = $q['id'];
        $ans = $_POST['question'][$q_id] ?? '';
        if (strtoupper($ans) === strtoupper($q['correct_option'])) $score++;
    }

    // Save result
    $stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score, total_questions, correct_answers) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $quiz_id, $score, $total, $score]);

    $show_result = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($quiz['title']) ?> | Quiz</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f0f4f8; }
.quiz-container { max-width: 900px; margin: 50px auto; }
.timer { background: #5563DE; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: bold; font-size: 1.2rem; display: inline-block; margin-bottom: 20px; }
.card { border-radius: 15px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); margin-bottom: 20px; transition: transform 0.2s; }
.card:hover { transform: translateY(-3px); }
.card-body { padding: 20px; }
.option-label { cursor: pointer; display: block; padding: 10px 15px; border-radius: 10px; transition: background 0.2s; margin-bottom: 10px; }
.option-label:hover { background: rgba(85,99,222,0.1); }
.form-check-input:checked + .option-label { background: rgba(85,99,222,0.3); }
.result-popup {
    position: fixed; top:50%; left:50%;
    transform: translate(-50%, -50%);
    z-index:1050; width: 400px;
    background: #fff; border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    padding: 30px; text-align:center; display:none;
}
.overlay {
    position: fixed; top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.5); display:none; z-index:1040;
}
</style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="quiz-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= htmlspecialchars($quiz['title']) ?></h2>
        <div class="timer" id="timer"></div>
    </div>
    <p class="text-muted mb-4"><?= htmlspecialchars($quiz['description'] ?? 'Challenge yourself!') ?></p>

    <form method="POST" id="quizForm">
        <?php foreach ($questions as $index => $q): ?>
        <div class="card">
            <div class="card-body">
                <h5>Q<?= $index + 1 ?>: <?= htmlspecialchars($q['question_text']) ?></h5>
                <?php
                $options = ['A'=>$q['option_a'],'B'=>$q['option_b'],'C'=>$q['option_c'],'D'=>$q['option_d']];
                foreach ($options as $key=>$text):
                    if (!$text) continue;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="question[<?= $q['id'] ?>]" id="q<?= $q['id'] ?>_<?= $key ?>" value="<?= $key ?>" required>
                    <label class="option-label" for="q<?= $q['id'] ?>_<?= $key ?>"><?= htmlspecialchars($text) ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg px-5">Submit Quiz</button>
        </div>
    </form>
</div>

<!-- Result Popup -->
<div class="overlay" id="overlay"></div>
<div class="result-popup" id="resultPopup">
    <h3>Quiz Completed!</h3>
    <p><strong>Score:</strong> <?= $score ?> / <?= $total ?></p>
    <p><strong>Correct Answers:</strong> <?= $score ?></p>
    <p><strong>Total Questions:</strong> <?= $total ?></p>
    <button class="btn btn-primary mt-2" id="closePopup">Close</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let totalTime = <?= intval($quiz['time_limit'] ?? 5) ?> * 60; // in seconds
const timerEl = document.getElementById('timer');
const form = document.getElementById('quizForm');

function updateTimer() {
    const minutes = Math.floor(totalTime/60);
    const seconds = totalTime % 60;
    timerEl.textContent = `⏱ ${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
    if(totalTime <= 0){
        clearInterval(interval);
        alert('Time is up! Auto-submitting your quiz...');
        form.submit();
    }
    totalTime--;
}
updateTimer();
const interval = setInterval(updateTimer, 1000);

<?php if ($show_result): ?>
document.getElementById('overlay').style.display = 'block';
document.getElementById('resultPopup').style.display = 'block';
document.getElementById('closePopup').onclick = function(){
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('resultPopup').style.display = 'none';
    window.location.href = 'quiz_list.php';
};
<?php endif; ?>
</script>
</body>
</html>
