<?php
require_once 'config.php';

$user_id = $_GET['user_id'] ?? null;
$exam_id = $_GET['exam_id'] ?? null;

if ($user_id !== null && $exam_id !== null) {
    $userAnswers = fetchUserAnswers($user_id, $exam_id);
    $examTitle = fetchExamTitle($exam_id);

    header('Content-Type: application/json');
    echo json_encode([
        'userAnswers' => $userAnswers,
        'examTitle' => $examTitle,
    ]);
}

function fetchUserAnswers($user_id, $exam_id) {
    global $conn;

    $sql = "SELECT q.question_text, a.answer
            FROM answers a
            INNER JOIN questions q ON a.question_id = q.id
            WHERE a.user_id = ? AND q.exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $userAnswers = [];
    while ($row = $result->fetch_assoc()) {
        $userAnswers[] = $row;
    }

    $stmt->close();

    return $userAnswers;
}

function fetchExamTitle($exam_id) {
    global $conn;

    $sql = "SELECT title FROM exams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $exam = $result->fetch_assoc();
    $stmt->close();

    return $exam['title'];
}

// Close the database connection
$conn->close();
?>

