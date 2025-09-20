<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["User_Id"])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch books
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$search_query_escaped = mysqli_real_escape_string($conn, $search_query);

if (!empty($search_query)) {
    $sql = "SELECT book.book_id, book.title, book.author, book.pdflink, book.access_type, image.image_name 
            FROM book 
            LEFT JOIN image ON book.book_id = image.book_id 
            WHERE book.title LIKE '%$search_query_escaped%' 
               OR book.author LIKE '%$search_query_escaped%'
            ORDER BY book.book_id DESC";
} else {
    $sql = "SELECT book.book_id, book.title, book.author, book.pdflink, book.access_type, image.image_name 
            FROM book 
            LEFT JOIN image ON book.book_id = image.book_id 
            ORDER BY book.book_id DESC";
}

$result = mysqli_query($conn, $sql);

// Fetch paid books for the user
$paid_books = [];
$user_id = $_SESSION['User_Id'];
$payment_sql = "SELECT book_id FROM payment WHERE user_id = $user_id AND payment_status = 'Completed'";
$payment_result = mysqli_query($conn, $payment_sql);
if ($payment_result) {
    while ($payment_row = mysqli_fetch_assoc($payment_result)) {
        $paid_books[] = $payment_row['book_id'];
    }
}

// Check if user has an active subscription
$has_active_subscription = false;

$sub_sql = "SELECT * FROM subscription 
            WHERE user_id = $user_id 
              AND status = 'Active' 
              AND end_time >= NOW() 
            LIMIT 1";

$sub_result = mysqli_query($conn, $sub_sql);
if ($sub_result && mysqli_num_rows($sub_result) > 0) {
    $has_active_subscription = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Recommendation System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="homepage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">📖 Book Haven</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Genres
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    $genre_sql = "SELECT genre_name FROM genre";
                    $genre_result = mysqli_query($conn, $genre_sql);
                    if (mysqli_num_rows($genre_result) > 0) {
                        while ($genre_row = mysqli_fetch_assoc($genre_result)) {
                            $genre_name = $genre_row['genre_name'];
                            echo '<li><a class="dropdown-item" href="genre_dropdown.php?genre=' . urlencode($genre_name) . '">' . htmlspecialchars($genre_name) . '</a></li>';
                        }
                    } else {
                        echo '<li><a class="dropdown-item" href="#">No Genres Available</a></li>';
                    }
                    ?>
                    </ul>
                </li>
                <!-- Search Bar -->
                <li class="nav-item">
                    <form method="GET" id="searchForm" class="d-flex ms-3">
                        <input type="text" name="search_query" id="search_query" class="form-control me-2" placeholder="Search by title or author..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                        <button type="submit" class="btn btn-primary">🔍</button>
                    </form>
                </li>
                <li class="nav-item d-flex">
    <a class="nav-link btn btn-primary text-white me-2" href="login.php">Login</a>
    <a class="nav-link btn btn-primary text-white me-2" href="logout.php">Logout</a>
    <a class="nav-link btn btn-secondary text-white me-2" href="history.php">History</a>
    <a class="nav-link btn btn-danger text-white" href="adminlogin.php">Admin Login</a>
</li>

            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero text-center">
    <div class="container">
        <h1>Find Comfort in Every Page 📚</h1>
        <p>Your go-to place for warm, cozy book recommendations</p>
        <a href="recommendation.php" class="btn btn-light btn-lg">Get Recommendations</a>

    </div>
</header>

<!-- Popular Books Section -->
<section class="container mt-5">
    <h2 class="text-center">🌟 Popular Reads</h2>
    <div class="row" id="books-list">
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
                    <div class="card">
                        <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Book Cover">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                            <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($author); ?></p>
                            <span class="<?php echo $badge_class; ?>"><?php echo $access_label; ?></span>
                            <br><br>
                            <a href="viewdetails_button.php?book_id=<?php echo $book_id; ?>" class="btn btn-primary">View Details</a>
                            <?php
                           if ($access_type == 0 || $has_active_subscription || in_array($book_id, $paid_books)) {
                            echo '<a href="download-book.php?book_id=' . $book_id . '" class="btn btn-success">Download Book</a>';
                        } else {
                            echo '<a href="preview.php?book_id=' . $book_id . '" class="btn btn-warning">Read Preview</a>';
                        }
                        
                            ?>
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
    </div>
</section>

<!-- Footer -->
<footer class="text-center py-3">
    <p>&copy; 2025 Book Haven | Cozy Reads for Everyone</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
