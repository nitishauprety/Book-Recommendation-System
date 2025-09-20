<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details | Once Upon A Broken Heart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="book_details.css"> <!-- Custom CSS -->
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.html">📖 Book Haven</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Genres</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Search</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Book Details Section -->
<section class="book-details">
    <div class="container text-center">
        <div class="row">
            <!-- Book Image -->
            <div class="col-md-4">
                <img src="bookcover/once-upon-a-broken-heart.jpg" alt="Once Upon A Broken Heart" class="book-cover">
            </div>

            <!-- Book Info -->
            <div class="col-md-8">
                <h1 class="book-title">Once Upon A Broken Heart</h1>
                <h3 class="book-author">By Stephanie Garber</h3>
                <p class="book-description">
                    A whimsical fantasy filled with magic, love, and heartbreak. Evangeline Fox believes in true love and happy endings—until she learns that the love of her life is about to marry someone else.
                    <br><br>
                    Desperate to stop the wedding, she makes a deal with the wickedly handsome Prince of Hearts, but everything comes with a price...
                </p>

                <!-- Buttons -->
                <a href="pdfs/once-upon-a-broken-heart.pdf" class="btn btn-primary btn-lg" download>📥 Download PDF</a>
                <a href="Homepage.php" class="btn btn-outline-secondary btn-lg">🏡 Back to Home</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="text-center py-3">
    <p>&copy; 2025 Book Haven | Cozy Reads for Everyone</p>
</footer>

</body>
</html>
