<?php
require 'vendor/autoload.php'; // Make sure this path is correct for your project

header('Content-Type: application/json');

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('YOUR_STRIPE_TEST_KEY'); // Replace with your own Stripe test key when running locally

$input = json_decode(file_get_contents('php://input'), true);

// Sanitize input
$book_id = intval($input['book_id'] ?? 0);

// Connect to your DB and fetch the book price (assuming price is stored in Rs.)
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$book_sql = "SELECT price, admin_id FROM book WHERE book_id = $book_id";
$book_result = mysqli_query($conn, $book_sql);
$book = mysqli_fetch_assoc($book_result);

if (!$book) {
    echo json_encode(['success' => false, 'message' => 'Book not found.']);
    exit();
}

$amount = intval($book['price']) * 100; // convert to paisa (Rs. 100 => 10000)
$admin_id = intval($book['admin_id']);

// Create PaymentIntent
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'inr',
        'automatic_payment_methods' => [
            'enabled' => true,
        ],
        'metadata' => [
            'book_id' => $book_id,
        ]
    ]);

    // Store to your payment table if needed (optional, or after confirmation)
    session_start();
    $user_id = $_SESSION['User_Id'] ?? 0;

    // $insert_sql = "INSERT INTO payment (method, payment_status, time, user_id, admin_id)
    //                VALUES ('stripe', 'pending', NOW(), $user_id, $admin_id)";
    // mysqli_query($conn, $insert_sql);

    echo json_encode([
        'success' => true,
        'clientSecret' => $paymentIntent->client_secret
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Stripe Error: ' . $e->getMessage()
    ]);
}
?>
