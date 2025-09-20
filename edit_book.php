<?php
session_start();
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$book_id = $_GET['book_id'];
$sql = "SELECT b.*, i.image_name,
               GROUP_CONCAT(bg.genre_id) AS genre_ids
        FROM book b
        LEFT JOIN image i ON b.book_id = i.book_id
        LEFT JOIN book_genre bg ON b.book_id = bg.book_id
        WHERE b.book_id = $book_id
        GROUP BY b.book_id, i.image_name";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

$genres_result = mysqli_query($conn, "SELECT * FROM genre");
$all_genres = mysqli_fetch_all($genres_result, MYSQLI_ASSOC);
$selected_genres = explode(',', $book['genre_ids']);

$isbn_error = $pdf_error = $image_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $description = $_POST['description'];
    $pdflink = $_POST['pdflink'] ?? '';
    $genre_ids = $_POST['genres'];

    // Validate ISBN (13 digits)
    if (!preg_match('/^\d{13}$/', $isbn)) {
        $isbn_error = "ISBN must be exactly 13 digits long.";
    }

    // Validate PDF upload
    if (!empty($_FILES['pdf']['name'])) {
        $pdf_ext = pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION);
        if (strtolower($pdf_ext) !== 'pdf') {
            $pdf_error = "Only PDF files are allowed.";
        }
    }

    // Validate image upload
    if (!empty($_FILES['image']['name'])) {
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($image_ext), ['jpg', 'jpeg', 'png', 'gif'])) {
            $image_error = "Only image files (jpg, jpeg, png, gif) are allowed.";
        }
    }

    if (empty($isbn_error) && empty($pdf_error) && empty($image_error)) {
        mysqli_query($conn, "UPDATE book SET title='$title', author='$author', isbn='$isbn', description='$description' WHERE book_id=$book_id");

        mysqli_query($conn, "DELETE FROM book_genre WHERE book_id=$book_id");
        foreach ($genre_ids as $genre_id) {
            mysqli_query($conn, "INSERT INTO book_genre (book_id, genre_id) VALUES ($book_id, $genre_id)");
        }

        // Handle new image upload
        if (!empty($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];

            // Delete old image
            $getOldImage = mysqli_query($conn, "SELECT image_name FROM image WHERE book_id=$book_id");
            $oldImageData = mysqli_fetch_assoc($getOldImage);
            $oldImageName = $oldImageData['image_name'];

            if (!empty($oldImageName) && file_exists("Uploads/" . $oldImageName)) {
                unlink("Uploads/" . $oldImageName);
            }

            move_uploaded_file($image_tmp, "Uploads/" . $image_name);

            $checkImage = mysqli_query($conn, "SELECT * FROM image WHERE book_id=$book_id");
            if (mysqli_num_rows($checkImage) > 0) {
                mysqli_query($conn, "UPDATE image SET image_name='$image_name' WHERE book_id=$book_id");
            } else {
                mysqli_query($conn, "INSERT INTO image (book_id, image_name) VALUES ($book_id, '$image_name')");
            }
        }

        //handle new pdf upload
        if (!empty($_FILES['pdf']['name'])) {
            $pdf_name = $_FILES['pdf']['name'];
            $pdf_tmp = $_FILES['pdf']['tmp_name'];
            $pdf_path = "Uploads/" . $pdf_name;

            // Delete old PDF if it exists
            $getOldPdf = mysqli_query($conn, "SELECT pdflink FROM book WHERE book_id=$book_id");
            $oldPdfData = mysqli_fetch_assoc($getOldPdf);
            $oldPdfName = basename($oldPdfData['pdflink']);

            if (!empty($oldPdfName) && file_exists("Uploads/" . $oldPdfName)) {
                unlink("Uploads/" . $oldPdfName);
            }

            move_uploaded_file($pdf_tmp, $pdf_path);
            mysqli_query($conn, "UPDATE book SET pdflink='$pdf_name' WHERE book_id=$book_id");
        }

        header("Location: displaybooks_db.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <style>
        body {
            font-family: 'Arial', sans-serif !important;
            background-image: url('bookpic1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow-y: auto;
        }

        .edit-book-form {
            background-color: rgba(255, 255, 255, 0.95) !important;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            text-align: left;
        }

        .edit-book-form h2 {
            margin-bottom: 20px;
            color: #4E3B31 !important;
            text-align: center;
        }

        .edit-book-form .form-group {
            margin-bottom: 18px;
        }

        .edit-book-form .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #6F4C3E !important;
        }

        .edit-book-form input,
        .edit-book-form textarea,
        .edit-book-form select {
            width: 100%;
            padding: 12px !important;
            border: 1px solid #C2B280 !important;
            border-radius: 6px !important;
            box-sizing: border-box;
            background-color: #fff !important;
            color: #333 !important;
        }

        .edit-book-form textarea {
            resize: vertical;
            height: 120px;
        }

        .edit-book-form button {
            width: 100%;
            padding: 12px;
            background-color: #8B5A2B !important;
            border: none;
            border-radius: 6px;
            color: white !important;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
            display: inline-block;
            text-align: center;
        }

        .edit-book-form button:hover {
            background-color: #A0522D !important;
        }

        .edit-book-form button:active {
            background-color: #6F4C3E !important;
        }

        .edit-book-form img {
            max-width: 100px;
            margin-top: 10px;
            display: block;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="edit-book-form">
    <h2>Edit Book</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="form-group">
            <label>Author:</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>
        <div class="form-group">
            <label for="isbn">ISBN (13 digits):</label>
            <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
            <?php if (!empty($isbn_error)): ?>
                <div class="error-message"><?php echo $isbn_error; ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($book['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label>PDF File:</label>
            <input type="file" name="pdf">
            <?php if (!empty($book['pdflink'])): ?>
                <p>Current PDF: <a href="Uploads/<?php echo $book['pdflink']; ?>" target="_blank">Download PDF</a></p>
            <?php endif; ?>
            <?php if (!empty($pdf_error)): ?>
                <div class="error-message"><?php echo $pdf_error; ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Genres:</label>
            <select name="genres[]" multiple required>
                <?php foreach ($all_genres as $genre): ?>
                    <option value="<?php echo $genre['genre_id']; ?>" <?php echo in_array($genre['genre_id'], $selected_genres) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genre['genre_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Change Image (optional):</label>
            <input type="file" name="image">
            <?php if (!empty($book['image_name'])): ?>
                <img src="Uploads/<?php echo $book['image_name']; ?>" alt="Current Image">
            <?php endif; ?>
            <?php if (!empty($image_error)): ?>
                <div class="error-message"><?php echo $image_error; ?></div>
            <?php endif; ?>
        </div>
        <button type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
