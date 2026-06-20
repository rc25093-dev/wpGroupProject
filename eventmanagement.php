<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['eventname'];
    $desc = $_POST['description'];
    
    $stmt = $pdo->prepare("INSERT INTO events (event_name, description) VALUES (?, ?)");
    $stmt->execute([$name, $desc]);
    $event_id = $pdo->lastInsertId();

    if (isset($_FILES['eventimage'])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["eventimage"]["name"]);
        move_uploaded_file($_FILES["eventimage"]["tmp_name"], $target_dir . $file_name);
        
        $stmt = $pdo->prepare("INSERT INTO event_images (event_id, image_path) VALUES (?, ?)");
        $stmt->execute([$event_id, $file_name]);
    }
    echo "Event created successfully!";
}
?>