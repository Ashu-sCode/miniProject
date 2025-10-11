<?php
include '../includes/session_check.php';
include '../includes/config.php';

// Only admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch quizzes for filter dropdown
$stmt = $conn->prepare("SELECT id, title FROM quizzes ORDER BY title ASC");
$stmt->execute();
$allQuizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quiz Results | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .table thead {
            background: #5563DE;
            color: #fff;
        }

        .table tbody tr:hover {
            background: rgba(85, 99, 222, 0.1);
        }

        .filters .form-control,
        .filters .form-select,
        .filters .btn {
            margin-bottom: 10px;
        }

        .filters .input-group-text {
            background-color: #5563DE;
            color: #fff;
            border: none;
        }

        .filters .btn-reset {
            background-color: #74ABE2;
            color: #fff;
        }

        @media (max-width: 768px) {

            .filters .col-md-2,
            .filters .col-md-3,
            .filters .col-md-1 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="card">
            <h2 class="mb-4">All Quiz Results</h2>

            <!-- Filters -->
            <div class="card mb-4 p-3 shadow-sm">
                <h5 class="mb-3">Filter Results</h5>

                <!-- Search -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search user, quiz, or date">
                    </div>
                </div>

                <div class="row g-3 align-items-center">
                    <!-- Quiz filter -->
                    <div class="col-md-4">
                        <label for="quizFilter" class="form-label">Quiz</label>
                        <select id="quizFilter" class="form-select">
                            <option value="">All Quizzes</option>
                            <?php foreach ($allQuizzes as $quiz): ?>
                                <option value="<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date range -->
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" id="startDate" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" id="endDate" class="form-control">
                        </div>
                    </div>

                    <!-- Score range -->
                    <div class="col-md-1">
                        <label for="minScore" class="form-label">Min</label>
                        <input type="number" id="minScore" class="form-control" min="0" placeholder="0">
                    </div>
                    <div class="col-md-1">
                        <label for="maxScore" class="form-label">Max</label>
                        <input type="number" id="maxScore" class="form-control" min="0" placeholder="100">
                    </div>
                </div>

                <!-- Reset button -->
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-outline-secondary" id="resetFilters"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                </div>
            </div>


            <!-- Table -->
            <div id="resultsTable">
                <!-- AJAX-loaded results will appear here -->
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const quizFilter = document.getElementById('quizFilter');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const minScore = document.getElementById('minScore');
        const maxScore = document.getElementById('maxScore');
        const resetBtn = document.getElementById('resetFilters');
        const resultsTable = document.getElementById('resultsTable');

        let debounceTimer;

        async function fetchResults() {
            const params = new URLSearchParams({
                search: searchInput.value.trim(),
                quiz_id: quizFilter.value,
                start_date: startDate.value,
                end_date: endDate.value,
                min_score: minScore.value,
                max_score: maxScore.value
            });

            try {
                const res = await fetch('fetch_results.php?' + params.toString());
                const html = await res.text();
                resultsTable.innerHTML = html;
            } catch (err) {
                resultsTable.innerHTML = '<p class="text-danger">Error fetching results.</p>';
            }
        }

        // Initial load
        fetchResults();

        // Debounced search
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchResults, 500);
        });

        // Filter change events
        [quizFilter, startDate, endDate, minScore, maxScore].forEach(el => {
            el.addEventListener('change', fetchResults);
        });

        // Reset filters
        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            quizFilter.value = '';
            startDate.value = '';
            endDate.value = '';
            minScore.value = '';
            maxScore.value = '';
            fetchResults();
        });
    </script>
</body>

</html>
