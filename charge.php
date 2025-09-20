<?php
require 'vendor/autoload.php';  //load php library
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User ID not found. Please log in.");
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

\Stripe\Stripe::setApiKey('YOUR_STRIPE_TEST_KEY'); // Replace with your own Stripe test key when running locally



// Get the Stripe token from the form submission
$token = $_POST['stripeToken']; 

// Create the charge
try {
    $charge = \Stripe\Charge::create([
        'amount' => 200000, // Amount in cents ($5.00)
        'currency' => 'usd',
        'description' => 'Premium Subscription - 30 Days',
        'source' => $token,
    ]);

 
    // Insert payment record into database
    $stmt = $conn->prepare("INSERT INTO payment (method, payment_status, amount, time, user_id) VALUES (?, ?, ?, NOW(), ?)");
$stmt->bind_param("ssdi", $method, $status, $amount, $user_id);

$method = 'stripe';
$status = 'paid';
$amount = 2000.00; // In dollars
$stmt->execute();

    $payment_id = $conn->insert_id;

    // Insert subscription record into database
    $start_time = date('Y-m-d H:i:s');
    $end_time = date('Y-m-d H:i:s', strtotime('+30 days'));

    $stmt2 = $conn->prepare("INSERT INTO subscription (user_id, start_time, end_time, status, plan_type, payment_id) VALUES (?, ?, ?, 'active', 'monthly', ?)");
    $stmt2->execute([$user_id, $start_time, $end_time, $payment_id]);

    // Redirect to success page
    echo "<script>
            alert('Subscription successful. You can now access premium content.');
            window.location.href = 'Homepage.php';
          </script>";
} catch (\Stripe\Exception\CardException $e) {
    // Handle error
    echo "<script>
            alert('Payment failed: " . $e->getMessage() . "');
            window.location.href = 'payment_form.php';
          </script>";
}
?>