<?php
session_start();

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch genres from the database
$sql = "SELECT * FROM genre";
$result = mysqli_query($conn, $sql);
$genres = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <title>Manage Genres - Book Haven</title>
    <style>
        body {
            background-color: #f5e6d7; /* Warm beige */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5c3d2e; /* Dark brown for text */
        }
        h2 {
            color: #8b4513; /* Saddle brown */
        }
        .btn-primary {
            background-color: #a67c52; /* Coffee brown */
            border-color: #8b4513;
        }
        .btn-primary:hover {
            background-color: #8b4513;
        }
        .btn-warning {
            background-color: #d4a373; /* Soft brown */
            border-color: #8b4513;
        }
        .btn-danger {
            background-color: #b85c5c; /* Reddish brown */
            border-color: #8b4513;
        }
        table {
            background-color: #fffaf0; /* Ivory */
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            color: #5c3d2e;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Genres</h2>
        <a href="addgenre.php" class="btn btn-primary mb-3">Add New Genre</a>

        <?php if (count($genres) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Genre Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genres as $genre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($genre['genre_name']); ?></td>
                            <td><?php echo htmlspecialchars($genre['description']); ?></td>
                            <td>
                                <a href="edit_genre.php?genre_id=<?php echo $genre['genre_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_genre.php?genre_id=<?php echo $genre['genre_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this genre?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No genres available. Please add one.</p>
        <?php endif; ?>
    </div>
</body>
</html>