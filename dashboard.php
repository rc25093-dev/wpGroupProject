<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to access this page.'); window.location.href='login.php';</script>";
    exit();
}

$loggedIn = true;

$stats = [
    'total_events' => 0,
    'total_tickets' => 0,
    'total_revenue' => 0.00
];
$events = [];

try {
    if ($result = mysqli_query($conn, "SELECT COUNT(*) AS total_events FROM events")) {
        $row = mysqli_fetch_assoc($result);
        $stats['total_events'] = (int)($row['total_events'] ?? 0);
    }

    if ($result = mysqli_query($conn, "SELECT COALESCE(SUM(capacity), 0) AS total_capacity FROM events")) {
        $row = mysqli_fetch_assoc($result);
        $stats['total_tickets'] = (int)($row['total_capacity'] ?? 0);
    }

    if ($result = mysqli_query($conn, "SELECT COALESCE(SUM(total_payment), 0) AS total_revenue FROM bookings")) {
        $row = mysqli_fetch_assoc($result);
        $stats['total_revenue'] = (float)($row['total_revenue'] ?? 0.00);
    }

    if ($result = mysqli_query($conn, "SELECT event_name, event_date, venue FROM events ORDER BY event_date ASC LIMIT 6")) {
        $while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }
} catch (Exception $e) {
    // Graceful fallback if something goes wrong while reading the dashboard data.
}
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
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
        }

        .dashboard-hero {
            background: url('dashboardimage.png') no-repeat center center;
            background-size: cover;
            color: #fff;
            padding: 80px 6% 140px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            animation: fadeUp 0.6s ease;
            position: relative;
        }

        /* Dark overlay to ensure text readability over the background image */
        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(90deg, rgba(29, 53, 87, 0.85), rgba(44, 93, 157, 0.7));
            z-index: 1;
        }

        .dashboard-hero > div, .dashboard-hero .dashboard-action-btn {
            position: relative;
            z-index: 2;
        }

        .dashboard-hero h1 {
            margin: 0;
            font-size: 2.2rem;
            color: #fff;
        }

        .dashboard-hero p {
            margin: 8px 0 0;
            color: #dfeaff;
        }

        .dashboard-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, #f8b400, #ff7a59);
            color: #12213f;
            text-decoration: none;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 999px;
            box-shadow: 0 8px 18px rgba(248, 180, 0, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(248, 180, 0, 0.35);
        }

        .dashboard-panel {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            margin: -80px auto 40px auto;
            width: min(1180px, 92%);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            animation: fadeUp 0.7s ease;
            position: relative;
            z-index: 10;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(180deg, #f8fbff, #eef5ff);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.2s ease;
        }

        .stat-box:hover {
            transform: translateY(-3px);
        }

        /* Specific style for Total Revenue block to include the background image */
        .revenue-box {
            background: url('TotalRevenue.png') no-repeat center center;
            background-size: cover;
            position: relative;
            color: #fff;
        }

        .revenue-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(18, 33, 63, 0.65);
            border-radius: 16px;
            z-index: 1;
        }

        .revenue-box .stat-icon, .revenue-box .stat-info h3, .revenue-box .stat-info p {
            color: #fff !important;
            position: relative;
            z-index: 2;
        }

        .stat-icon {
            font-size: 1.8rem;
            color: #2c5d9d;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #1d3557;
        }

        .stat-info p {
            margin: 4px 0 0;
            color: #5b6b85;
        }

        /* Grid layout to match image alongside the event list table */
        .dashboard-content-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 28px;
        }

        @media (min-width: 768px) {
            .dashboard-content-layout {
                grid-template-columns: 350px 1fr;
            }
        }

        .upcoming-event-img-container {
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upcoming-event-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .table-title {
            color: #1d3557;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #eef2f7;
            text-align: left;
        }

        th {
            background: linear-gradient(90deg, #eef5ff, #f8fbff);
            color: #1d3557;
            font-weight: 700;
        }

        tr:nth-child(even) td {
            background: #fbfcff;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #777;
            background: #fff;
            margin-top: 40px;
            border-top: 1px solid #eef2f7;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <i class="fa-solid fa-chart-line"></i>
            EventEase
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="event_listing.php">Events</a></li>
            <li><a href="booking.php" data-protect="true">Booking</a></li>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
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

    <section class="dashboard-hero">
        <div>
            <h1>EventEase Organizer Dashboard</h1>
            <p>Stay on top of your events, bookings, and next steps.</p>
        </div>
        <a href="eventmanagement.php" class="dashboard-action-btn">
            <i class="fa-solid fa-plus"></i>
            Create New Event
        </a>
    </section>

    <main>
        <section class="dashboard-panel">
            <div class="stats-row">
                <div class="stat-box">
                    <i class="fa-solid fa-calendar-days stat-icon"></i>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['total_events']); ?></h3>
                        <p>Total Events</p>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fa-solid fa-ticket stat-icon"></i>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['total_tickets']); ?></h3>
                        <p>Total Capacity</p>
                    </div>
                </div>
                <div class="stat-box revenue-box">
                    <i class="fa-solid fa-dollar-sign stat-icon"></i>
                    <div class="stat-info">
                        <h3>RM <?php echo number_format($stats['total_revenue'], 2); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-content-layout">
                <div class="upcoming-event-img-container">
                    <img src="upcomingEvent.png" alt="Upcoming Events Preview">
                </div>
                
                <div>
                    <div class="table-title">Upcoming Events</div>
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
                                <?php if (!empty($events)): ?>
                                    <?php foreach ($events as $row): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['event_name']); ?></strong></td>
                                            <td><?php echo date('M j, Y', strtotime($row['event_date'])); ?></td>
                                            <td><span style="color: #1f9d61; font-weight: 700; background: #e8f7f0; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;">Upcoming</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center; color: #777;">No upcoming events found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2026 EventEase. All Rights Reserved.
    </footer>

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