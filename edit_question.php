<?php
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM questions WHERE id = ?');
$stmt->execute([$id]);
$question = $stmt->fetch();
if (!$question) {
    header('Location: manage_questions.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $questionText = trim($_POST['question_text'] ?? '');
    $optionA = trim($_POST['option_a'] ?? '');
    $optionB = trim($_POST['option_b'] ?? '');
    $optionC = trim($_POST['option_c'] ?? '');
    $optionD = trim($_POST['option_d'] ?? '');
    $correct = $_POST['correct_option'] ?? 'a';
    $difficulty = $_POST['difficulty'] ?? 'easy';

    if ($categoryId > 0 && $questionText !== '' && $optionA !== '' && $optionB !== '' && $optionC !== '' && $optionD !== '' && in_array($correct, ['a', 'b', 'c', 'd'], true) && in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
        $update = $pdo->prepare('UPDATE questions SET category_id = ?, question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?, difficulty = ? WHERE id = ?');
        $update->execute([$categoryId, $questionText, $optionA, $optionB, $optionC, $optionD, $correct, $difficulty, $id]);
    }
    header('Location: manage_questions.php');
    exit;
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question - QuizMaster</title>
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
                <small class="text-muted">Edit Question</small>
            </div>
        </div>
        <div class="user-chip">
            <span class="avatar avatar-sm">HK</span>
            <span>Hammad Khan</span>
        </div>
    </header>

    <div class="card shadow-sm p-4">
        <form method="post">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (int)$cat['id'] === (int)$question['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Difficulty</label>
                    <select name="difficulty" class="form-select">
                        <?php foreach (['easy', 'medium', 'hard'] as $d): ?>
                            <option value="<?= $d ?>" <?= $question['difficulty'] === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Question</label>
                    <input type="text" name="question_text" class="form-control" value="<?= htmlspecialchars($question['question_text']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option A</label>
                    <input type="text" name="option_a" class="form-control" value="<?= htmlspecialchars($question['option_a']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option B</label>
                    <input type="text" name="option_b" class="form-control" value="<?= htmlspecialchars($question['option_b']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option C</label>
                    <input type="text" name="option_c" class="form-control" value="<?= htmlspecialchars($question['option_c']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option D</label>
                    <input type="text" name="option_d" class="form-control" value="<?= htmlspecialchars($question['option_d']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correct option</label>
                    <select name="correct_option" class="form-select">
                        <?php foreach (['a', 'b', 'c', 'd'] as $letter): ?>
                            <option value="<?= $letter ?>" <?= $question['correct_option'] === $letter ? 'selected' : '' ?>><?= strtoupper($letter) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="manage_questions.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
