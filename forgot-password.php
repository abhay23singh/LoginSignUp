<?php
session_start();
require_once('config.php');

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Please enter your email!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $token = bin2hex(random_bytes(50));
            $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("ss", $token, $email);
            if ($stmt->execute()) {
                $_SESSION['reset_email'] = $email;
                header("Location: reset-password.php?token=$token");
                exit();
            } else {
                $error = "Error updating reset token.";
            }
        } else {
            $error = "No account found with that email!";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Forgot Password</h2>
            <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Enter your email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="submit" name="submit" class="btn btn-custom">Submit</button>
            </form>
            <p class="text-muted mt-3 para"><a href="login.php">Back to Login</a></p>
        </div>
    </div>

</body>
</html>
