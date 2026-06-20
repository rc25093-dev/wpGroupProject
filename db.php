<?php 
// 1. Link to your database setup script
require_once 'db.php'; 

// 2. Fetch the dynamic stat values from MySQL using PDO
try {
    // Total Events count
    $eventCountStmt = $pdo->query("SELECT COUNT(*) FROM events");
    $totalEvents = $eventCountStmt->fetchColumn();

    // Total Tickets Sold count (Assuming a column named 'quantity' or counting rows in a tickets table)
    $ticketCountStmt = $pdo->query("SELECT COUNT(*) FROM bookings"); // Adjust table name if needed
    $totalTickets = $ticketCountStmt->fetchColumn();

    // Total Revenue count (Assuming a price/amount column)
    $revenueStmt = $pdo->query("SELECT SUM(total_price) FROM bookings"); // Adjust table/column if needed
    $totalRevenue = $revenueStmt->fetchColumn() ?? 0;

    // Fetch the array of upcoming events to display in the table
    $eventsQuery = $pdo->query("SELECT event_name, event_date, location, status FROM events ORDER BY event_date ASC LIMIT 5");
    $upcomingEvents = $eventsQuery->fetchAll();

} catch (PDOException $e) {
    // Fail gracefully if your table structure doesn't perfectly match these queries yet
    $totalEvents = 0;
    $totalTickets = 0;
    $totalRevenue = 0;
    $upcomingEvents = [];
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #2d4052;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.2s;
        }

        .navbar a:hover, .navbar a.active {
            color: #a0b2c6;
        }

        .main-container {
            flex: 1;
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .page-title {
            color: #2d4052;
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .page-subtitle {
            color: #555;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .dashboard-card {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            background-color: #fafafa;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            font-size: 24px;
            color: #2d4052;
        }

        .stat-info h3 {
            font-size: 24px;
            color: #2d4052;
            margin-bottom: 2px;
        }

        .stat-info p {
            color: #666;
            font-size: 13px;
        }

        .table-title {
            color: #2d4052;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: bold;
            border-bottom: 2px solid #2d4052;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #dddddd;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            color: #2d4052;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            background-color: #2d4052;
            color: #ffffff;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="eventmanagement.html">Home</a>
        <a href="events.html">Events</a>
        <a href="booking.html">Booking</a>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="feedback.html">Feedback</a>
    </nav>

    <main class="main-container">
        
        <h1 class="page-title">EventEase Organizer Dashboard</h1>
        <p class="page-subtitle">EVENTEASE: Organize Your Events Effortlessly.</p>

        <div class="dashboard-card">
            
            <div class="stats-row">
                <div class="stat-box">
                    <i class="fa-solid fa-calendar-days stat-icon"></i>
                    <div class="stat-info">
                        <h3><?php echo number_format($totalEvents); ?></h3>
                        <p>Total Events</p>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fa-solid fa-ticket stat-icon"></i>
                    <div class="stat-info">
                        <h3><?php echo number_format($totalTickets); ?></h3>
                        <p>Tickets Sold</p>
                    </div>
                </div>
                <div class="stat-box">
                    <i class="fa-solid fa-dollar-sign stat-icon"></i>
                    <div class="stat-info">
                        <h3>$<?php echo number_format($totalRevenue, 2); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

            <div class="table-title">Upcoming Events Overview</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($upcomingEvents)): ?>
                            <?php foreach ($upcomingEvents as $event): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($event['event_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars(date("F d, Y", strtotime($event['event_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td><?php echo htmlspecialchars($event['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #888;">No upcoming eve    nts found in database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <footer>
        &copy; 2026 EventEase. All Rights Reserved.
    </footer>

</body>
</html>