<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['User_Id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the book details using the book_id stored in the session
$book_id = $_SESSION['premium_book_id'] ?? 0;

if ($book_id <= 0) {
    echo "<script>alert('Invalid book.'); window.location.href='homepage.php';</script>";
    exit();
}

// Database connection
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

// Get book details (title, etc.)
$result = mysqli_query($conn, "SELECT title FROM book WHERE book_id = $book_id");
$book = mysqli_fetch_assoc($result);

// If no book found
if (!$book) {
    echo "<script>alert('Book not found.'); window.location.href='homepage.php';</script>";
    exit();
}

// Update the session to reflect the purchased book
if (!isset($_SESSION['paid_books'])) {
    $_SESSION['paid_books'] = []; // Initialize if not already set
}

// Add the book to the paid books array
if (!in_array($book_id, $_SESSION['paid_books'])) {
    $_SESSION['paid_books'][] = $book_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdf6f0;
            text-align: center;
            padding: 40px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #d9c2a3;
            border-radius: 10px;
            padding: 30px;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn {
            background-color: #A67C52;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #8B5E3C;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>🎉 Payment Successful!</h2>
        <p>You can now download your book:</p>
        <h3><?php echo htmlspecialchars($book['title']); ?></h3>

        <!-- If the book is paid, show the download button -->
        <a href="download-book.php?book_id=<?php echo $book_id; ?>" class="btn">Download Book</a><br><br>
        <a href="homepage.php" class="btn">Back to Home</a>
    </div>
</body>
</html>
