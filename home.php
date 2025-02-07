<?php
session_start();

// Prevent accessing home page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Prevent caching to ensure session is checked every time
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Logout function inside home.php
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    // Redirect with a timestamp to prevent back navigation
    header("Location: login.php?session_expired=" . time());
    exit();
}

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
        }

        h3 {
            color: white;
            font-weight: bold;
        }

        p {
            color: rgba(255, 255, 255, 0.8);
        }

        .quote {
            font-style: italic;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 15px;
        }

        .btn-logout {
            background: rgba(255, 0, 0, 0.7);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            width: 100%;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 0, 0, 0.9);
        }
    </style>
</head>
<body>

    <div class="glass-container">
        <h3>Welcome, <?php echo htmlspecialchars($fname); ?>! 🎉</h3>
        <p>Glad to have you here.</p>
        
        <p class="quote">"जिस तरह तू सोचता है, उस तरह नहीं होता, दुनिया वही करवाती है जो वह चाहती है।" - धर्मवीर भारती</p>

        <!-- Logout Button -->
        <a href="home.php?logout=true" class="btn btn-logout">Logout</a>
    </div>

    <script>
        // Prevent back button from loading cached pages
        window.onload = function() {
            if (performance.navigation.type === 2) {
                location.href = "login.php?session_expired=true";
            }
        };

        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>

</body>
</html>
