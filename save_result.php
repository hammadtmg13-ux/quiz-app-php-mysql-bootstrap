<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$playerName = trim($_POST['player_name'] ?? 'Hammad Khan');
$categoryId = (int)($_POST['category_id'] ?? 0);
$score = (int)($_POST['score'] ?? 0);
$total = (int)($_POST['total'] ?? 0);

if ($playerName === '' || $categoryId <= 0 || $total <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO results (player_name, category_id, score, total_questions) VALUES (?, ?, ?, ?)');
$stmt->execute([$playerName, $categoryId, $score, $total]);

header('Location: index.php?saved=1');
exit;
