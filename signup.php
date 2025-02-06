<?php
require_once('config.php'); // Database connection

if (isset($_POST['signup'])) {
    // Fetch and sanitize form inputs
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validation checks
    if (empty($fname) || empty($lname) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("All fields are required!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("Username or email already taken!");
    }
    $stmt->close();

    // Secure password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: success.php");
        exit();
    } else {
        die("Error: " . $stmt->error);
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
    <title>Signup Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="signup-container">
            <div class="signup-box">
            <h2>Sign Up</h2>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="fname" placeholder="Enter first name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lname" placeholder="Enter last name" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Choose a username" required>
                </div>

                <div class="mt-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm password" required>
                    </div>
                </div>

                <button type="submit" name="signup" class="btn btn-custom mt-4">Sign Up</button>

                <p class="text-muted mt-3">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>

</body>
</html>
