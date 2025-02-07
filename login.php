<?php
session_start();
require_once('config.php');

// Prevent accessing login page if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Cache-Control Headers - Prevent Back Navigation
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Prevent browser from loading a cached version of login page
if (isset($_GET['session_expired'])) {
    echo "<script>localStorage.clear(); sessionStorage.clear();</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and password are required!'); window.location.href='login.php';</script>";
        exit();
    }

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, fname, lname, password FROM users WHERE LOWER(username) = LOWER(?)");
    
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fname, $lname, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User does not exist! Please sign up.'); window.location.href='signup.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalistic Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        // Prevent browser back button from going to home page after logout
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email or Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="btn btn-custom">Login</button>
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
            </form>
            <p class="text-muted mt-3 para">Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>

</body>
</html>
