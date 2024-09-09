<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $role_id);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $id;
            $_SESSION['role_id'] = $roleid;
            
            if ($role_id == 1) {
                // Redirect to admin page
                header('Location: administrator.php');
            } elseif ($role_id == 2) {
                // Redirect to user page
                header('Location: trainer.php');
            } elseif ($role_id == 3) {
                header('Location: consultant.php');
            } elseif ($role_id == 4) {
                header('Location: examiner.php');
            } else {
                header('Location: index.php');
            }

            exit;
        }
        else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Invalid username or password";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Error</title>
</head>
<body>
    <?php if (isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <p><a href="index.php">Return to homepage</a></p>
</body>
</html>