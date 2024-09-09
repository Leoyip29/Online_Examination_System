<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fdm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];
    $input_role = $_POST["role"];

    $password_hash = password_hash($input_password, PASSWORD_DEFAULT);

    // Get the role ID from the roles table
    $role_id_query = "SELECT id FROM roles WHERE role_name = ?";
    $stmt = $conn->prepare($role_id_query);
    $stmt->bind_param("s", $input_role);
    $stmt->execute();
    $stmt->bind_result($role_id);
    $stmt->fetch();
    $stmt->close();

    // Insert the user into the users table
    $insert_query = "INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssi", $input_username, $password_hash, $role_id);
    $stmt->execute();
    $stmt->close();

    echo "User registered successfully!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

<div id="id01" class="modal">
    
    <form class="modal-content animate" action="login.php" method="post">
      <div class="imgcontainer">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        <img src="logo.png" alt="FDM" class="FDM">
      </div>

      <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>
          
        <button type="submit">Login</button>
        <label>
          <input type="checkbox" checked="checked" name="remember"> Remember me
        </label>
      </div>

      <div class="container" style="background-color:#f1f1f1">
        <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>
        <!-- Header -->
        <header class="header">
      <a href="index.php" class="site-name">FDM</a>
      <div class="header-right">
        <?php if (isset($_SESSION['username'])): ?>
          <!-- <a href="dashboard.php">Dashboard</a> -->
          <a href="#" onclick="goBack()" class="back-btn">Dashboard</a>
          <a href="index.php#about" class="about-btn">About</a>
          <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
          <a href="index.php##about" class="about-btn">About</a>
          <a class="login-btn" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a>
        <?php endif; ?>
      </div>
    </header>
    <h1>Register</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="role">Choose the role that you would like to register</label>
        <select name="role" id="role" required>
            <option value="trainer">Trainer</option>
            <option value="consultant">Consultant</option>
            <option value="examiner">Examiner</option>
            <option value="admin">Admin</option>
        </select>
        <br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

