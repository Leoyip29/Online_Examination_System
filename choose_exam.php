<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

// Fetch exams from the database
$query = "SELECT id, title FROM exams";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<script src="js/chat.js"></script>
    <script src="js/app.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <a href="index.php#about" class="about-btn">About</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <div class="container">
        <h1>Choose an Exam</h1>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li><a href="take_exam.php?exam_id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
                }
            } else {
                echo '<li>No exams available</li>';
            }
            ?>
        </ul>
    </div>
</body>
</html>
