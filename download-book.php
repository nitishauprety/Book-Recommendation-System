<?php
session_start();
if (!isset($_SESSION['User_Id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['User_Id'];
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

if ($book_id <= 0) {
    die("Invalid book.");
}

// ✅ Fetch book info (including access_type and pdflink)
$sql = "SELECT pdflink, title, access_type FROM book WHERE book_id = $book_id";
$res = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($res);

if (!$book || empty($book['pdflink'])) {
    die("Book PDF not found.");
}

$pdf_file = "Uploads/" . $book['pdflink'];
$access_type = $book['access_type'];

// ✅ Check if user is a premium user (has an active subscription)
if ($access_type == 1) {
    // Check if the user has an active subscription
    $sub_sql = "SELECT * FROM subscription WHERE user_id = $user_id AND status = 'Active' AND end_time >= NOW() LIMIT 1";
    $sub_result = mysqli_query($conn, $sub_sql);
    $has_active_subscription = mysqli_num_rows($sub_result) > 0;

    if (!$has_active_subscription) {
        // Non-premium users need to pay for this specific premium book
        $payment_sql = "SELECT time FROM payment 
                        WHERE user_id = $user_id AND book_id = $book_id AND payment_status = 'Completed'
                        ORDER BY time DESC LIMIT 1";
        $payment_result = mysqli_query($conn, $payment_sql);
        $payment_row = mysqli_fetch_assoc($payment_result);

        if (!$payment_row) {
            die("You have not purchased this premium book.");
        }

        // Check if access is still valid (36 hours)
        $purchase_time = strtotime($payment_row['time']);
        $current_time = time();

        if (($current_time - $purchase_time) > (36 * 60 * 60)) {
            die("Your access to download this book has expired after 36 hours.");
        }
    }
}

// ✅ Check if the PDF file exists on the server
if (!file_exists($pdf_file)) {
    die("PDF file does not exist on the server. Path checked: $pdf_file");
}

// ✅ Log the book interaction to user_history if not already recorded
$check_history_sql = "SELECT * FROM user_history WHERE user_id = $user_id AND book_id = $book_id";
$check_history_result = mysqli_query($conn, $check_history_sql);

if (mysqli_num_rows($check_history_result) == 0) {
    $insert_history_sql = "INSERT INTO user_history (user_id, book_id) VALUES ($user_id, $book_id)";
    mysqli_query($conn, $insert_history_sql);
}

// ✅ Force the download of the book PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($pdf_file) . '"');
header('Content-Length: ' . filesize($pdf_file));
readfile($pdf_file);
exit();
?>
