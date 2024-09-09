<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : 0;

if ($exam_id == 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT title FROM exams WHERE id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$stmt->bind_result($exam_title);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT id, question_text FROM questions WHERE exam_id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Take Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Global styles */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Raleway", sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #333;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #1F1F1F;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 999;
            font-weight: bold;
        }

        .site-name {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .about-btn,
        .logout-btn {
            padding: 10px;
            margin-left: 10px;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            transition: background-color 0.2s;
        }

        .about-btn:hover,
        .logout-btn:hover {
            background-color: #04AA6D;
            cursor: pointer;
        }

        .about-btn {
            margin-right: 10px;
        }

        /* Exam List */
        .container {
            max-width: 800px;
            margin: 100px auto 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .container h1 {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .exam-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .exam-item {
            width: 300px;
            margin: 20px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 5px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            text-align: center;
        }

        .exam-item:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .exam-item a {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            transition: color 0.2s;
        }
        .exam-item a:hover {
        color: #04AA6D;
    }

    .exam-item p {
        font-size: 18px;
        color: #666;
        margin-bottom: 20px;
    }

    .exam-item button {
        background-color: #04AA6D;
        border: none;
        color: white;
        padding: 12px 24px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.2s;
    }

    .exam-item button:hover {
        background-color: #3e8e41;
    }

    /* Footer */
    footer {
        padding: 20px;
        text-align: center;
        background-color: #f1f1f1;
    }

    footer a {
        margin: 0 10px;
        font-size: 24px;
        color: #333;
    }

</style>
</head>
<body>
    <header class="header">
        <a href="index.php" class="site-name">FDM</a>
        <div class="header-right">
            <a href="#about" class="about-btn">About</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <div class="container">
        <h1><?php echo htmlspecialchars($exam_title); ?></h1>
        <form action="submit_exam.php" method="post">
            <?php
            $question_count = 0;
            while ($row = $result->fetch_assoc()) {
                $question_count++;
                echo '<div class="form-group">';
                echo '<label for="answer' . $question_count . '">Question ' . $question_count . ': ' . htmlspecialchars($row['question_text']) . '</label>';
                echo '<input type="text" class="form-control" name="answer' . $question_count . '">';
                echo '</div>';
                echo '<input type="hidden" name="question_id' . $question_count . '" value="' . $row['id'] . '">';
            }
            ?>
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <br><br>
            <input type="submit" class="btn btn-success" value="Submit Exam">
        </form>
    </div>

</body>
</html>
