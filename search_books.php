<?php
// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

if (!empty($search_query)) {
    $search_query_escaped = mysqli_real_escape_string($conn, $search_query);
    $sql = "SELECT book.book_id, book.title, book.author, book.isbn, book.pdflink, book.access_type, image.image_name 
            FROM book 
            LEFT JOIN image ON book.book_id = image.book_id 
            WHERE book.title LIKE '%$search_query_escaped%' 
               OR book.author LIKE '%$search_query_escaped%'
               OR book.isbn LIKE '%$search_query_escaped%'  
            ORDER BY book.book_id DESC";
} else {
    $sql = "SELECT book.book_id, book.title, book.author, book.isbn, book.pdflink, book.access_type, image.image_name 
            FROM book 
            LEFT JOIN image ON book.book_id = image.book_id 
            ORDER BY book.book_id DESC";
}

$result = mysqli_query($conn, $sql);

// Output the results as HTML
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $book_id = $row['book_id'];
        $title = $row['title'];
        $author = $row['author'];
        $isbn = $row['isbn'];  // Fetch ISBN
        $pdf_link = $row['pdflink'];
        $access_type = $row['access_type'];
        $image_path = isset($row['image_name']) ? 'Uploads/' . $row['image_name'] : 'default-book.jpg';

        $badge_class = ($access_type == 1) ? "badge bg-danger" : "badge bg-success";
        $access_label = ($access_type == 1) ? "Premium" : "Free";
?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Book Cover">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                    <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($author); ?></p>
                    <p class="card-text"><strong>ISBN:</strong> <?php echo htmlspecialchars($isbn); ?></p>  <!-- Display ISBN -->
                    <span class="<?php echo $badge_class; ?>"><?php echo $access_label; ?></span>
                    <br><br>
                    <a href="viewdetails_button.php?book_id=<?php echo $book_id; ?>" class="btn btn-primary">View Details</a>
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
    echo "<p class='text-center mt-4'>No books found matching your search.</p>";
}

mysqli_close($conn);
?>


