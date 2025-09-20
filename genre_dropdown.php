<?php
// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get selected genre from URL
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : '';

// Fetch books that match the selected genre
$sql = "SELECT b.book_id, b.title, b.author, b.pdflink, b.access_type, i.image_name 
        FROM book b
        LEFT JOIN image i ON b.book_id = i.book_id
        JOIN book_genre bg ON b.book_id = bg.book_id
        JOIN genre g ON bg.genre_id = g.genre_id
        WHERE g.genre_name = '" . mysqli_real_escape_string($conn, $selected_genre) . "'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books in Genre: <?php echo htmlspecialchars($selected_genre); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="homepage.css"> <!-- Same styling as homepage -->
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📚 Books in "<?php echo htmlspecialchars($selected_genre); ?>"</h2>
    <div class="row mt-4">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $book_id = $row['book_id'];
                $title = $row['title'];
                $author = $row['author'];
                $pdf_link = $row['pdflink'];
                $access_type = $row['access_type'];
                $image_path = isset($row['image_name']) ? 'Uploads/' . $row['image_name'] : 'default-book.jpg';

                $badge_class = ($access_type == 1) ? "badge bg-danger" : "badge bg-success";
                $access_label = ($access_type == 1) ? "Premium" : "Free";
        ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Book Cover">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                            <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($author); ?></p>
                            <span class="<?php echo $badge_class; ?>"><?php echo $access_label; ?></span>
                            <br><br>

                            <a href="viewdetails_button.php?book_id=<?php echo $book_id; ?>" class="btn btn-primary mb-2">View Details</a>

                            <a href="<?php echo ($access_type == 0) ? htmlspecialchars($pdf_link) : 'subscribe.php'; ?>"
                               class="btn <?php echo ($access_type == 0) ? 'btn-outline-secondary' : 'btn-warning'; ?>"
                               <?php echo ($access_type == 0) ? 'download' : ''; ?>>
                               Download PDF
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='text-center'>No books found in this genre.</p>";
        }
        mysqli_close($conn);
        ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
    <p>&copy; 2025 Book Haven | Cozy Reads for Everyone</p>
</footer>

</body>
</html>
