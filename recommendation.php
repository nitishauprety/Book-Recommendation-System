<?php
session_start();

if (!isset($_SESSION["User_Id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["User_Id"];
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 1: Fetch user's book history (interacted/downloaded books)
$history_sql = "
    SELECT DISTINCT b.book_id, b.author, bg.genre_id
    FROM user_history uh
    JOIN book b ON uh.book_id = b.book_id
    JOIN book_genre bg ON b.book_id = bg.book_id
    WHERE uh.user_id = $user_id
";
$history_result = mysqli_query($conn, $history_sql);

$user_books = [];
$user_authors = [];
$user_genres = [];

if (mysqli_num_rows($history_result) > 0) {
    while ($row = mysqli_fetch_assoc($history_result)) {
        $user_books[] = $row['book_id'];
        $user_authors[] = $row['author'];
        $user_genres[] = $row['genre_id'];
    }
}

$user_authors = array_unique($user_authors);
$user_genres = array_unique($user_genres);

$recommendations = [];

if (!empty($user_books)) {
    // Escape and format author list
    $authors_str = "'" . implode("','", array_map(function($a) use ($conn) {
        return mysqli_real_escape_string($conn, $a);
    }, $user_authors)) . "'";

    // Format genre ID list
    $genres_str = implode(',', array_map('intval', $user_genres));
    $book_ids_str = implode(',', array_map('intval', $user_books));

    // Step 2: Fetch books not already read but match author or genre
    $rec_sql = "
    SELECT DISTINCT b.*, i.image_name
    FROM book AS b
    LEFT JOIN image AS i ON b.book_id = i.book_id
    LEFT JOIN book_genre AS bg ON b.book_id = bg.book_id
    WHERE b.book_id NOT IN ($book_ids_str)
      AND (
          b.author IN ($authors_str)
          OR bg.genre_id IN ($genres_str)
      )
    ORDER BY b.book_id DESC
    LIMIT 10
";


    $recommendations = mysqli_query($conn, $rec_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommendations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Personalized Recommendations 📚</h2>
    <div class="row">
        <?php
        if (!empty($recommendations) && mysqli_num_rows($recommendations) > 0) {
            while ($book = mysqli_fetch_assoc($recommendations)) {
                $book_id = $book['book_id'];
                $image = $book['image_name'] ? 'Uploads/' . $book['image_name'] : 'default-book.jpg';

                ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo $image; ?>" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($book['author']); ?></p>
                            <a href="viewdetails_button.php?book_id=<?php echo $book_id; ?>" class="btn" style="background-color: #5A3E36; color: white;">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='mt-4'>No recommendations found. Try reading or downloading books to get suggestions!</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
