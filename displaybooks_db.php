<?php
session_start();

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch books with their images, authors, descriptions, genres, PDF links, and ISBN
$sql = "SELECT b.book_id, b.title, b.author, b.isbn, b.description, i.image_name, b.pdflink,
               GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres
        FROM book b
        LEFT JOIN image i ON b.book_id = i.book_id
        LEFT JOIN book_genre bg ON b.book_id = bg.book_id
        LEFT JOIN genre g ON bg.genre_id = g.genre_id
        GROUP BY b.book_id, b.title, b.author, b.isbn, b.description, i.image_name, b.pdflink";
$result = mysqli_query($conn, $sql);
$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <title>Manage Books - Book Haven</title>
    <style>
        body {
            background-color: #f5e6d7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5c3d2e;
        }
        h2 {
            color: #8b4513;
        }
        .btn-primary {
            background-color: #a67c52;
            border-color: #8b4513;
        }
        .btn-primary:hover {
            background-color: #8b4513;
        }
        .btn-warning {
            background-color: #d4a373;
            border-color: #8b4513;
        }
        .btn-danger {
            background-color: #8b4513;
            border-color: #8b4513;
        }
        .btn-back {
            background-color: #a67c52;
            border-color: #8b4513;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #4a2e22;
            border-color: #4a2e22;
        }
        table {
            background-color: #fffaf0;
            border-radius: 8px;
        }
        th, td {
            color: #5c3d2e;
            vertical-align: middle;
        }
        img {
            max-width: 80px;
            height: auto;
            border-radius: 8px;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Back Button -->
        <a href="admindashboard.php" class="btn btn-back mb-3">← Back</a>
        
        <h2>Manage Books</h2>
        <a href="addbooks.php" class="btn btn-primary mb-3">Add New Book</a>

        <?php if (count($books) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th> <!-- New ISBN column -->
                        <th>Description</th>
                        <th>Genres</th>
                        <th>PDF Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <?php if (!empty($book['image_name'])): ?>
                                    <img src="Uploads/<?php echo htmlspecialchars($book['image_name']); ?>" alt="Book Image">
                                <?php else: ?>
                                    <p>No Image Available</p>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['isbn']); ?></td> <!-- Display ISBN -->
                            <td><?php echo htmlspecialchars($book['description']); ?></td>
                            <td><?php echo htmlspecialchars($book['genres']); ?></td>
                            <td>
                                <?php if (!empty($book['pdflink'])): ?>
                                    <a href="Uploads/<?php echo htmlspecialchars($book['pdflink']); ?>" target="_blank" class="btn btn-info btn-sm">Download PDF</a>
                                <?php else: ?>
                                    <p>No PDF Available</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No books available. Please add one.</p>
        <?php endif; ?>
    </div>
</body>
</html>
