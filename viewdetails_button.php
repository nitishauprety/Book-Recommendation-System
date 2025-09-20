<?php
// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if book_id is provided
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details
    $sql = "SELECT title, author, description, image_name FROM book 
            LEFT JOIN image ON book.book_id = image.book_id 
            WHERE book.book_id = $book_id";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($result);

    if (!$book) {
        echo "<h2>Book not found!</h2>";
        exit;
    }
} else {
    echo "<h2>Invalid request!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Read Now</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">📖 Book Haven</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="Homepage.php">Home</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Book Details -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <img src="Uploads/<?php echo $book['image_name']; ?>" class="img-fluid rounded" alt="Book Cover">
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
            <h5 class="text-muted">by <?php echo htmlspecialchars($book['author']); ?></h5>
            <p class="mt-3"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
    <p>&copy; 2025 Book Haven | Cozy Reads for Everyone</p>
</footer>

</body>
</html>
