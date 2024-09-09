<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST['exam_id'];
    $exam_title = $_POST['exam_title'];

    $stmt = $conn->prepare("UPDATE exams SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $exam_title, $exam_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE questions SET question_text = ?, answer_type = ? WHERE id = ?");
    for ($i = 1; $i <= count($_POST) - 2; $i++) {
        $question_id = $_POST['question_id' . $i];
        $question_text = $_POST['question_text' . $i];
        $answer_type = $_POST['answer_type' . $i];
        $stmt->bind_param("ssi", $question_text, $answer_type, $question_id);
        $stmt->execute();
    }
    $stmt->close();

    header("Location: manage_exams.php");
    exit;
}

$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : 0;

if ($exam_id == 0) {
    header("Location: manage_exams.php");
    exit;
}

$stmt = $conn->prepare("SELECT title FROM exams WHERE id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$stmt->bind_result($exam_title);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT id, question_text, answer_type FROM questions WHERE exam_id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
.login-btn,
.back-btn,
.logout-btn {
padding: 10px;
margin-left: 10px;
border-radius: 5px;
font-size: 16px;
color: #fff;
transition: background-color 0.2s;
}

.about-btn:hover,
.login-btn:hover,
.back-btn:hover,
.logout-btn:hover {
background-color: #04AA6D;
cursor: pointer;
}
.about-btn,
.login-btn,
.back-btn,
.logout-btn {
color: #fff;
}

.login-btn {
border: none;
outline: none;
cursor: pointer;
}

.back-btn {
background-color: transparent;
border: none;
outline: none;
cursor: pointer;
}

/* Login modal */

.modal {
display: none;
position: fixed;
z-index: 999;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
background-color: #f1f1f1;
margin: 10% auto;
padding: 20px;
border: 1px solid #888;
width: 50%;
border-radius: 5px;
}

.imgcontainer {
text-align: center;
margin: 24px 0 12px 0;
position: relative;
}

.FDM {
width: 20%;
border-radius: 50%;
}

.close {
position: absolute;
right: 10px;
top: 0;
color: #000;
font-size: 35px;
font-weight: bold;
transition: color 0.2s;
}

.close:hover,
.close:focus {
color: #f44336;
cursor: pointer;
}

.container {
padding: 16px;
}

input[type="text"],
input[type="password"] {
width: 100%;
padding: 12px 20px;
margin: 8px 0;
display: inline-block;
border: 1px solid #ccc;
box-sizing: border-box;
border-radius: 4px;
}

button[type="submit"] {
background-color: #04AA6D;
color: #fff;
padding: 14px 20px;
margin: 8px 0;
border: none;
border-radius: 4px;
cursor: pointer;
width: 100%;
}

button[type="submit"]:hover {
background-color: #3e8e41;
}

label {
display: block;
margin-bottom: 10px;
}

.checkbox-container {
display: flex;
align-items: center;
}

.checkbox-container input[type="checkbox"] {
margin-right: 5px;
}


    /* Dashboard styles */

    .dashboard-content {
        padding-top: 80px;
        padding-left: 10%;
        padding-right: 10%;
        background-color: #f1f1f1;
        min-height: calc(100vh - 200px);
    }

    .dashboard-content h1 {
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .dashboard-content p {
        font-size: 18px;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Card styles */

    .card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        text-align: center;
        margin-bottom: 20px;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    .card button {
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

    .card button:hover {
        background-color: #3e8e41;
    }

    /* Grid layout for cards */

    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-gap: 20px;
        justify-items: center;
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


form {
background-color: #f1f1f1;
padding: 20px;
border-radius: 5px;
width: 100%;
max-width: 500px;
margin: auto;
}

form h2 {
text-align: center;
margin-bottom: 20px;
}

form label {
display: block;
margin: 10px 0;
}

form input,
form select {
width: 100%;
padding: 8px;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
resize: vertical;
}

form button {
width: 100%;
background-color: #4CAF50;
color: white;
padding: 14px 20px;
margin: 8px 0;
border: none;
border-radius: 4px;
cursor: pointer;
}

form button:hover {
background-color: #45a049;
}

/* CSS for the user table */
.exam-table {
width: 100%;
border-collapse: collapse;
margin: 20px 0;
}

.exam-table thead {
background-color: #f2f2f2;
}

.exam-table th,
.exam-table td {
text-align: left;
padding: 8px;
border: 1px solid #ddd;
}

.exam-table tr:nth-child(even) {
background-color: #f2f2f2;
}

/* Add CSS animations */
@keyframes fadeIn {
from {
    opacity: 0;
}
to {
    opacity: 1;
}
}

/* Add animation classes */
.fade-in {
animation: fadeIn 0.5s;
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
        <h1>Edit Exam</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            <div class="form-group">
                <label for="exam_title">Exam Title:</label>
                <input type="text" class="form-control" name="exam_title" value="<?php echo htmlspecialchars($exam_title); ?>" required>
            </div>
            <h2>Edit Questions</h2>
            <?php
            $question_count = 0;
            while ($row = $result->fetch_assoc()) {
                $question_count++;
                echo '<div class="form-group">';
                echo '<label for="question_text' . $question_count . '">Question ' . $question_count . ':</label>';
                echo '<input type="text" class="form-control" name="question_text' . $question_count . '" value="' . htmlspecialchars($row['question_text']) . '" required>';


                echo '</div>';
                echo '<input type="hidden" name="question_id' . $question_count . '" value="' . $row['id'] . '">';
            }
            ?>
            <input type="submit" class="btn btn-success" value="Save Changes">
        </form>
    </div>

</body>
</html>




