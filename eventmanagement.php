<?php
require 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['eventname'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $event_date = $_POST['eventdate'];
    $event_venue = $_POST['venue'];
    $ticket_price = $_POST['ticketprice'];
    $event_capacity = $_POST['eventcapacity'];

    $stmt = $pdo->prepare("INSERT INTO events (event_name, description, category, event_date, venue, ticket_price, capacity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $category, $event_date, $event_venue, $ticket_price, $event_capacity]);
    $event_id = $pdo->lastInsertId();

    if (isset($_FILES['eventimage'])) {
        $target_dir = "eventimages/";
        $file_name = time() . "_" . basename($_FILES["eventimage"]["name"]);
        move_uploaded_file($_FILES["eventimage"]["tmp_name"], $target_dir . $file_name);
        
        $stmt = $pdo->prepare("INSERT INTO event_images (event_id, image_path) VALUES (?, ?)");
        $stmt->execute([$event_id, $file_name]);
    }
    echo "<script>alert('Event created successfully!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EventEase Event Management</title>
    <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="eventmanagement.css">
    <script src="feedback.js"></script>

    <style>
        .event-mgmt-form{
            width:18vw;
        }

        form.flexbox{
            width: 60vw;
            margin: auto;
        }

        .flex-row{
            gap:1vw;
        }
    </style>
</head>

<body>
    <nav>
        <a href="#">Home</a>
        <a href="#">Events</a>
        <a href="#">Booking</a>
        <a href="#">Dashboard</a>
        <a href="#">Feedback</a>
    </nav>

    <main>

        <h1>EVENT MANAGEMENT</h1>

        <form action="eventmanagement.php" method="POST" class="flexbox flex-column align-center" enctype="multipart/form-data">

            <h2 style="align-self: flex-start; margin-left: 30px;">Create New Event</h2>

            <div class="flexbox flex-row align-center">

            <div class="flexbox flex-column align-center">
            <div class="flexbox flex-column event-mgmt-form">
            <label for="eventname">Event Name</label>
            <input type="text" id="eventname" name="eventname" placeholder="Enter event name..." required>
            </div>

            <div class="flexbox flex-column event-mgmt-form">
            <label for="eventdate">Event Date</label>
            <input type="date" id="eventdate" name="eventdate" placeholder="YYYY-MM-DD" required>
            </div>

            <div class="flexbox flex-column event-mgmt-form">
            <label for="venue">Event Venue</label>
            <input type="text" id="venue" name="venue" placeholder="Enter event venue..." required>
            </div>
            </div>

            <div class="flexbox flex-column align-center">
            <div class="flexbox flex-column event-mgmt-form">
            <label for="ticketprice">Price per Entry</label>
            <input type="number" id="ticketprice" name="ticketprice" placeholder="Enter price in RM..." min="0" step="0.01" required>
            </div>

            <div class="flexbox flex-column event-mgmt-form">
            <label for="eventcapacity">Event Capacity</label>
            <input type="number" id="eventcapacity" name="eventcapacity" placeholder="Enter participant amount limit..." min="0" step="1" required>
            </div>

            <div class="flexbox flex-column event-mgmt-form">
            <label for="category">Event Category</label>
            <select id="category" name="category" required>
                <option value="">Select category...</option>
                <option value="education">Education</option>
                <option value="sports">Sports</option>
                <option value="entertainment">Entertainment</option>
                <option value="lecture">Lecture</option>
                <option value="workshop">Workshop</option>
                <option value="social">Social</option>
                <option value="fairexhibition">Fair/Exhibition</option>
                <option value="others">Others</option>
            </select>
            </div>
            
            </div>

            </div>

            <div class="flexbox flex-column">
            <div class="flexbox flex-column event-mgmt-form" style="width: 37vw; margin-top: 20px;">
            <label for="description">Event Description</label>
            <textarea id="description" name="description" placeholder="Add a description for your event..." required></textarea>
            </div>

            <div class="flexbox flex-column event-mgmt-form" style="width: 37vw;">
            <label for="eventimage">Upload Display Image for your Event</label> 
            <input type="file" id="eventimage" name="eventimage" accept="image/*" required>
            </div>
            </div>

            <button type="submit" class="submit-form-btn" style="align-self: flex-end; margin-right: 30px;">CREATE EVENT</button>

        </form>

    </main>

    <footer>
        <p>&copy; 2026 EventEase. All Rights Reserved.</p>
    </footer>

</body>
</html>