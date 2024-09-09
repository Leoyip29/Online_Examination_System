<?php
session_start();
require_once 'config.php';
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FDM online examination</title>
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

    /* Main section */

    .main {
    padding: 100px 0;
    background-image: url('img/main.jpg');
    background-size: cover;
    background-position: center;
    color: #fff;
    }

    .main h1 {
    font-size: 48px;
    margin-bottom: 20px;
    text-align: center;
    color: #fff;
    }

    /* About section */

    #about {
    padding: 100px 0;
    background-color: #f1f1f1;
    }

    #about h3 {
    font-size: 36px;
    margin-bottom: 20px;
    text-align: center;
    }

    .about-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    }

    .about-item {
    flex-basis: calc(33.33% - 30px);
    margin: 15px;
    padding: 30px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s;
    }

    .about-item:hover {
    transform: translateY(-5px);
    }

    .about-icon {
    font-size: 60px;
    margin-bottom: 20px;
    text-align: center;
    color: #04AA6D;
    }

    .about-title {
    font-size: 24px;
    margin-bottom: 10px;
    text-align: center;
    }

    .about-description {
    font-size: 16px;
    text-align: center;
    line-height: 1.5;
    }

    img {
      width: 100%;
      height: auto;
      max-width: 350px;
      max-height: 350px;
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

    /* services styles */
    #services {
      background-color: #f9f9f9;
      padding: 100px 0;
    }

    #services h3 {
      margin-bottom: 64px;
      font-size: 36px;
      font-weight: 700;
      color: #333;
    }

    #services p {
      margin-bottom: 64px;
      font-size: 20px;
      color: #666;
    }

    #services .w3-card {
      background-color: #fff;
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: box-shadow 0.3s ease-in-out;
    }

    #services .w3-card:hover {
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    #services h4 {
      margin-bottom: 24px;
      font-size: 24px;
      font-weight: 600;
      color: #333;
    }

    #services p {
      font-size: 18px;
      color: #666;
    }



    /* Responsive styles */

    @media screen and (max-width: 768px) {
    .header {
    flex-wrap: wrap;
    }

    .site-name {
    margin-bottom: 10px;
    }

    .header-right {
    flex-basis: 100%;
    justify-content: center;
    margin-top: 10px;
    }

    .modal-content {
    width: 90%;
    }

    .about-item {
    flex-basis: calc(50% - 30px);
    }
    }

    @media screen and (max-width: 576px) {
    .about-item {
    flex-basis: 100%;
    }
    }
    </style>
</head>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<body>
    <!-- Header -->
    <header class="header">
      <a href="index.php" class="site-name">FDM</a>
      <div class="header-right">
        <?php if (isset($_SESSION['username'])): ?>
          <!-- <a href="dashboard.php">Dashboard</a> -->
          <a href="#" onclick="goBack()" class="back-btn">Dashboard</a>
          <a href="#about" class="about-btn">About</a>
          <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
          <a href="#about" class="about-btn">About</a>
          <a class="login-btn" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a>
        <?php endif; ?>
      </div>
    </header>




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

  <script>
  // Get the modal
  var modal = document.getElementById('id01');

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }
  </script>


 <!-- main Section -->
 <div class="main">
 <div class="w3-container" style="padding:128px 16px" id="main">
    <h1 class="w3-center">Welcome to FDM online examination page</h3>
    <p class="w3-center w3-large">Examination Application</p>
    </div>
  </div>
  </div>




<!-- About Section -->
<div class="w3-container" style="padding: 80px 16px" id="about">
  <h3 class="w3-center">ABOUT FDM GROUP</h3>
  <p class="w3-center w3-large">We are a global professional services provider that empowers people with valuable skills and experience to succeed in the digital world.</p>
  <div class="w3-row-padding" style="margin-top: 64px">
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-card">
        <img src="img/mission.png" alt="Mission" style="width:100%">
        <div class="w3-container">
          <h4><b>Our Mission</b></h4>
          <p>We empower people with valuable skills and experience to succeed in the digital world.</p>
        </div>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-card">
        <img src="img/values.png" alt="Values" style="width:100%">
        <div class="w3-container">
          <h4><b>Our Values</b></h4>
          <p>Integrity, Excellence, Respect, Collaboration, and Diversity &amp; Inclusion.</p>
        </div>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-card">
        <img src="img/history.png" alt="History" style="width:100%">
        <div class="w3-container">
          <h4><b>Our History</b></h4>
          <p>We have been providing professional services for over 30 years and have trained over 15,000 consultants.</p>
        </div>
      </div>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <div class="w3-card">
        <img src="img/gobal.png" alt="Global Presence" style="width:100%">
        <div class="w3-container">
          <h4><b>Global Presence</b></h4>
          <p>We have offices in Leeds, Glasgow, Brighton, New York City, Toronto, Reston, Frankfurt, Hong Kong, Singapore, Sydney and Shanghai, and work with clients worldwide.</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Our Services Section -->
<div class="w3-container" style="padding: 80px 16px" id="services">
  <h3 class="w3-center">OUR SERVICES</h3>
  <p class="w3-center w3-large">We offer a range of services to help businesses succeed in the digital age.</p>
  <div class="w3-row-padding" style="margin-top: 64px">
    <div class="w3-col l4 m6 w3-margin-bottom">
      <div class="w3-card">
        <div class="w3-container">
          <h4><b>Consulting</b></h4>
          <p>We help businesses develop digital strategies, optimize operations, and drive growth.</p>
        </div>
      </div>
    </div>
    <div class="w3-col l4 m6 w3-margin-bottom">
      <div class="w3-card">
        <div class="w3-container">
          <h4><b>Training</b></h4>
          <p>We provide industry-leading training programs to help individuals and businesses upskill and reskill.</p>
        </div>
      </div>
    </div>
    <div class="w3-col l4 m6 w3-margin-bottom">
      <div class="w3-card">
        <div class="w3-container">
          <h4><b>Staffing</b></h4>
          <p>We offer staffing services to help businesses find the right talent for their digital projects and initiatives.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Find Us Section -->
<div class="w3-container" style="padding: 80px 16px" id="find-us">
  <h3 class="w3-center">FIND US</h3>
  <p class="w3-center w3-large">Visit our offices and get in touch with our team.</p>
  <div class="w3-row-padding" style="margin-top: 64px">
    <div class="w3-col m6">
      <div class="w3-card">
        <div class="w3-container w3-center">
          <h4><b>Our Offices</b></h4>
          <p>123 Main Street<br>London, UK<br>12345</p>
        </div>
      </div>
    </div>
    <div class="w3-col m6">
      <div class="w3-card">
        <div class="w3-container w3-center">
          <h4><b>Contact Us</b></h4>
          <p>Email: info@fdmgroup.com<br>Phone: +44 1234 567890</p>
        </div>
      </div>
    </div>
  </div>
</div>





  <!-- Footer -->
  <footer>
    <div>
      <a href="https://www.facebook.com/FDMGroup/?locale=en_GB" class="fa fa-facebook"></a>
      <a href="https://twitter.com/FDMGroup?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" class="fa fa-twitter"></a>
      <a href="https://www.instagram.com/fdm_group/?hl=en" class="fa fa-instagram"></a>
    </div>
  </footer>
  <script>
    // Add this script to handle header background color on scroll
    $(window).scroll(function() {
      if ($(this).scrollTop() > 50) {
        $(".header").css("background-color", "rgba(4, 170, 109, 0.9)");
      } else {
        $(".header").css("background-color", "#04AA6D");
      }
    });
  </script>
</body>



