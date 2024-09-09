<?php
require_once 'config.php';

// Fetch feedback report
$feedbackReport = fetchFeedbackReport();

function fetchFeedbackReport() {
    global $conn;

    $sql = "SELECT f.id, f.feedback, f.score, u.username as user, e.title as exam
            FROM feedbacks f
            INNER JOIN users u ON f.user_id = u.id
            INNER JOIN exams e ON f.exam_id = e.id
            ORDER BY f.id";
    $result = $conn->query($sql);
    $report = [];

    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }

    return $report;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Report</title>
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

      /* Feedback Report */
      table {
        border-collapse: collapse;
        width: 100%;
        margin: 100px auto 0;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      }

      thead {
        background-color: #1F1F1F;
        color: #fff;
      }

      th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
      }

      tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      tr:hover {
        background-color: #ddd;
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
    <h1>Feedback Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Exam</th>
                <th>Feedback</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbackReport as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['user'] ?></td>
                    <td><?= $row['exam'] ?></td>
                    <td><?= $row['feedback'] ?></td>
                    <td><?= $row['score'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
