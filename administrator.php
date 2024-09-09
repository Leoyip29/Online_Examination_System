<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Retrieve user role from database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role_name FROM roles WHERE id = (SELECT role_id FROM users WHERE id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user_role = $row['role_name'];

$sql = "SELECT * FROM users";
$result1 = $conn->query($sql);

// Fetch available roles from the database
$role_sql = "SELECT * FROM roles";
$role_result = $conn->query($role_sql);
$roles = [];

if ($role_result->num_rows > 0) {
    while($role_row = $role_result->fetch_assoc()) {
        $roles[] = $role_row;
    }
}

// Create user function
function create_user($conn, $username, $password, $role_id) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssi", $username, $hashed_password, $role_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Remove user function
function remove_user($conn, $user_id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Create user function
if (isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $hashed_password, $role_id);

    if ($stmt->execute()) {
        // Redirect to the same page to update the information
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Remove user function
if (isset($_POST['remove_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect to the same page to update the information
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

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

        .card img {
            width: 100%;
            height: auto;
            max-width: 300px; /* or any other maximum width you want */
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

    <div class="dashboard-content">
        <h1>Welcome to the Dashboard</h1>
        <p>
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Your role is: <?php echo $user_role; ?>
        </p>
        <div class="grid-container">
                    <!-- user table to show details -->
            <div id="userTable" class="card">
            <div class="card-header">
                <h3>User Table</h3>
            </div>
            <div class="card-body">
                <table class="exam-table">
                <thead>
                    <tr>
                    <th>Username</th>
                    <th>User ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row1["username"] . "</td>";
                        echo "<td>" . $row1["id"] . "</td>";
                        echo "</tr>";
                    }
                    } else {
                    echo "<tr><td colspan='2'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
                </table>
            </div>
            </div>
        <div class="card">
            <img src="img/takeexam.png" alt="Take Exam">
            <button onclick="window.open('choose_exam.php', '_blank', 'width=500,height=600')">Take Exam</button>
        </div>

     
        <div class="card">
            <img src="img/markexam.jpg" alt="Mark Exam">
            <button onclick="window.open('exam_feedback_form.php', '_blank', 'width=500,height=600')">Mark Exam</button>
        </div>

       
        <div class="card">
            <img src="img/createexam.jpg" alt="Create Exam">
            <button onclick="window.open('create_exam.php', '_blank', 'width=500,height=600')">Create Exam</button>
        </div>

        <div class="card">
            <img src="img/feedback.jpg" alt="Feedback">
            <button onclick="window.open('feedback_report.php', '_blank', 'width=500,height=600')">Feedback Report</button>
        </div>

       
        <div class="card">
            <img src="img/manageexam.jpg" alt="Manage Exam">
            <button onclick="window.open('manage_exams.php', '_blank', 'width=500,height=600')">Manage Exam</button>
        </div>

      
        <div class="card">
            <img src="img/manageuser.png" alt="Manage User">
            <button onclick="toggleManageUser()">Manage User</button>
        </div>

            <!-- Add create user form -->
            <div id="createUserForm" class="fade-in" style="display:none;">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Create User</h2>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <label for="role_id">Role:</label>
                    <select name="role_id" id="role_id" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="create_user">Create User</button>
                </form>
            </div>

            <!-- Add remove user form -->
            <div id="removeUserForm" class="fade-in" style="display:none;">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Remove User</h2>
                    <label for="user_id">User ID:</label>
                    <input type="number" name="user_id" id="user_id" required>
                    <button type="submit" name="remove_user">Remove User</button>
                </form>
            </div>

    <!-- Footer -->
    <footer>
    <div class="icons">
            <a href="https://www.facebook.com/FDMGroup/?locale=en_GB" class="fa fa-facebook"></a>
            <a href="https://twitter.com/FDMGroup?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" class="fa fa-twitter"></a>
            <a href="https://www.instagram.com/fdm_group/?hl=en" class="fa fa-instagram"></a>
        </div>
    </footer>

        <!-- Add JavaScript to show and hide forms -->
        <script>
            function toggleManageUser() {
                var createUserForm = document.getElementById("createUserForm");
                var removeUserForm = document.getElementById("removeUserForm");
                createUserForm.style.display = createUserForm.style.display === "none" ? "block" : "none";
                removeUserForm.style.display = removeUserForm.style.display === "none" ? "block" : "none";
            }

        </script>
</body>
</html>


