<?php
require 'vendor/autoload.php';
session_start();

// ✅ Step 1: Check session for user ID
if (!isset($_SESSION['user_id'])) {
    die("User ID not found. Please register again.");
}

$user_id = $_SESSION['user_id'];

// ✅ Step 2: Database connection (used later in success page)
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Step 3: Stripe secret key
\Stripe\Stripe::setApiKey('YOUR_STRIPE_TEST_KEY'); // Replace with your own Stripe test key when running locally


// ✅ Step 4: Define your local domain path
$YOUR_DOMAIN = 'http://localhost/Bookrecommendation/';

// ✅ Step 5: Create the Stripe checkout session
$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'product_data' => ['name' => 'Premium Subscription - 30 Days'],
           'unit_amount' => 200000, // in cents
'currency' => 'usd',

        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/subscription_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . '/subscription_cancel.php',
]);

// After successful registration
$_SESSION['user_id'] = $new_user_id; // whatever you get from DB
header("Location: payment_form.php");
exit();

?>
