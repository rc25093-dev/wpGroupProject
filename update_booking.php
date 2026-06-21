<?php

require 'database.php';

$id=(int)$_POST['booking_id'];

$name=mysqli_real_escape_string(
$conn,
$_POST['customer_name']
);

$qty=(int)$_POST['quantity'];

$query=mysqli_query($conn,"
SELECT
ticket_price
FROM events
JOIN bookings
ON events.event_id=bookings.event_id
WHERE booking_id=$id
");

$data=mysqli_fetch_assoc($query);

$total=$qty*$data['ticket_price'];

mysqli_query($conn,"
UPDATE bookings

SET

customer_name='$name',

quantity='$qty',

total_payment='$total'

WHERE booking_id=$id
");

header("Location: booking.php");

exit();

?>