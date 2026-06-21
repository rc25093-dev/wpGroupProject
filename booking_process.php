<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = mysqli_real_escape_string($conn, trim($_POST['customer_name']));
    $event_id = (int)$_POST['event_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    if ($quantity < 1) {
        die('Quantity must be at least 1.');
    }

    $eventQuery = mysqli_query($conn, "
        SELECT
            e.ticket_price,
            e.capacity,
            IFNULL(SUM(b.quantity), 0) AS booked
        FROM events e
        LEFT JOIN bookings b
        ON e.event_id = b.event_id
        WHERE e.event_id = '$event_id'
        GROUP BY e.event_id
    ");

    if (mysqli_num_rows($eventQuery) == 0) {
        die('Event not found.');
    }

    $event = mysqli_fetch_assoc($eventQuery);
    $price = (float)$event['ticket_price'];
    $capacity = (int)$event['capacity'];
    $booked = (int)$event['booked'];
    $available = $capacity - $booked;

    if ($quantity > $available) {
        echo "<script>
            alert('Only $available seat(s) available.');
            window.location='booking.php';
        </script>";
        exit();
    }

    $subtotal = $price * $quantity;
    $discount = ($quantity >= 3) ? ($subtotal * 0.10) : 0;
    $service_fee = 2.00;
    $total_payment = ($subtotal - $discount) + $service_fee;

    mysqli_query($conn, "
        INSERT INTO bookings
        (
            user_id,
            event_id,
            customer_name,
            quantity,
            total_payment
        )
        VALUES
        (
            '$user_id',
            '$event_id',
            '$customer_name',
            '$quantity',
            '$total_payment'
        )
    ");

    echo "<script>
        alert('Booking Successful!');
        window.location='booking.php';
    </script>";
}
?>