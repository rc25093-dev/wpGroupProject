<?php
session_start();
require 'database.php';
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>EventEase</title>

<link rel="stylesheet" href="mainstyle.css">
<link rel="stylesheet" href="event_listing.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php
require 'database.php';



/* ==============================
   SEARCH & FILTER
============================== */

$search = "";
$category = "";

$sql = "
SELECT
    e.event_id,
    e.event_name,
    e.description,
    e.category,
    e.event_date,
    e.venue,
    e.ticket_price,
    e.capacity,
    IFNULL(SUM(b.quantity), 0) AS booked,
    MAX(ei.image_path) AS image
FROM events e
LEFT JOIN bookings b
ON e.event_id = b.event_id
LEFT JOIN event_images ei
ON e.event_id = ei.event_id
WHERE 1
";

if(isset($_GET['search']) && trim($_GET['search'])!=""){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " AND e.event_name LIKE '%$search%'";
}

if(isset($_GET['category']) && $_GET['category']!=""){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $sql .= " AND e.category='$category'";
}

$sql .= " GROUP BY e.event_id
ORDER BY e.event_date ASC";

$result = mysqli_query($conn, $sql);
$totalEvents = mysqli_num_rows($result);

$categoryResult = mysqli_query($conn,
"SELECT COUNT(DISTINCT category) total FROM events");
$totalCategory = mysqli_fetch_assoc($categoryResult)['total'];
?>

<nav class="navbar">

<div class="logo">

<i class="fa-solid fa-calendar-days"></i>

EventEase

</div>

<ul>
<li><a href="index.php">Home</a></li>
<li><a href="event_listing.php" class="active">Events</a></li>
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

<section class="hero">

<h1>

Discover Amazing Events

</h1>

<p>

Find exciting workshops, concerts, sports and more.

</p>

</section>

<section class="statistics">

<div class="stat">

<i class="fa-solid fa-calendar-check"></i>

<h2><?php echo $totalEvents;?></h2>

<p>Events</p>

</div>

<div class="stat">

<i class="fa-solid fa-layer-group"></i>

<h2><?php echo $totalCategory;?></h2>

<p>Categories</p>

</div>

<div class="stat">

<i class="fa-solid fa-percent"></i>

<h2>10%</h2>

<p>Early Bird</p>

</div>

<div class="stat">

<i class="fa-solid fa-ticket"></i>

<h2>100%</h2>

<p>Secure Booking</p>

</div>

</section>

<section class="search-box">

<form method="GET">

<input

type="text"

name="search"

placeholder="Search events..."

value="<?php echo $search;?>">

<select name="category">

<option value="">All Categories</option>

<option value="education"
<?php if($category=="education") echo "selected";?>
>Education</option>

<option value="sports"
<?php if($category=="sports") echo "selected";?>
>Sports</option>

<option value="entertainment"
<?php if($category=="entertainment") echo "selected";?>
>Entertainment</option>

</select>

<button type="submit">

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>

<a href="event_listing.php"

class="reset">

Reset

</a>

</form>

</section>

<section class="event-grid">

