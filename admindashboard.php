<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.php");
    exit();
}

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


// Total Users
$userResult = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM user");
$totalUsers = mysqli_fetch_assoc($userResult)['total_users'];

// Total Genres
$genreResult = mysqli_query($conn, "SELECT COUNT(*) AS total_genres FROM genre");
$totalGenres = mysqli_fetch_assoc($genreResult)['total_genres'];

// Total Books
$bookResult = mysqli_query($conn, "SELECT COUNT(*) AS total_books FROM book");
$totalBooks = mysqli_fetch_assoc($bookResult)['total_books'];

// Recent Books
$recentBooks = mysqli_query($conn, "SELECT title, author FROM book ORDER BY book_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Haven - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <style>
        body {
            background-color: #fff7f1;
            font-family: "Poppins", sans-serif;
        }
        .sidebar {
            background-color: #ffcad4;
            height: 100vh;
            padding-top: 30px;
        }
        .sidebar a {
            color: #051821;
            font-weight: bold;
            display: block;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #f4a8ae;
        }
        .dashboard-header {
            background-color: #ffcad4;
            color: #051821;
            padding: 20px;
            border-radius: 12px;
        }
        .card-custom {
            background-color: #ffe5ec;
            border-radius: 12px;
        }
        .recent-books {
            background-color: #ffe5ec;
            border-radius: 12px;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 sidebar">
            <h2 class="text-center">📚 Book Haven</h2>
            <a href="#">Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-user"></i> User Details</a>
            <a href="genre_display_db.php"><i class="fas fa-plus-circle"></i> Add Genre</a>
            <a href="addbooks.php"><i class="fas fa-plus-circle"></i> Add Books</a>
            <a href="displaybooks_db.php"><i class="fas fa-check-circle"></i> Book Details</a>
            <a href="payment_details.php"><i class="fas fa-check-circle"></i> Payment Details</a>
            <a href="adminlogout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <!-- Main Section -->
        <main class="col-md-10">
            <div class="dashboard-header mb-4">
                <h3>Welcome, Admin!</h3>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card card-custom p-4 text-center">
                        <h5>Total Users</h5>
                        <h3><?php echo $totalUsers; ?></h3>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-custom p-4 text-center">
                        <h5>Total Genres</h5>
                        <h3><?php echo $totalGenres; ?></h3>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-custom p-4 text-center">
                        <h5>Total Books</h5>
                        <h3><?php echo $totalBooks; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Recently Added Books -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="recent-books">
                        <h5>📖 Recently Added Books</h5>
                        <ul class="list-group mt-3">
                            <?php while($book = mysqli_fetch_assoc($recentBooks)): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($book['title']); ?>
                                    <small class="text-muted">by <?php echo htmlspecialchars($book['author']); ?></small>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

</body>
</html>
