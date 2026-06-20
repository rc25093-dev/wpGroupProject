<?php
require 'database.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

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
    <nav>
        <a href="index.html">Home</a>
        <a href="events.html">Events</a>
        <a href="booking.html">Booking</a>
        <a href="dashboard.html">Dashboard</a>
        <a href="feedback.html">Feedback</a>
    </nav>

    <main class="flexbox flex-column align-center flex-start">

    <form action="signup.php" method="POST" id="signupForm" class="flexbox flex-column align-center">
        
        <h1>Sign Up for EventEase</h1>

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
        const form = document.getElementById('signupForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmpassword');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

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
        confirmPassword.addEventListener('input', validate);

        form.addEventListener('submit', (e) => {
            if (!validate()) e.preventDefault();
        });
    </script>

</body>
</html>