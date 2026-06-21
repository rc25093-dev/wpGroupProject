<?php
session_start();
require 'database.php';

$loggedIn = isset($_SESSION['user_id']);

$sql = "
SELECT
    COUNT(*) AS total_events
FROM events
";
$totalEvents = 0;
if ($result = mysqli_query($conn, $sql)) {
    $row = mysqli_fetch_assoc($result);
    $totalEvents = (int)($row['total_events'] ?? 0);
}

$bookingSql = "
SELECT
    COUNT(*) AS total_bookings,
    COALESCE(SUM(total_payment), 0) AS total_revenue
FROM bookings
";
$totalBookings = 0;
$totalRevenue = 0;
if ($result = mysqli_query($conn, $bookingSql)) {
    $row = mysqli_fetch_assoc($result);
    $totalBookings = (int)($row['total_bookings'] ?? 0);
    $totalRevenue = (float)($row['total_revenue'] ?? 0);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EventEase | Home</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fa-solid fa-calendar-days"></i>
            EventEase
        </div>
        <ul>
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="event_listing.php">Events</a></li>
            <li><a href="booking.php" data-protect="true">Booking</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="eventmanagement.php">Event Management</a></li>
            <li><a href="feedback.php" data-protect="true">Feedback</a></li>
            <?php if ($loggedIn): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section class="hero" style="text-align:center;">
        <h1>Plan, Discover, and Book Amazing Events</h1>
        <p>EventEase helps students and organizers manage events, bookings, and feedback in one place.</p>
        <div style="display:flex;justify-content:center;align-items:center;gap:12px;flex-wrap:wrap;">
            <a href="event_listing.php" class="submit-form-btn">Browse Events</a>
            <?php if ($loggedIn): ?>
                <a href="dashboard.php" class="submit-form-btn" style="background:#fff;color:#1d3b6d;">Go to Dashboard</a>
            <?php else: ?>
                <a href="login.php" class="submit-form-btn" style="background:#fff;color:#1d3b6d;">Login</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="statistics" style="width:min(1100px, 90%);margin:40px auto 0;">
        <div class="stat">
            <i class="fa-solid fa-calendar-check"></i>
            <h2><?php echo $totalEvents; ?></h2>
            <p>Events</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-ticket"></i>
            <h2><?php echo $totalBookings; ?></h2>
            <p>Bookings</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-money-bill-wave"></i>
            <h2>RM <?php echo number_format($totalRevenue, 2); ?></h2>
            <p>Revenue</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-comments"></i>
            <h2>Live</h2>
            <p>Feedback</p>
        </div>
    </section>

    <section style="padding: 40px 8%; width: 100%; box-sizing: border-box;">
        <h2 style="margin-bottom: 20px; text-align:left;">What you can do</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
            <div style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.08);">
                <h3>Manage Events</h3>
                <p>Create and organize event details from one central place.</p>
            </div>
            <div style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.08);">
                <h3>Track Bookings</h3>
                <p>See seat availability, totals, and booking activity instantly.</p>
            </div>
            <div style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.08);">
                <h3>Collect Feedback</h3>
                <p>Let attendees share their thoughts after each event.</p>
            </div>
        </div>
    </section>
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
</body>
</html>
