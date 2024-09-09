<?php
require_once 'config.php';

$users = fetchUsers();
$exams = fetchExams();

function fetchUsers() {
    global $conn;

    $sql = "SELECT id, username FROM users";
    $result = $conn->query($sql);

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

function fetchExams() {
    global $conn;

    $sql = "SELECT id, title FROM exams";
    $result = $conn->query($sql);

    $exams = [];
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }

    return $exams;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $exam_id = $_POST['exam_id'];
    $feedback = $_POST['feedback'];
    $score = $_POST['score'];

    submitFeedbackAndScore($user_id, $exam_id, $feedback, $score);
}

function submitFeedbackAndScore($user_id, $exam_id, $feedback, $score) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, exam_id, feedback, score) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $user_id, $exam_id, $feedback, $score);
    $stmt->execute();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Feedback Form</title>
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

/* Feedback Form */
form {
  margin: 100px auto;
  padding: 20px;
  width: 80%;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

select,
textarea,
input[type="number"],
input[type="submit"] {
  display: block;
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 20px;
  font-size: 16px;
}

textarea {
  resize: vertical;
  min-height: 100px;
}

input[type="submit"] {
  background-color: #1F1F1F;
  color: #fff;
  border: none;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #04AA6D;
}

/* User Answers */
#userAnswersContainer {
  margin: 50px auto;
  padding: 20px;
  width: 80%;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

#userAnswersContainer h2 {
  margin-top: 0;
  font-size: 24px;
  font-weight: bold;
}

#userAnswersContainer p {
  margin-bottom: 10px;
  font-weight: bold;
}

table {
  border-collapse: collapse;
  width: 100%;
  margin-top: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

thead {
  background-color: #1F1F1F;
  color: #fff;
}
th,
td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

th {
  background-color: #1F1F1F;
  color: #fff;
  font-weight: bold;
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
    <h1>Submit Feedback and Score</h1>
    <form action="" method="POST">
        <label for="user_id">User:</label>
        <select name="user_id" id="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="exam_id">Exam:</label>
        <select name="exam_id" id="exam_id">
            <?php foreach ($exams as $exam): ?>
                <option value="<?= $exam['id'] ?>"><?= $exam['title'] ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="feedback">Feedback:</label>
        <textarea name="feedback" id="feedback" rows="4" cols="50"></textarea>
        <br><br>
        <label for="score">Score:</label>
        <input type="number" name="score" id="score" min="0">
        <br><br>
        <input type="submit" value="Submit Feedback and Score">
    </form>
    <br>
    <div id="userAnswersContainer" style="display: none;">
        <h2>User's Answers</h2>
        <p>User ID: <span id="displayUserId"></span></p>
        <p>Exam: <span id="displayExamTitle"></span></p>
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody id="userAnswersTableBody">
            </tbody>
        </table>
        </div>
    <script>
        document.getElementById('user_id').addEventListener('change', updateUserAnswers);
        document.getElementById('exam_id').addEventListener('change', updateUserAnswers);

        async function updateUserAnswers() {
            const user_id = document.getElementById('user_id').value;
            const exam_id = document.getElementById('exam_id').value;

            const response = await fetch(`fetch_user_answers.php?user_id=${user_id}&exam_id=${exam_id}`);
            const data = await response.json();

            document.getElementById('displayUserId').textContent = user_id;
            document.getElementById('displayExamTitle').textContent = data.examTitle;
            const tableBody = document.getElementById('userAnswersTableBody');

            tableBody.innerHTML = '';

            for (const answer of data.userAnswers) {
                const row = document.createElement('tr');
                const questionCell = document.createElement('td');
                const answerCell = document.createElement('td');

                questionCell.textContent = answer.question_text;
                answerCell.textContent = answer.answer;

                row.appendChild(questionCell);
                row.appendChild(answerCell);

                tableBody.appendChild(row);
            }

            document.getElementById('userAnswersContainer').style.display = 'block';
        }

        // Initialize user answers on page load
        updateUserAnswers();
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>



