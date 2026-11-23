<?php
require 'config.php';

$categoryId = (int)($_GET['category_id'] ?? 0);
$catStmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
$catStmt->execute([$categoryId]);
$category = $catStmt->fetch();
if (!$category) {
    header('Location: index.php');
    exit;
}

$qStmt = $pdo->prepare('SELECT id, question_text, option_a, option_b, option_c, option_d, correct_option FROM questions WHERE category_id = ? ORDER BY RAND() LIMIT 5');
$qStmt->execute([$categoryId]);
$questions = $qStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> Quiz - QuizMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container py-4" style="max-width: 760px;">
    <header class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar">HK</div>
            <div>
                <h1 class="h4 mb-0">QuizMaster</h1>
                <small class="text-muted"><?= htmlspecialchars($category['name']) ?> Quiz</small>
            </div>
        </div>
        <div class="user-chip">
            <span class="avatar avatar-sm">HK</span>
            <span>Hammad Khan</span>
        </div>
    </header>

    <div id="quizBox" class="card shadow-sm p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="badge" style="background: <?= htmlspecialchars($category['color']) ?>"><?= htmlspecialchars($category['name']) ?></span>
            <div class="timer-circle" id="timerCircle"><span id="timerText">30</span></div>
        </div>
        <div class="progress mb-4">
            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%; background: <?= htmlspecialchars($category['color']) ?>"></div>
        </div>
        <p class="text-muted small mb-1" id="questionCounter">Question 1 of <?= count($questions) ?></p>
        <h2 class="h5 fw-bold mb-4" id="questionText"></h2>
        <div class="d-grid gap-2" id="optionsBox"></div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="index.php" class="btn btn-outline-secondary btn-sm">Quit quiz</a>
            <button type="button" class="btn btn-primary" id="nextBtn" disabled>Next question</button>
        </div>
    </div>

    <div id="resultBox" class="card shadow-sm p-4 text-center d-none">
        <div class="score-ring mb-3">
            <div class="score-value" id="finalScore">0/5</div>
            <small class="text-muted">your score</small>
        </div>
        <h2 class="h5 fw-bold" id="resultMessage"></h2>
        <p class="text-muted" id="resultDetail"></p>
        <form action="save_result.php" method="post" class="mt-3">
            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
            <input type="hidden" name="score" id="scoreInput" value="0">
            <input type="hidden" name="total" id="totalInput" value="<?= count($questions) ?>">
            <div class="row justify-content-center g-2">
                <div class="col-auto">
                    <input type="text" name="player_name" class="form-control" value="Hammad Khan" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Save my result</button>
                </div>
                <div class="col-auto">
                    <a href="index.php" class="btn btn-outline-secondary">Back to dashboard</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const QUESTIONS = <?= json_encode($questions) ?>;
</script>
<script src="assets/js/script.js"></script>
</body>
</html>
