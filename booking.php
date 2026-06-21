<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to access this page.'); window.location.href='login.php';</script>";
    exit();
}

$loggedIn = true;

$events = mysqli_query($conn, "
SELECT
    e.event_id,
    e.event_name,
    e.ticket_price,
    e.capacity,
    IFNULL(SUM(b.quantity), 0) AS booked,
    (e.capacity - IFNULL(SUM(b.quantity), 0)) AS available
FROM events e
LEFT JOIN bookings b
ON e.event_id = b.event_id
GROUP BY e.event_id
");
?>

<!DOCTYPE html>
<html>
<head>

<title>EventEase Booking</title>

<link rel="stylesheet" href="mainstyle.css">
<link rel="stylesheet" href="booking.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<nav class="navbar">

<div class="logo">
<i class="fa-solid fa-ticket"></i>
EventEase
</div>

<ul>
<li><a href="index.php">Home</a></li>
<li><a href="event_listing.php">Events</a></li>
<li><a href="booking.php" class="active" data-protect="true">Booking</a></li>
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

<section class="hero">

<h1>Book Your Event</h1>

<p>
Secure your seat before it's sold out.
</p>

</section>

<section class="table-section">

<h2>Available Events</h2>

<table>

<tr>

<th>Event</th>
<th>Price</th>
<th>Available Seats</th>

</tr>

<?php while($event=mysqli_fetch_assoc($events)){ ?>

<tr>

<td>
<?php echo $event['event_name']; ?>
</td>

<td>
RM <?php echo number_format($event['ticket_price'],2); ?>
</td>

<td>

<?php echo $event['available']; ?>

</td>

</tr>

<?php } ?>

</table>

</section>

<section class="booking-container">

<h2>Booking Form</h2>

<form
action="booking_process.php"
method="POST"
id="bookingForm">

<label>Customer Name</label>

<input
type="text"
name="customer_name"
required>

<label>Select Event</label>

<select
name="event_id"
id="eventSelect"
required>

<option value="">
Choose Event
</option>

<?php

$eventList = mysqli_query($conn, "
SELECT
    e.event_id,
    e.event_name,
    e.ticket_price,
    e.capacity,
    IFNULL(SUM(b.quantity), 0) AS booked,
    (e.capacity - IFNULL(SUM(b.quantity), 0)) AS available
FROM events e
LEFT JOIN bookings b
ON e.event_id = b.event_id
GROUP BY e.event_id
");


while($row=mysqli_fetch_assoc($eventList)){

    if($row['available'] > 0){
?>

<option
value="<?php echo $row['event_id']; ?>"
data-price="<?php echo $row['ticket_price']; ?>"
data-seat="<?php echo $row['available']; ?>">

<?php echo $row['event_name']; ?>

(<?php echo $row['available']; ?> seats left)

</option>

<?php
    }else{
?>

<option disabled>

<?php echo $row['event_name']; ?>

(Sold Out)

</option>

<?php
    }

}
?>

</select>

<label>Quantity</label>

<input
type="number"
id="quantity"
name="quantity"
min="1"
required>

<h3>Booking Calculation</h3>

<div class="calculation-grid">

<div>

<label>Ticket Price</label>

<input
type="text"
id="priceDisplay"
readonly>

</div>

<div>

<label>Remaining Seats</label>

<input
type="text"
id="seatAvailable"
readonly>

</div>

<div>

<label>Total Payment</label>

<input
type="text"
id="total"
readonly>

</div>

<div>

<label>Early Bird Discount (10%)</label>

<input
type="text"
id="discount"
readonly>

</div>

<div>

<label>Service Fee</label>

<input
type="text"
id="service"
readonly>

</div>

<div>

<label>Final Payment</label>

<input
type="text"
id="finalTotal"
readonly>

</div>

</div>

<button type="submit">

<i class="fa-solid fa-ticket"></i>

Book Now

</button>

</form>

</section>

<section class="record-section">

<h2>Booking Records</h2>

<table>

<tr>

<th>Name</th>
<th>Event</th>
<th>Quantity</th>
<th>Total</th>
<th>Action</th>

</tr>

<?php

$records = mysqli_query($conn, "
SELECT
    bookings.booking_id,
    bookings.customer_name,
    bookings.quantity,
    bookings.total_payment,
    events.event_name
FROM bookings
JOIN events
ON bookings.event_id = events.event_id
ORDER BY bookings.booking_id DESC
");

while($booking=mysqli_fetch_assoc($records)){

?>

<tr>

<td>

<?php echo $booking['customer_name']; ?>

</td>

<td>

<?php echo $booking['event_name']; ?>

</td>

<td>

<?php echo $booking['quantity']; ?>

</td>

<td>

RM <?php echo number_format($booking['total_payment'],2); ?>

</td>

<td>

<a
class="edit-btn"
href="edit_booking.php?id=<?php echo $booking['booking_id']; ?>">
Edit
</a>

<a
class="delete-btn"
href="delete_booking.php?id=<?php echo $booking['booking_id']; ?>"
onclick="return confirm('Delete booking?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</section>


<script src="booking.js"></script>
</body>
</html>