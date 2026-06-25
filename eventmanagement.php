<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to access this page.'); window.location.href='login.php';</script>";
    exit();
}

$loggedIn = true;
$userId = (int) $_SESSION['user_id'];
$message = '';
$messageType = 'success';

function deleteEventImages($eventId)
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT image_path FROM event_images WHERE event_id = ?');
    $stmt->execute([$eventId]);
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($images as $imagePath) {
        if ($imagePath && file_exists('eventimages/' . $imagePath)) {
            unlink('eventimages/' . $imagePath);
        }
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $eventId = (int) $_GET['delete'];

    deleteEventImages($eventId);

    $stmt = $pdo->prepare('DELETE FROM event_images WHERE event_id = ?');
    $stmt->execute([$eventId]);

    $stmt = $pdo->prepare('DELETE FROM events WHERE event_id = ? AND user_id = ?');
    $stmt->execute([$eventId, $userId]);

    echo "<script>alert('Event deleted successfully!'); window.location.href='eventmanagement.php';</script>";
    exit();
}

$editingEvent = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = $pdo->prepare('SELECT e.*, (SELECT ei.image_path FROM event_images ei WHERE ei.event_id = e.event_id ORDER BY ei.image_id LIMIT 1) AS image_path FROM events e WHERE e.event_id = ? AND e.user_id = ?');
    $stmt->execute([$editId, $userId]);
    $editingEvent = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action']) && $_POST['form_action'] === 'save_event') {
    $eventId = !empty($_POST['event_id']) ? (int) $_POST['event_id'] : 0;
    $eventName = trim($_POST['eventname'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $eventDate = trim($_POST['eventdate'] ?? '');
    $venue = trim($_POST['venue'] ?? '');
    $ticketPrice = (float) ($_POST['ticketprice'] ?? 0);
    $capacity = (int) ($_POST['eventcapacity'] ?? 0);
    $errors = [];

    if ($eventName === '') {
        $errors[] = 'Event name is required.';
    }
    if ($description === '') {
        $errors[] = 'Event description is required.';
    }
    if ($category === '' || !in_array($category, ['education', 'sports', 'entertainment'], true)) {
        $errors[] = 'Please choose a valid category.';
    }
    if ($eventDate === '' || strtotime($eventDate) === false) {
        $errors[] = 'Please enter a valid event date.';
    }
    if ($venue === '') {
        $errors[] = 'Event venue is required.';
    }
    if ($ticketPrice < 0) {
        $errors[] = 'Ticket price cannot be negative.';
    }
    if ($capacity < 1) {
        $errors[] = 'Capacity must be at least 1.';
    }

    $hasNewImage = isset($_FILES['eventimage']) && $_FILES['eventimage']['error'] !== UPLOAD_ERR_NO_FILE;
    if ($eventId === 0 && !$hasNewImage) {
        $errors[] = 'Please upload an event image.';
    }

    if ($hasNewImage && $_FILES['eventimage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['eventimage']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes, true)) {
            $errors[] = 'Only JPG, PNG, WEBP, and GIF images are allowed.';
        }

        if ($_FILES['eventimage']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Please upload an image smaller than 2MB.';
        }
    }

    if (empty($errors)) {
        if ($eventId > 0) {
            $stmt = $pdo->prepare('UPDATE events SET event_name = ?, description = ?, category = ?, event_date = ?, venue = ?, ticket_price = ?, capacity = ? WHERE event_id = ? AND user_id = ?');
            $stmt->execute([$eventName, $description, $category, $eventDate, $venue, $ticketPrice, $capacity, $eventId, $userId]);
            $message = 'Event updated successfully!';
        } else {
            $stmt = $pdo->prepare('INSERT INTO events (user_id, event_name, description, category, event_date, venue, ticket_price, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$userId, $eventName, $description, $category, $eventDate, $venue, $ticketPrice, $capacity]);
            $eventId = (int) $pdo->lastInsertId();
            $message = 'Event created successfully!';
        }

        if ($hasNewImage && $_FILES['eventimage']['error'] === UPLOAD_ERR_OK) {
            $targetDir = 'eventimages/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['eventimage']['name']);
            $targetPath = $targetDir . $fileName;
            move_uploaded_file($_FILES['eventimage']['tmp_name'], $targetPath);

            if ($eventId > 0) {
                $stmt = $pdo->prepare('SELECT image_path FROM event_images WHERE event_id = ?');
                $stmt->execute([$eventId]);
                $oldImages = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($oldImages as $oldImage) {
                    if ($oldImage && file_exists($targetDir . $oldImage)) {
                        unlink($targetDir . $oldImage);
                    }
                }

                $stmt = $pdo->prepare('DELETE FROM event_images WHERE event_id = ?');
                $stmt->execute([$eventId]);
            }

            $stmt = $pdo->prepare('INSERT INTO event_images (event_id, image_path) VALUES (?, ?)');
            $stmt->execute([$eventId, $fileName]);
        }

        echo "<script>alert('" . addslashes($message) . "'); window.location.href='eventmanagement.php';</script>";
        exit();
    }

    $message = implode(' ', $errors);
    $messageType = 'error';
}

$stmt = $pdo->prepare('SELECT COUNT(*) FROM events WHERE user_id = ?');
$stmt->execute([$userId]);
$totalEvents = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COALESCE(SUM(b.total_payment), 0) FROM bookings b JOIN events e ON e.event_id = b.event_id WHERE e.user_id = ?');
$stmt->execute([$userId]);
$totalRevenue = (float) $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM bookings b JOIN events e ON e.event_id = b.event_id WHERE e.user_id = ?');
$stmt->execute([$userId]);
$totalBookings = (int) $stmt->fetchColumn();

$revenuePerAttendee = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

$stmt = $pdo->prepare('SELECT e.event_id, e.event_name, e.event_date, e.venue, e.ticket_price, e.capacity, (SELECT ei.image_path FROM event_images ei WHERE ei.event_id = e.event_id ORDER BY ei.image_id LIMIT 1) AS image_path FROM events e WHERE e.user_id = ? ORDER BY e.event_date DESC, e.event_id DESC');
$stmt->execute([$userId]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase Event Management</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="eventmanagement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fa-solid fa-calendar-days"></i>
            EventEase
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="event_listing.php">Events</a></li>
            <li><a href="booking.php" data-protect="true">Booking</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="eventmanagement.php" class="active">Event Management</a></li>
            <li><a href="feedback.php" data-protect="true">Feedback</a></li>
            <?php if ($loggedIn): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="evt-page">
        <?php if (!empty($message)): ?>
            <div class="evt-message <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <section class="evt-banner">
            <div>
                <h1>Event Management</h1>
                <p>Create, manage, and keep track of the events you host.</p>
            </div>
            <button type="button" class="evt-create-btn" id="openCreateModalBtn"><i class="fa-solid fa-plus"></i> Create Event</button>
        </section>

        <section class="evt-stats">
            <div class="evt-stat-card">
                <h3><?= $totalEvents ?></h3>
                <p>Your Total Events</p>
            </div>
            <div class="evt-stat-card">
                <h3>RM <?= number_format($totalRevenue, 2) ?></h3>
                <p>Your Revenue</p>
            </div>
            <div class="evt-stat-card">
                <h3>RM <?= number_format($revenuePerAttendee, 2) ?></h3>
                <p>Revenue Per Attendee</p>
            </div>
        </section>

        <section>
            <div class="evt-section-title">
                <h2>Your Events</h2>
            </div>

            <?php if (!empty($events)): ?>
                <div class="evt-gallery">
                    <?php foreach ($events as $event): ?>
                        <div class="evt-card">
                            <?php if (!empty($event['image_path'])): ?>
                                <img src="eventimages/<?= htmlspecialchars($event['image_path']) ?>" alt="<?= htmlspecialchars($event['event_name']) ?>">
                            <?php else: ?>
                                <img src="eventimages/default.jpg" alt="Default event image">
                            <?php endif; ?>
                            <div class="evt-card-body">
                                <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                                <div class="evt-meta"><i class="fa-solid fa-calendar-days"></i> <?= htmlspecialchars($event['event_date']) ?></div>
                                <div class="evt-meta"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($event['venue']) ?></div>
                                <div class="evt-meta"><i class="fa-solid fa-tag"></i> RM <?= number_format((float)$event['ticket_price'], 2) ?></div>
                                <div class="evt-card-actions">
                                    <a class="evt-edit-btn" href="eventmanagement.php?edit=<?= (int)$event['event_id'] ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <a class="evt-delete-btn" href="eventmanagement.php?delete=<?= (int)$event['event_id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');"><i class="fa-solid fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="evt-empty">
                    You have not created any events yet. Create one to get started.
                </div>
            <?php endif; ?>
        </section>
    </main>

    <div class="evt-modal-overlay <?= $editingEvent ? 'is-open' : '' ?>" id="eventModalOverlay">
        <div class="evt-modal-card">
            <button class="evt-close-btn" type="button" id="closeModalBtn" aria-label="Close modal">
                <img src="close.png" alt="Close">
            </button>
            <h2><?= $editingEvent ? 'Update Event' : 'Create New Event' ?></h2>
            <form action="eventmanagement.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_action" value="save_event">
                <input type="hidden" name="event_id" value="<?= $editingEvent ? (int)$editingEvent['event_id'] : '' ?>">

                <div class="evt-form-grid">
                    <div class="evt-form-group">
                        <label for="eventname">Event Name</label>
                        <input type="text" id="eventname" name="eventname" value="<?= htmlspecialchars($editingEvent['event_name'] ?? '') ?>" required>
                    </div>

                    <div class="evt-form-group">
                        <label for="eventdate">Event Date</label>
                        <input type="date" id="eventdate" name="eventdate" value="<?= htmlspecialchars($editingEvent['event_date'] ?? '') ?>" required>
                    </div>

                    <div class="evt-form-group">
                        <label for="venue">Event Venue</label>
                        <input type="text" id="venue" name="venue" value="<?= htmlspecialchars($editingEvent['venue'] ?? '') ?>" required>
                    </div>

                    <div class="evt-form-group">
                        <label for="ticketprice">Price per Entry</label>
                        <input type="number" id="ticketprice" name="ticketprice" min="0" step="0.01" value="<?= htmlspecialchars($editingEvent['ticket_price'] ?? '') ?>" required>
                    </div>

                    <div class="evt-form-group">
                        <label for="eventcapacity">Event Capacity</label>
                        <input type="number" id="eventcapacity" name="eventcapacity" min="1" step="1" value="<?= htmlspecialchars($editingEvent['capacity'] ?? '') ?>" required>
                    </div>

                    <div class="evt-form-group">
                        <label for="category">Event Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select category...</option>
                            <option value="education" <?= (($editingEvent['category'] ?? '') === 'education') ? 'selected' : '' ?>>Education</option>
                            <option value="sports" <?= (($editingEvent['category'] ?? '') === 'sports') ? 'selected' : '' ?>>Sports</option>
                            <option value="entertainment" <?= (($editingEvent['category'] ?? '') === 'entertainment') ? 'selected' : '' ?>>Entertainment</option>
                        </select>
                    </div>

                    <div class="evt-form-group full">
                        <label for="description">Event Description</label>
                        <textarea id="description" name="description" required><?= htmlspecialchars($editingEvent['description'] ?? '') ?></textarea>
                    </div>

                    <div class="evt-form-group full">
                        <label for="eventimage">Upload Display Image</label>
                        <input type="file" id="eventimage" name="eventimage" accept="image/*" <?= $editingEvent ? '' : 'required' ?>>
                        <div class="evt-preview">
                            <?php if (!empty($editingEvent['image_path'])): ?>
                                <img id="eventImagePreview" src="eventimages/<?= htmlspecialchars($editingEvent['image_path']) ?>" alt="Preview">
                            <?php else: ?>
                                <img id="eventImagePreview" src="" alt="Preview" style="display:none;">
                            <?php endif; ?>
                            <div id="imagePreviewLabel"><?= $editingEvent ? 'Current image will be replaced if you choose a new one.' : 'Selected image will appear here.' ?></div>
                        </div>
                    </div>
                </div>

                <div class="evt-actions-row">
                    <button type="button" class="evt-delete-btn" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="evt-submit-btn"><?= $editingEvent ? 'Update Event' : 'Create Event' ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.eventManagementLoggedIn = <?= json_encode($loggedIn) ?>;
    </script>
    <script src="eventmanagement.js"></script>
</body>
</html>