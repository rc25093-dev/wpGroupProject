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
    <style>
        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        .feedback-banner-wrap {
            width: 100%;
            margin: 18px 0 24px;
        }

        .feedback-banner {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            display: block;
            border-radius: 18px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
        }

        #feedbackForm {
            width: min(760px, 100%);
            margin: 0 auto 28px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        #feedbackForm h2 {
            margin: 0 0 18px;
            text-align: center;
            color: #1d3557;
        }

        #feedbackForm label {
            font-weight: 600;
            color: #1d3557;
        }

        #feedbackForm select,
        #feedbackForm textarea {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            box-sizing: border-box;
        }

        .rating-box {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .rating-box label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 1rem;
            color: #f59e0b;
            cursor: pointer;
        }

        .rating-box input {
            accent-color: #f59e0b;
        }

        #errorMessage {
            min-height: 22px;
            margin: 0;
            color: #dc2626;
            font-size: 0.9rem;
            text-align: center;
        }

        .feedback-summary {
            background: linear-gradient(90deg, #eef5ff, #f8fbff);
            border-radius: 14px;
            padding: 18px 22px;
            text-align: center;
            margin: 0 auto 30px;
            max-width: 420px;
            box-shadow: inset 0 0 0 1px #dfe9ff;
        }

        .feedback-summary h2 {
            margin: 0;
            color: #1d3557;
            font-size: 1.3rem;
        }

        .feedback-list {
            display: grid;
            gap: 18px;
        }

        .feedback-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        }

        .feedback-card p {
            margin: 8px 0;
            color: #334155;
        }

        .feedback-card strong {
            color: #1d3557;
        }

        .feedback-card a {
            color: #2c5d9d;
            font-weight: 600;
            text-decoration: none;
        }

        .feedback-card a:hover {
            text-decoration: underline;
        }
    </style>
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
<input
type="hidden"
name="feedback_id"
value="<?= $editFeedback['feedback_id']; ?>">
<?php endif; ?>

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

<div class="feedback-list">
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
</div>

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