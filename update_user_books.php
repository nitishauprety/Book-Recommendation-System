<?php
session_start();

// Database connection
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION["User_Id"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

// Retrieve parameters from the request
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION["User_Id"];
$book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;

// Validate book_id
if ($book_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid book ID"]);
    exit();
}

// Check if the user already has access to this book
$sql = "SELECT * FROM user_books WHERE user_id = $user_id AND book_id = $book_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["success" => false, "message" => "User already has access to this book"]);
    exit();
}

// Insert the new record into the user_books table
$expiry_time = date("Y-m-d H:i:s", strtotime("+36 hours"));
$insert_sql = "INSERT INTO user_books (user_id, book_id, expiry_time) VALUES ($user_id, $book_id, '$expiry_time')";
if (mysqli_query($conn, $insert_sql)) {
    echo json_encode(["success" => true, "message" => "Book access granted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update book access"]);
}

// Close the database connection
mysqli_close($conn);
?>
