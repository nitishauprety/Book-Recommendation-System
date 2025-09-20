<?php
session_start();

// ✅ Optional: Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Database connection
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Get genre_id from URL
$genre_id = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 0;

if ($genre_id > 0) {
    // Prepare and execute delete query
    $sql = "DELETE FROM genre WHERE genre_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $genre_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Genre deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting genre: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = "Invalid genre ID.";
}

mysqli_close($conn);

// ✅ Redirect back to Manage Genres page
header("Location: genre_display_db.php");
exit();