<?php
while($row = mysqli_fetch_assoc($result)){

    $capacity = (int)$row['capacity'];
    $booked = (int)$row['booked'];

    if($booked > $capacity){
        $booked = $capacity;
    }

    $remaining = $capacity - $booked;
    $percentage = ($capacity > 0) ? round(($booked / $capacity) * 100) : 0;
    $discount = round($row['ticket_price'] * 0.10, 2);
    $earlyBird = round($row['ticket_price'] - $discount, 2);

    if($remaining <= 0){
        $status = "Sold Out";
        $statusClass = "sold";
    } elseif($remaining <= 10){
        $status = "Almost Full";
        $statusClass = "almost";
    } else{
        $status = "Available";
        $statusClass = "available";
    }

    $imagePath = !empty($row['image']) ? 'eventimages/' . $row['image'] : 'eventimages/default.jpg';
?>

<div class="event-card">

    <div class="image-wrapper">

        <img
src="<?php echo $imagePath; ?>"
alt="<?php echo htmlspecialchars($row['event_name']);?>">

        <span class="badge">

            <?php echo $row['category'];?>

        </span>

        <span class="price-tag">

            RM <?php echo number_format($row['ticket_price'],2);?>

        </span>

    </div>

    <div class="event-body">

        <h2>

            <?php echo $row['event_name'];?>

        </h2>

        <div class="event-info">

            <p>

                <i class="fa-solid fa-calendar-days"></i>

                <?php echo date("d M Y", strtotime($row['event_date'])); ?>

            </p>

            <p>

                <i class="fa-solid fa-location-dot"></i>

                <?php echo htmlspecialchars($row['venue']); ?>

            </p>

        </div>

        <div class="seat-status <?php echo $statusClass;?>">

            <?php echo $status;?>

        </div>

        <div class="progress-container">

            <div class="progress-bar">

                <div
                    class="progress-fill"
                    style="width:<?php echo $percentage;?>%;">
                </div>

            </div>

            <small>

                <?php echo $percentage;?>% Booked

            </small>

        </div>

        <div class="card-footer">

            <div>

                <small>Early Bird</small>

                <h3>

                    RM <?php echo number_format($earlyBird,2);?>

                </h3>

            </div>

            <button
            type="button"
            class="details-btn"
            data-id="<?php echo $row['event_id'];?>"

            data-image="<?php echo $imagePath; ?>"

            data-name="<?php echo htmlspecialchars($row['event_name']);?>"

            data-category="<?php echo htmlspecialchars($row['category']);?>"

            data-date="<?php echo date('Y-m-d', strtotime($row['event_date'])); ?>"

            data-time="18:00"

            data-venue="<?php echo htmlspecialchars($row['venue']);?>"

            data-description="<?php echo htmlspecialchars($row['description']);?>"

            data-price="<?php echo number_format($row['ticket_price'],2);?>"

            data-discount="<?php echo number_format($discount,2);?>"

            data-early="<?php echo number_format($earlyBird,2);?>"

            data-capacity="<?php echo $capacity; ?>"

            data-booked="<?php echo $booked;?>"

            data-remaining="<?php echo $remaining;?>"

            data-progress="<?php echo $percentage;?>">

            <i class="fa-solid fa-eye"></i>

                View Details

            </button>

        </div>

    </div>

</div>

<?php } ?>

</section>

<!-- ==========================================
EVENT DETAILS MODAL
========================================== -->

<div id="eventModal" class="modal">

    <div class="modal-content">

        <span class="close">&times;</span>

        <img id="modalImage" class="modal-image">

        <div class="modal-header">

            <span id="modalCategory" class="modal-category"></span>

            <h2 id="modalTitle"></h2>

        </div>

        <div class="modal-grid">

            <div class="modal-item">
                <i class="fa-solid fa-calendar-days"></i>
                <div>
                    <small>Date</small>
                    <h4 id="modalDate"></h4>
                </div>
            </div>

            <div class="modal-item">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <small>Time</small>
                    <h4 id="modalTime"></h4>
                </div>
            </div>

            <div class="modal-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <small>Venue</small>
                    <h4 id="modalVenue"></h4>
                </div>
            </div>

        </div>

        <div class="price-section">

            <div class="price-card">

                <small>Original Price</small>

                <h2>

                    RM <span id="modalPrice"></span>

                </h2>

            </div>

            <div class="price-card early">

                <small>Early Bird</small>

                <h2>

                    RM <span id="modalEarly"></span>

                </h2>

            </div>

            <div class="price-card save">

                <small>You Save</small>

                <h2>

                    RM <span id="modalDiscount"></span>

                </h2>

            </div>

        </div>

        <div class="description-box">

            <h3>

                About this Event

            </h3>

            <p id="modalDescription"></p>

        </div>

        <div class="seat-wrapper">

            <div class="seat-card">

                <h2 id="modalCapacity"></h2>

                <small>Total Seats</small>

            </div>

            <div class="seat-card">

                <h2 id="modalBooked"></h2>

                <small>Booked</small>

            </div>

            <div class="seat-card">

                <h2 id="modalRemaining"></h2>

                <small>Remaining</small>

            </div>

        </div>

        <h3>

            Booking Progress

        </h3>

        <div class="progress">

            <div id="progressFill" class="progress-fill"></div>

        </div>

        <p id="progressText"></p>

        <h3>

            Event Starts In

        </h3>

        <div class="countdown">

            <div>

                <h2 id="days">00</h2>

                <small>Days</small>

            </div>

            <div>

                <h2 id="hours">00</h2>

                <small>Hours</small>

            </div>

            <div>

                <h2 id="minutes">00</h2>

                <small>Minutes</small>

            </div>

        </div>

       <a id="bookButton" href="booking.php">

            <button class="book-btn">

                <i class="fa-solid fa-ticket"></i>

                Book Now

            </button>

        </a>

    </div>

</div>

<script src="event.js"></script>
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

