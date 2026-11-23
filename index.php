<?php
require 'config.php';

// Dashboard stats, computed from the database
$totalQuizzes = (int)$pdo->query('SELECT COUNT(*) FROM results')->fetchColumn();
$avgScore = (int)$pdo->query('SELECT COALESCE(ROUND(AVG(score * 100.0 / total_questions)), 0) FROM results')->fetchColumn();
$bestScore = (int)$pdo->query('SELECT COALESCE(MAX(score * 100.0 / total_questions), 0) FROM results')->fetchColumn();
$totalQuestions = (int)$pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();

$categories = $pdo->query('SELECT c.*, COUNT(q.id) AS question_count FROM categories c LEFT JOIN questions q ON q.category_id = c.id GROUP BY c.id ORDER BY c.id')->fetchAll();
$recentResults = $pdo->query('SELECT r.*, c.name AS category_name, c.color AS category_color FROM results r JOIN categories c ON c.id = r.category_id ORDER BY r.taken_at DESC LIMIT 8')->fetchAll();
$leaders = $pdo->query('SELECT player_name, MAX(score * 100.0 / total_questions) AS best, COUNT(*) AS played FROM results GROUP BY player_name ORDER BY best DESC, played DESC LIMIT 5')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizMaster - Interactive Quiz App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container py-4">
    <header class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar">HK</div>
            <div>
                <h1 class="h4 mb-0">QuizMaster</h1>
                <small class="text-muted">Interactive Quiz App</small>
            </div>
        </div>
        <div class="user-chip">
            <span class="avatar avatar-sm">HK</span>
            <span>Hammad Khan</span>
        </div>
    </header>

    <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Your quiz result was saved. Well played!</div>
    <?php endif; ?>

    <div class="card hero-card p-4 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="h4 fw-bold mb-2">Test your web development skills</h2>
                <p class="mb-0 opacity-75">Pick a category, answer 5 timed questions and climb the leaderboard. Every result is saved in MySQL so your progress is always tracked.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="manage_questions.php" class="btn btn-light fw-semibold">Manage Question Bank</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card text-center p-3">
                <div class="stat-number"><?= $totalQuizzes ?></div>
                <div class="stat-label">Quizzes taken</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card text-center p-3">
                <div class="stat-number"><?= $avgScore ?>%</div>
                <div class="stat-label">Average score</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card text-center p-3">
                <div class="stat-number"><?= $bestScore ?>%</div>
                <div class="stat-label">Best score</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card text-center p-3">
                <div class="stat-number"><?= $totalQuestions ?></div>
                <div class="stat-label">Questions in bank</div>
            </div>
        </div>
    </div>

    <h2 class="h5 fw-bold mb-3">Choose a category</h2>
    <div class="row g-3 mb-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-4 col-lg">
                <div class="card category-card h-100">
                    <div class="category-top" style="background: <?= htmlspecialchars($cat['color']) ?>"></div>
                    <div class="card-body">
                        <div class="category-icon mb-3" style="background: <?= htmlspecialchars($cat['color']) ?>">
                            <?= htmlspecialchars(substr($cat['name'], 0, 2)) ?>
                        </div>
                        <h3 class="h6 fw-bold mb-1"><?= htmlspecialchars($cat['name']) ?></h3>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($cat['description']) ?></p>
                        <p class="small mb-3"><strong><?= $cat['question_count'] ?></strong> questions</p>
                        <a href="quiz.php?category_id=<?= $cat['id'] ?>" class="btn btn-primary btn-sm w-100">Start Quiz</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm p-4">
                <h2 class="h5 fw-bold mb-3">Recent results</h2>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Category</th>
                                <th>Score</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentResults as $r):
                                $percent = round($r['score'] * 100 / $r['total_questions']);
                                $badge = $percent >= 80 ? 'bg-success' : ($percent >= 60 ? 'bg-warning text-dark' : 'bg-danger');
                            ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($r['player_name']) ?></td>
                                    <td><span class="badge" style="background: <?= htmlspecialchars($r['category_color']) ?>"><?= htmlspecialchars($r['category_name']) ?></span></td>
                                    <td><?= $r['score'] ?> / <?= $r['total_questions'] ?></td>
                                    <td><span class="badge <?= $badge ?>"><?= $percent ?>%</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm p-4">
                <h2 class="h5 fw-bold mb-3">Leaderboard</h2>
                <?php foreach ($leaders as $i => $leader): ?>
                    <div class="leader-row">
                        <div class="rank-badge <?= $i === 0 ? 'first' : '' ?>"><?= $i + 1 ?></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= htmlspecialchars($leader['player_name']) ?></div>
                            <small class="text-muted"><?= $leader['played'] ?> <?= $leader['played'] == 1 ? 'quiz' : 'quizzes' ?> played</small>
                        </div>
                        <span class="badge bg-primary"><?= round($leader['best']) ?>%</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>
