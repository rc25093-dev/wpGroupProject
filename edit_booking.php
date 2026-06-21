<?php
require 'database.php';

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT
bookings.*,
events.ticket_price,
events.event_name
FROM bookings
JOIN events
ON bookings.event_id=events.event_id
WHERE booking_id=$id
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Booking</title>

<link rel="stylesheet" href="booking.css">

</head>

<body>

<div class="booking-container">

<h2>Edit Booking</h2>

<form action="update_booking.php" method="POST">

<input
type="hidden"
name="booking_id"
value="<?php echo $data['booking_id']; ?>">

<label>Name</label>

<input
type="text"
name="customer_name"
value="<?php echo $data['customer_name']; ?>"
required>

<label>Event</label>

<input
type="text"
value="<?php echo $data['event_name']; ?>"
readonly>

<label>Quantity</label>

<input
type="number"
name="quantity"
id="quantity"
value="<?php echo $data['quantity']; ?>"
min="1"
required>

<label>Total Payment</label>

<input
type="text"
id="total"
readonly>

<button>

Update Booking

</button>

</form>

</div>

<script>

const qty=document.getElementById("quantity");

const total=document.getElementById("total");

const price=<?php echo $data['ticket_price']; ?>;

function calculate(){

total.value="RM "+(price*qty.value).toFixed(2);

}

calculate();

qty.addEventListener("input",calculate);

</script>

</body>

</html>