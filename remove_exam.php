<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    $sql = "DELETE FROM exam WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    
    header("Location: manage_exam.php");
    exit;
} else {
    header("Location: manage_exam.php");
    exit;
    }
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
    </head>
    <body>
        <header>
        </header>
        <div class="dashboard-content">
</div>

</body>
</html>
```    
