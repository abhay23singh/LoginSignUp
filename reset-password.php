<?php
session_start();
require_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ?");
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("Invalid or expired token!");
    }

    $stmt->bind_result($email);
    $stmt->fetch();
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_token'] = $token;
} else {
    die("No token provided!");
}

if (isset($_POST['reset'])) {
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Please enter a password!";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $hashedPassword, $_SESSION['reset_token']);
        if ($stmt->execute()) {
            echo "<script>alert('Password reset successful!'); window.location.href='login.php';</script>";
        } else {
            $error = "Error updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Reset Password</h2>
            <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                </div>
                <button type="submit" name="reset" class="btn btn-custom">Reset Password</button>
            </form>
            <p class="text-muted mt-3 para"><a href="login.php">Back to Login</a></p>
        </div>
    </div>

</body>
</html>
