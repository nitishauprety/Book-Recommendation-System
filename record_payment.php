<?php
session_start();
require 'vendor/autoload.php';
header('Content-Type: application/json');

date_default_timezone_set("Asia/Kathmandu");

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_SESSION['User_Id'] ?? null;
$book_id = intval($data['book_id'] ?? 0);
$payment_intent_id = $data['payment_intent_id'] ?? '';
$amount = intval($data['amount'] ?? 0);
$admin_id = intval($data['admin_id'] ?? 0);

if (!$user_id || !$payment_intent_id || $book_id <= 0 || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit();
}

// Check for existing completed payment for the same book by the same user
$check = mysqli_query($conn, "SELECT * FROM payment WHERE user_id=$user_id AND book_id=$book_id AND payment_status='Completed'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode(['success' => true, 'message' => 'Already paid.']);
    exit();
}

$timestamp = date('Y-m-d H:i:s');

$sql = "INSERT INTO payment (method, payment_status, time, amount, user_id, admin_id, book_id)
        VALUES ('Stripe', 'Completed', '$timestamp', $amount, $user_id, $admin_id, $book_id)";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
