<?php
session_start();
require_once 'database.php';

// Quick security check
if (!isset($_SESSION['user_id'])) {
    die("Access denied.");
}

// Set headers to force download as a CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=EventEase_Report_' . date('Y-m-d') . '.csv');

// Open the output stream
$output = fopen('php://output', 'w');

// 1. Write the Summary/Stats Row
fputcsv($output, ['EventEase Summary Report', date('Y-m-d H:i:s')]);
fputcsv($output, []); // Empty line

// Fetch calculations
$totalEvents  = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totalTickets = $pdo->query("SELECT COALESCE(SUM(capacity), 0) FROM events")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_payment), 0) FROM bookings")->fetchColumn();

fputcsv($output, ['Metric', 'Value']);
fputcsv($output, ['Total Events Hosted', $totalEvents]);
fputcsv($output, ['Total Ticket Capacity', $totalTickets]);
fputcsv($output, ['Total Revenue (RM)', number_format($totalRevenue, 2)]);
fputcsv($output, []); // Empty line

// 2. Write the Detailed Event List
fputcsv($output, ['Event Name', 'Event Date', 'Capacity']);
$events = $pdo->query("SELECT event_name, event_date, capacity FROM events ORDER BY event_date DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>