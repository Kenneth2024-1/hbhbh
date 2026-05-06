<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'provider') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $provider_id = $_SESSION['user_id'];

    // Check if already applied
    $stmt = $pdo->prepare("SELECT id FROM bookings WHERE job_id = ? AND provider_id = ?");
    $stmt->execute([$job_id, $provider_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Already applied']);
        exit;
    }

    // Insert booking with status 'pending'
    $stmt = $pdo->prepare("INSERT INTO bookings (job_id, provider_id, status) VALUES (?, ?, 'pending')");
    if ($stmt->execute([$job_id, $provider_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
}
?>