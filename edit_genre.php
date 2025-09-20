<?php
session_start();

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Database connection
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Get genre_id from URL
$genre_id = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 0;

if ($genre_id > 0) {
    // Fetch existing genre details
    $sql = "SELECT * FROM genre WHERE genre_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $genre_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $genre = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$genre) {
        $_SESSION['message'] = "Genre not found.";
        header("Location: genre_display_db.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid genre ID.";
    header("Location: genre_display_db.php");
    exit();
}

// ✅ Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $genre_name = trim($_POST['genre_name']);

    if (!empty($genre_name)) {
        $update_sql = "UPDATE genre SET genre_name = ? WHERE genre_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "si", $genre_name, $genre_id);

        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['message'] = "Genre updated successfully!";
        } else {
            $_SESSION['message'] = "Error updating genre: " . mysqli_error($conn);
        }

        mysqli_stmt_close($update_stmt);
        header("Location: genre_display_db.php");
        exit();
    } else {
        $_SESSION['message'] = "Genre name cannot be empty.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Genre - Book Haven</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #f5e6d7; /* Warm beige */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5c3d2e; /* Dark brown text */
        }
        .container {
            max-width: 500px;
            margin-top: 60px;
        }
        h2 {
            color: #8b4513; /* Saddle brown */
            margin-bottom: 25px;
            text-align: center;
        }
        .card {
            background-color: #fffaf0; /* Ivory */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        label {
            font-weight: 600;
        }
        input[type="text"] {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #a67c52; /* Coffee brown */
            border-color: #8b4513;
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #8b4513;
        }
        .btn-secondary {
            background-color: #d4a373; /* Soft brown */
            border-color: #8b4513;
            color: #fff;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #8b4513;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Edit Genre</h2>
            <form method="POST">
                <label for="genre_name">Genre Name:</label>
                <input type="text" name="genre_name" id="genre_name" value="<?php echo htmlspecialchars($genre['genre_name']); ?>" required>

                <button type="submit" class="btn btn-primary">Update Genre</button>
                <a href="genre_display_db.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
