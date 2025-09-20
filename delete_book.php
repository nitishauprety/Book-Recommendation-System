<?php
session_start();

$Book_Id = $_GET['book_id'];

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// First, fetch the images associated with the book
$sql_images = "SELECT image_name FROM image WHERE book_id = $Book_Id";
$result_images = mysqli_query($conn, $sql_images);

if (!$result_images) {
    die("Error fetching images: " . mysqli_error($conn));
}

// Delete images from server and image table
while ($row = mysqli_fetch_assoc($result_images)) {
    $image_name = $row['image_name'];
    $image_path = "Uploads/" . $image_name; // Adjust path as per your actual setup

    // Delete file from server
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete records from the image table for the book
$sql_delete_images = "DELETE FROM image WHERE book_id = $Book_Id";
if (!mysqli_query($conn, $sql_delete_images)) {
    die("Error deleting image record: " . mysqli_error($conn));
}

// **Delete the FK references only (not the genre itself)**
$sql_delete_book_genre = "DELETE FROM book_genre WHERE book_id = $Book_Id";
if (!mysqli_query($conn, $sql_delete_book_genre)) {
    die("Error deleting book genre relation: " . mysqli_error($conn));
}

// Delete the book record from the book table
$sql_delete_book = "DELETE FROM book WHERE book_id = $Book_Id";
if (mysqli_query($conn, $sql_delete_book)) {
    header('Location: displaybooks_db.php?message=Book deleted successfully');
    exit;
} else {
    echo "ERROR: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
