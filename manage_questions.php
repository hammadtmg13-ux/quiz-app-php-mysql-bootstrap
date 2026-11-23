<?php
require 'config.php';

$filter = (int)($_GET['category_id'] ?? 0);
$categories = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll();

$sql = 'SELECT q.*, c.name AS category_name, c.color AS category_color FROM questions q JOIN categories c ON c.id = q.category_id';
$params = [];
if ($filter > 0) {
    $sql .= ' WHERE q.category_id = ?';
    $params[] = $filter;
}
$sql .= ' ORDER BY q.category_id, q.id';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - QuizMaster</title>
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
                <small class="text-muted">Question Bank Manager</small>
            </div>
        </div>
        <div class="user-chip">
            <span class="avatar avatar-sm">HK</span>
            <span>Hammad Khan</span>
        </div>
    </header>

    <div class="mb-4">
        <a href="index.php" class="btn btn-outline-secondary btn-sm">&larr; Back to dashboard</a>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">Add a new question</h2>
        <form action="add_question.php" method="post">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Difficulty</label>
                    <select name="difficulty" class="form-select">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Question</label>
                    <input type="text" name="question_text" class="form-control" placeholder="Type the question here" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option A</label>
                    <input type="text" name="option_a" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option B</label>
                    <input type="text" name="option_b" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option C</label>
                    <input type="text" name="option_c" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Option D</label>
                    <input type="text" name="option_d" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correct option</label>
                    <select name="correct_option" class="form-select">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add question</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h2 class="h5 fw-bold mb-0">Question bank (<?= count($questions) ?>)</h2>
            <form method="get" class="d-flex gap-2">
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="0">All categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $filter === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Correct</th>
                        <th>Difficulty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $q):
                        $correctKey = 'option_' . $q['correct_option'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>
                            <td><span class="badge" style="background: <?= htmlspecialchars($q['category_color']) ?>"><?= htmlspecialchars($q['category_name']) ?></span></td>
                            <td><?= htmlspecialchars($q[$correctKey]) ?></td>
                            <td><span class="badge bg-light text-dark text-capitalize"><?= htmlspecialchars($q['difficulty']) ?></span></td>
                            <td class="text-nowrap">
                                <a href="edit_question.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="delete_question.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this question?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>
