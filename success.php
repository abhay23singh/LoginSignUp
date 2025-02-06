<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-container {
            width: 50%;
            margin: 100px auto;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <h2>✅ Your data has been submitted successfully!</h2>
        <p>You can now proceed to the login page.</p>
        <a href="login.php" class="btn btn-custom">Go to Login</a>
    </div>

</body>
</html>
