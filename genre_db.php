<?php
session_start();

if (isset($_POST['add_genre'])) {
    $genre_name = $_POST['genre'];
    $description = $_POST['description'];
    $admin_id = 1; // Assuming the admin ID is 1

    // Validation for genre name and description
    if (strlen($description) > 500) {
        echo "<script>alert('Description should not exceed 500 characters.'); window.location.href = 'addgenre.php';</script>";
        exit();
    }

    if (empty($genre_name) || empty($description)) {
        echo "<script>alert('Both genre name and description are required.'); window.location.href = 'addgenre.php';</script>";
        exit();
    }

    // Connect to database
    $conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if genre already exists
    $check_query = "SELECT * FROM genre WHERE genre_name = '$genre_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Genre already exists!'); window.location.href = 'addgenre.php';</script>";
        exit();
    }

    // Insert into genre table
    $sql = "INSERT INTO genre (genre_name, description, admin_id) VALUES ('$genre_name', '$description', '$admin_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Genre added successfully!'); window.location.href = 'addgenre.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
