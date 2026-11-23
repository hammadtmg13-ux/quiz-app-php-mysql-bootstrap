<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_questions.php');
    exit;
}

$categoryId = (int)($_POST['category_id'] ?? 0);
$questionText = trim($_POST['question_text'] ?? '');
$optionA = trim($_POST['option_a'] ?? '');
$optionB = trim($_POST['option_b'] ?? '');
$optionC = trim($_POST['option_c'] ?? '');
$optionD = trim($_POST['option_d'] ?? '');
$correct = $_POST['correct_option'] ?? 'a';
$difficulty = $_POST['difficulty'] ?? 'easy';

if ($categoryId <= 0 || $questionText === '' || $optionA === '' || $optionB === '' || $optionC === '' || $optionD === '' || !in_array($correct, ['a', 'b', 'c', 'd'], true) || !in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
    header('Location: manage_questions.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$categoryId, $questionText, $optionA, $optionB, $optionC, $optionD, $correct, $difficulty]);

header('Location: manage_questions.php');
exit;
