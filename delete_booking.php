<?php

require 'database.php';

$id=(int)$_GET['id'];

mysqli_query($conn,"
DELETE FROM bookings
WHERE booking_id=$id
");

header("Location: booking.php");

exit();

?>