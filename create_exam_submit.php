<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

$exam_title = isset($_POST['examTitle']) ? $_POST['examTitle'] : '';
$question_count = isset($_POST['questionCount']) ? $_POST['questionCount'] : 0;

if (empty($exam_title) || $question_count == 0) {
    header("Location: create_exam.php?error=Please fill in all required fields");
    exit;
}

// Insert exam data into the exams table
$stmt = $conn->prepare("INSERT INTO exams (title, creator_id) VALUES (?, ?)");
$stmt->bind_param("si", $exam_title, $_SESSION['user_id']);
$stmt->execute();
$exam_id = $conn->insert_id;

// Loop through each question and insert it into the questions table
for ($i = 1; $i <= $question_count; $i++) {
    $question_text = isset($_POST['question'.$i]) ? $_POST['question'.$i] : '';
    
    if (empty($question_text)) {
        header("Location: create_exam.php?error=Please fill in all required fields");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text) VALUES (?, ?)");
    $stmt->bind_param("is", $exam_id, $question_text);
    $stmt->execute();
    $question_id = $conn->insert_id;
}

header("Location: dashboard.php?success=Exam created successfully");
exit;
?>

