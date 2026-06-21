<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to access this page.'); window.location.href='login.php';</script>";
    exit();
}

$loggedIn = true;

$stmt = $pdo->query("
SELECT *
FROM events
ORDER BY event_name
");

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

/* CREATE FEEDBACK */
if(isset($_POST['submit_feedback'])){

   $user_id = $_SESSION['user_id'];

    $event_id = $_POST['event_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    $stmt = $pdo->prepare("
    INSERT INTO feedback
    (event_id,user_id,rating,comments,feedback_date)
    VALUES (?,?,?,?,CURDATE())
    ");

    $stmt->execute([
        $event_id,
        $user_id,
        $rating,
        $comments
    ]);

    $message =
    "Feedback submitted successfully!";
}

if(isset($_POST['update_feedback'])){

    $feedback_id = $_POST['feedback_id'];

    $event_id = $_POST['event_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    $stmt = $pdo->prepare("
    UPDATE feedback
    SET
    event_id=?,
    rating=?,
    comments=?
    WHERE feedback_id=?
    ");

    $stmt->execute([
        $event_id,
        $rating,
        $comments,
        $feedback_id
    ]);

    $message =
    "Feedback updated successfully!";
}

/* DELETE */
if(isset($_GET['delete'])){

    $stmt =
    $pdo->prepare("
    DELETE FROM feedback
    WHERE feedback_id=?
    ");

    $stmt->execute([
        $_GET['delete']
    ]);

    header("Location: feedback.php");
    exit();
}

$editFeedback = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare("
    SELECT *
    FROM feedback
    WHERE feedback_id = ?
    ");

    $stmt->execute([
        $_GET['edit']
    ]);

    $editFeedback = $stmt->fetch();
}


/* AVERAGE RATING */
$avgStmt = $pdo->query("
SELECT AVG(rating) AS avg_rating
FROM feedback
");

$average =
$avgStmt->fetch();

/* READ FEEDBACK */
$feedbacks = $pdo->query("

SELECT
feedback.*,
events.event_name

FROM feedback

JOIN events
ON feedback.event_id = events.event_id

ORDER BY feedback_date DESC

")->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>EventEase Feedback</title>
    <link rel="stylesheet" href="mainstyle.css">
    <script src="feedback.js" defer></script>
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <i class="fa-solid fa-comments"></i>
        EventEase
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="event_listing.php">Events</a></li>
        <li><a href="booking.php" data-protect="true">Booking</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="eventmanagement.php">Event Management</a></li>
        <li><a href="feedback.php" class="active" data-protect="true">Feedback</a></li>
        <?php if ($loggedIn): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <h1>EVENT FEEDBACK</h1>
    <?php if(!empty($message)): ?>

<p style="color:green;text-align:center;">
<?= $message ?>
</p>

<?php endif; ?>
<img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200"
     class="feedback-banner"
     alt="Event Feedback">

<form
method="POST"
id="feedbackForm"
class="flexbox flex-column align-center">

<h2>Submit Feedback</h2>

<div class="flexbox flex-column">

<label>Select Event</label>

<select
name="event_id"
id="event_id">

<option value="">
Select Event
</option>

<?php foreach($events as $event): ?>

<option
value="<?= $event['event_id']; ?>"

<?php
if(
$editFeedback &&
$editFeedback['event_id']
==
$event['event_id']
)
echo "selected";
?>

>

<?= $event['event_name']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="flexbox flex-column">

<label>Rating</label>

<div class="rating-box">

<label>

<input
type="radio"
name="rating"
value="1"

<?= (
$editFeedback &&
$editFeedback['rating']==1
)
?
'checked'
:
'' ?>

>

⭐

</label>

<label>

<input
type="radio"
name="rating"
value="2"

<?= (
$editFeedback &&
$editFeedback['rating']==2
)
?
'checked'
:
'' ?>

>

⭐⭐

</label>

<label>

<input
type="radio"
name="rating"
value="3"

<?= (
$editFeedback &&
$editFeedback['rating']==3
)
?
'checked'
:
'' ?>

>

⭐⭐⭐

</label>

<label>

<input
type="radio"
name="rating"
value="4"

<?= (
$editFeedback &&
$editFeedback['rating']==4
)
?
'checked'
:
'' ?>

>

⭐⭐⭐⭐

</label>

<label>

<input
type="radio"
name="rating"
value="5"

<?= (
$editFeedback &&
$editFeedback['rating']==5
)
?
'checked'
:
'' ?>

>

⭐⭐⭐⭐⭐

</label>

</div>

</div>

<div class="flexbox flex-column">

<label>Comments</label>

<textarea
name="comments"
id="comments"
rows="5"><?= $editFeedback['comments'] ?? '' ?></textarea>

</div>

<p id="errorMessage"></p>

<?php if($editFeedback): ?>

<button
type="submit"
name="update_feedback"
class="submit-form-btn">

UPDATE FEEDBACK

</button>

<?php else: ?>

<button
type="submit"
name="submit_feedback"
class="submit-form-btn">

SUBMIT FEEDBACK

</button>

<?php endif; ?>

</form>

<div class="feedback-summary">

<h2>
Average Rating:
<?= round($average['avg_rating'] ?? 0,1); ?> ⭐
</h2>

</div>

<h2 style="text-align:center;">
Recent Feedback
</h2>

<?php foreach($feedbacks as $fb): ?>

<div class="feedback-card">

<p>
<strong>Event:</strong>
<?= htmlspecialchars($fb['event_name']); ?>
</p>

<p>
<strong>Rating:</strong>
<?= $fb['rating']; ?> ⭐
</p>

<p>
<?= htmlspecialchars($fb['comments']); ?>
</p>

<p>
<?= $fb['feedback_date']; ?>
</p>

<a href="feedback.php?edit=<?= $fb['feedback_id']; ?>">
Edit
</a>

|

<a href="feedback.php?delete=<?= $fb['feedback_id']; ?>">
Delete
</a>

</div>

<?php endforeach; ?>

</main>
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