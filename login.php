<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernamepassword = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernamepassword, $usernamepassword]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
        exit();
    } else {
        echo "<script>alert('Invalid credentials.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In to EventEase</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="login-signup.css">
    <script src="feedback.js"></script>
</head>

<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="events.html">Events</a>
        <a href="booking.html">Booking</a>
        <a href="dashboard.html">Dashboard</a>
        <a href="feedback.html">Feedback</a>
    </nav>

    <main class="flexbox flex-column align-center flex-start">

        <form action="login.php" method="POST" class="flexbox flex-column align-center">
            <h1>Log In to EventEase</h1>

            <div class="flexbox flex-column">
            <label for="username">Username/Email</label>
            <input type="text" id="username" name="username" placeholder="Enter username or email..." required>
            </div>

            <div class="flexbox flex-column">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password..." required>
            </div>

            <button type="submit" class="submit-form-btn" style="margin-top: 20px;">LOG IN</button>

        </form>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>

    </main>

    <footer>
        <p>&copy; 2026 EventEase. All Rights Reserved.</p>
    </footer>

</body>
</html>