<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $exam_id = $_GET['exam_id'];

    $stmt = $conn->prepare("DELETE FROM exams WHERE id = ?");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_exams.php");
    exit;
}
?>
