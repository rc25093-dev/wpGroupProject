<?php
session_start();
require_once 'database.php';

// 1. Process Aggregations for Stats Cards
try {
    $totalEvents  = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $totalTickets = $pdo->query("SELECT COALESCE(SUM(capacity), 0) FROM events")->fetchColumn() ?? 0;
    $totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_payment), 0) FROM bookings")->fetchColumn() ?? 0;
    
    // Fetch all records for the main listing grid
    $events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
} catch (PDOException $e) {
    $totalEvents = $totalTickets = $totalRevenue = 0;
    $events = [];
}

// 2. Fallback check for the hero-banner picture matrix
$previewImage = 'music.jpg.jpeg';
if (!empty($events)) {
    try {
        $imgStmt = $pdo->prepare("SELECT image_path FROM event_images WHERE event_id = ? LIMIT 1");
        $imgStmt->execute([$events[0]['event_id']]);
        $imgRow = $imgStmt->fetch();
        if ($imgRow && !empty($imgRow['image_path'])) {
            $previewImage = 'eventimages/' . htmlspecialchars($imgRow['image_path']);
        }
    } catch (PDOException $e) {}
}
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase - Dashboard</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f0f4f8; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
        .dashboard-hero { background: linear-gradient(90deg, #1d3557, #457b9d); color: #fff; padding: 60px 6% 120px 6%; display: flex; align-items: center; justify-content: space-between; }
        .dashboard-hero h1 { font-size: 2.2rem; }
        .dashboard-hero p { margin-top: 8px; color: #dfeaff; }
        
        .hero-actions-group { display: flex; gap: 12px; align-items: center; }
        @media(max-width: 768px) { .hero-actions-group { flex-direction: column; align-items: stretch; width: 100%; margin-top: 15px; } }
        
        .dashboard-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: linear-gradient(90deg, #f8b400, #ff7a59); color: #12213f; text-decoration: none; font-weight: 700; padding: 12px 24px; border-radius: 999px; box-shadow: 0 8px 18px rgba(248, 180, 0, 0.3); }
        .btn-report { background: linear-gradient(90deg, #2c5d9d, #1d3557); color: #fff; box-shadow: 0 8px 18px rgba(29, 53, 87, 0.3); }
        
        .dashboard-panel { background: #fff; border-radius: 18px; padding: 28px; margin: -60px auto 40px auto; width: min(1180px, 92%); box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12); }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px; margin-bottom: 30px; }
        .stat-box { background: #f8fbff; border-radius: 16px; padding: 22px; display: flex; align-items: center; gap: 14px; border: 1px solid #eef2f7; }
        .revenue-box { background: #2c3e50; color: white; border: none; }
        .stat-icon { font-size: 1.8rem; color: #2c5d9d; }
        .revenue-box .stat-icon { color: #f8b400; }
        .stat-info h3 { font-size: 1.8rem; color: #1d3557; }
        .revenue-box .stat-info h3 { color: #fff; }
        
        .dashboard-content-layout { display: grid; grid-template-columns: minmax(280px, 320px) 1fr; gap: 28px; }
        @media(max-width: 768px) { .dashboard-content-layout { grid-template-columns: 1fr; } }
        .upcoming-event-img-container img { width: 100%; border-radius: 12px; object-fit: cover; max-height: 280px; }
        
        .table-title { color: #1d3557; font-size: 1.2rem; font-weight: 700; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px; border-bottom: 1px solid #eef2f7; text-align: left; }
        th { background: #f8fbff; color: #1d3557; }
        
        footer { margin-top: auto; background-color: #1d3557; color: white; text-align: center; padding: 15px; }
    </style>
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
        <li><a href="booking.php">Booking</a></li>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="eventmanagement.php">Event Management</a></li>
        <li><a href="feedback.php">Feedback</a></li>
        <?php if ($loggedIn): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

    <section class="dashboard-hero">
        <div>
            <h1>EventEase Organizer Dashboard</h1>
            <p>Stay on top of your events, bookings, and next steps.</p>
        </div>
        <div class="hero-actions-group">
            <a href="generate_report.php" class="dashboard-action-btn btn-report">
                <i class="fa-solid fa-file-invoice-dollar"></i> Generate Report
            </a>
            <a href="eventmanagement.php?action=add" class="dashboard-action-btn">
                <i class="fa-solid fa-plus"></i> Create New Event
            </a>
        </div>
    </section>

    <main>
        <section class="dashboard-panel">
            <div class="stats-row">
                <div class="stat-box">
                    <i class="fa-solid fa-calendar-days stat-icon"></i>
                    <div class="stat-info"><h3><?php echo number_format($totalEvents); ?></h3><p>Total Events</p></div>
                </div>
                <div class="stat-box">
                    <i class="fa-solid fa-ticket stat-icon"></i>
                    <div class="stat-info"><h3><?php echo number_format($totalTickets); ?></h3><p>Total Capacity</p></div>
                </div>
                <div class="stat-box revenue-box">
                    <i class="fa-solid fa-dollar-sign stat-icon"></i>
                    <div class="stat-info"><h3>RM <?php echo number_format($totalRevenue, 2); ?></h3><p>Total Revenue</p></div>
                </div>
            </div>

            <div class="dashboard-content-layout">
                <div class="upcoming-event-img-container">
                    <img src="<?php echo htmlspecialchars($previewImage); ?>" alt="Preview">
                </div>

                <div>
                    <div class="table-title">Event Status Overview</div>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($events)): foreach ($events as $row): 
                                    $evTime = strtotime($row['event_date']);
                                    $today = strtotime(date('Y-m-d'));
                                    if ($evTime > $today) {
                                        $status = 'Upcoming'; $sc = '#1f9d61'; $sb = '#e8f7f0';
                                    } elseif ($evTime == $today) {
                                        $status = 'Ongoing'; $sc = '#d97706'; $sb = '#fff7e6';
                                    } else {
                                        $status = 'Completed'; $sc = '#6b7280'; $sb = '#f3f4f6';
                                    }
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['event_name']); ?></strong></td>
                                        <td><?php echo date('M j, Y', $evTime); ?></td>
                                        <td><span style="color:<?php echo $sc; ?>; background:<?php echo $sb; ?>; padding:4px 10px; border-radius:20px; font-weight:700; font-size:0.85rem;"><?php echo $status; ?></span></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="3" style="text-align: center; color: #777;">No events registered.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>&copy; 2026 EventEase. All Rights Reserved.</footer>
</body>
</html>