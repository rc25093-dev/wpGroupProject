<?php
session_start();
require 'database.php';

$loggedIn = isset($_SESSION['user_id']);
if ($loggedIn) {
    header('Location: index.php');
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $message = "Username or Email already taken!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed]);

            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up for EventEase</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="login-signup.css">
    <script src="feedback.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fa-solid fa-user-plus"></i>
            EventEase
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="event_listing.php">Events</a></li>
            <li><a href="booking.php" data-protect="true">Booking</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="feedback.php" data-protect="true">Feedback</a></li>
            <?php if ($loggedIn): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php" class="active">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="flexbox flex-column align-center flex-start">

    <form action="signup.php" method="POST" id="signupForm" class="flexbox flex-column align-center">
        
        <h1>Sign Up for EventEase</h1>

        <?php if (!empty($message)): ?>
            <script>
                window.onload = function() {
                    alert('<?php echo addslashes($message); ?>');
                };
            </script>
        <?php endif; ?>

        <div class="flexbox flex-column">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter new username..." required>
        </div>

        <div class="flexbox flex-column">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter new email..." required>
        <span id="emailError" style="color:red; font-size: 12px;"></span>
        </div>

        <div class="flexbox flex-column">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter new password (min 8 characters)..." required>
        <span id="lengthError" style="color:red; font-size: 12px;"></span>
        </div>
    
        <div class="flexbox flex-column">
        <label for="confirmpassword">Confirm Password</label>
        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm your password..." required>
        <span id="passwordError" style="color:red; font-size: 12px;"></span>
        </div>

        <button type="submit" class="submit-form-btn" style="margin-top: 20px;" id="submitBtn">SIGN UP</button>
    </form>

        <p>Already have an account? <a href="login.php">Log In</a></p>

    </main>

    <footer>
        <p>&copy; 2026 EventEase. All Rights Reserved.</p>
    </footer>

    <script>
        document.querySelectorAll('[data-protect="true"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (!<?php echo json_encode($loggedIn); ?>) {
                    e.preventDefault();
                    alert('Please log in first to access this page.');
                    window.location.href = 'login.php';
                }
            });
        });
    </script>

    <script>
        const form = document.getElementById('signupForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmpassword');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const lengthError = document.getElementById('lengthError');

        function validate() {
            let isValid = true;

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                email.style.border = "2px solid red";
                emailError.textContent = "Please enter a valid email address.";
                isValid = false;
            } else {
                email.style.border = "1px solid #ccc";
                emailError.textContent = "";
            }

            if (password.value.length < 8) {
                password.style.border = "2px solid red";
                lengthError.textContent = "Password must be at least 8 characters.";
                isValid = false;
            } else {
                password.style.border = "1px solid #ccc";
                lengthError.textContent = "";
            }

            if (password.value !== confirmPassword.value || confirmPassword.value === "") {
                confirmPassword.style.border = "2px solid red";
                passwordError.textContent = "Passwords do not match.";
                isValid = false;
            } else {
                confirmPassword.style.border = "1px solid #ccc";
                passwordError.textContent = "";
            }

            return isValid;
        }

        email.addEventListener('input', validate);
        password.addEventListener('input', validate);
        confirmPassword.addEventListener('input', validate);

        form.addEventListener('submit', (e) => {
            if (!validate()) e.preventDefault();
        });
    </script>

</body>
</html>