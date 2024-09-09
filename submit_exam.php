<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

$exam_id = $_POST['exam_id'];

$stmt = $conn->prepare("INSERT INTO answers (user_id, question_id, answer) VALUES (?, ?, ?)");

for ($i = 1; $i <= count($_POST) - 1; $i++) {
    if (isset($_POST['question_id' . $i]) && isset($_POST['answer' . $i])) {
        $question_id = $_POST['question_id' . $i];
        $answer = $_POST['answer' . $i];
        $stmt->bind_param("iis", $_SESSION['user_id'], $question_id, $answer);
        $stmt->execute();
    }
}

$stmt->close();

header("Location: index.php");
exit;
?>


