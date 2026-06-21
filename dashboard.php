<?php
require 'database.php';

$eventSql = "
SELECT
    e.event_id,
    e.event_name,
    e.category,
    e.event_date,
    e.ticket_price,
    e.capacity,
    IFNULL(SUM(b.quantity), 0) AS booked,
    (e.capacity - IFNULL(SUM(b.quantity), 0)) AS available
FROM events e
LEFT JOIN bookings b ON e.event_id = b.event_id
GROUP BY e.event_id
ORDER BY e.event_date ASC
";
$eventResult = mysqli_query($conn, $eventSql);

$bookingSql = "
SELECT
    b.booking_id,
    b.customer_name,
    b.quantity,
    b.total_payment,
    b.booking_date,
    e.event_name
FROM bookings b
JOIN events e ON b.event_id = e.event_id
ORDER BY b.booking_date DESC
";
$bookingResult = mysqli_query($conn, $bookingSql);

$stats = [
    'events' => 0,
    'bookings' => 0,
    'revenue' => 0,
    'capacity' => 0,
];

if ($countResult = mysqli_query($conn, "SELECT COUNT(*) AS total_events FROM events")) {
    $countRow = mysqli_fetch_assoc($countResult);
    $stats['events'] = (int)($countRow['total_events'] ?? 0);
}
if ($countResult = mysqli_query($conn, "SELECT COUNT(*) AS total_bookings FROM bookings")) {
    $countRow = mysqli_fetch_assoc($countResult);
    $stats['bookings'] = (int)($countRow['total_bookings'] ?? 0);
}
if ($countResult = mysqli_query($conn, "SELECT COALESCE(SUM(total_payment), 0) AS total_revenue FROM bookings")) {
    $countRow = mysqli_fetch_assoc($countResult);
    $stats['revenue'] = (float)($countRow['total_revenue'] ?? 0);
}
if ($countResult = mysqli_query($conn, "SELECT COALESCE(SUM(capacity), 0) AS total_capacity FROM events")) {
    $countRow = mysqli_fetch_assoc($countResult);
    $stats['capacity'] = (int)($countRow['total_capacity'] ?? 0);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EventEase Dashboard</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fa-solid fa-chart-line"></i>
            EventEase
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="event_listing.php">Events</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="eventmanagement.php">Event Management</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Register</a></li>
        </ul>
    </nav>

    <section class="statistics" style="width:min(1100px, 90%);margin:0 auto;">
        <div class="stat">
            <i class="fa-solid fa-calendar-check"></i>
            <h2><?php echo $stats['events']; ?></h2>
            <p>Total Events</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-ticket"></i>
            <h2><?php echo $stats['bookings']; ?></h2>
            <p>Total Bookings</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-money-bill-wave"></i>
            <h2>RM <?php echo number_format($stats['revenue'], 2); ?></h2>
            <p>Revenue</p>
        </div>
        <div class="stat">
            <i class="fa-solid fa-users"></i>
            <h2><?php echo $stats['capacity']; ?></h2>
            <p>Total Capacity</p>
        </div>
    </section>

    <section style="padding: 0 8% 20px; width: 100%; box-sizing: border-box;">
        <a href="eventmanagement.php" class="submit-form-btn" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none;">Create New Event</a>
    </section>

    <section style="padding: 20px 8%; width: 100%; box-sizing: border-box;">
        <h2 style="text-align:left;">Event Overview</h2>
        <table style="width:100%;background:#fff;border-collapse:collapse;">
            <tr style="background:#f5f7fb;">
                <th style="padding:12px;text-align:left;">Event</th>
                <th style="padding:12px;text-align:left;">Category</th>
                <th style="padding:12px;text-align:left;">Date</th>
                <th style="padding:12px;text-align:left;">Booked</th>
                <th style="padding:12px;text-align:left;">Available</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($eventResult)) { ?>
            <tr>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['event_name']); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['category']); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['event_date']); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo (int)$row['booked']; ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo (int)$row['available']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </section>

    <section style="padding: 20px 8%; width: 100%; box-sizing: border-box;">
        <h2 style="text-align:left;">Recent Bookings</h2>
        <table style="width:100%;background:#fff;border-collapse:collapse;">
            <tr style="background:#f5f7fb;">
                <th style="padding:12px;text-align:left;">Customer</th>
                <th style="padding:12px;text-align:left;">Event</th>
                <th style="padding:12px;text-align:left;">Qty</th>
                <th style="padding:12px;text-align:left;">Payment</th>
                <th style="padding:12px;text-align:left;">Date</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($bookingResult)) { ?>
            <tr>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['event_name']); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo (int)$row['quantity']; ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;">RM <?php echo number_format((float)$row['total_payment'], 2); ?></td>
                <td style="padding:12px;border-bottom:1px solid #eee;"><?php echo htmlspecialchars($row['booking_date']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>
