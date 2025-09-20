<?php
session_start();

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update_book'])) {
    $book_id = (int)$_POST['book_id'];
    $title = trim($_POST['Title']);
    $author = trim($_POST['Author']);
    $description = trim($_POST['Description']);
    $access_type = (int)$_POST['AccessType'];
    $genres = isset($_POST['Genre']) ? $_POST['Genre'] : [];

    // Validate description allowing !, ", ', /, and other common characters
    if (strlen($description) > 250) {
        die("Error: Description should not exceed 250 characters.");
    }
    if (!preg_match("/^[a-zA-Z0-9\s.,'\"!?\/-]+$/", $description)) {
        die("Error: Description contains invalid characters.");
    }

    // Check for duplicate title (excluding current book)
    $check_sql = "SELECT book_id FROM book WHERE title = '$title' AND book_id != $book_id";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        die("Error: A book with the same title already exists.");
    }

    // Handle PDF upload (if provided)
    $pdfPath = '';
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $pdfFolder = 'pdfs/';
        $filename = time() . '_' . basename($_FILES['pdf_file']['name']);
        $pdfPath = $pdfFolder . $filename;

        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdfPath)) {
            die("Error uploading PDF file.");
        }

        // Update PDF link in the book record
        $update_pdf = ", pdflink = '$pdfPath'";
    } else {
        $update_pdf = ""; // Don't update PDF if no new file is uploaded
    }

    // Update book info (title, author, description, access type, and PDF if updated)
    $update_book_sql = "UPDATE book 
                        SET title = '$title', author = '$author', description = '$description',
                            access_type = $access_type $update_pdf
                        WHERE book_id = $book_id";

    if (!mysqli_query($conn, $update_book_sql)) {
        die("Error updating book: " . mysqli_error($conn));
    }

    // Handle image upload (if provided)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFolder = 'Uploads/';
        $imageFileName = time() . '_' . basename($_FILES['image']['name']);
        $imagePath = $imageFolder . $imageFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Check if image already exists
            $check_img_sql = "SELECT * FROM image WHERE book_id = $book_id";
            $img_result = mysqli_query($conn, $check_img_sql);

            if (mysqli_num_rows($img_result) > 0) {
                // Update existing image
                $update_img_sql = "UPDATE image SET image_name = '$imageFileName' WHERE book_id = $book_id";
                mysqli_query($conn, $update_img_sql);
            } else {
                // Insert new image record
                $insert_img_sql = "INSERT INTO image (book_id, image_name) VALUES ($book_id, '$imageFileName')";
                mysqli_query($conn, $insert_img_sql);
            }
        } else {
            echo "Error uploading image.";
        }
    }

    // Update genres
    // First, delete old genres
    $delete_genres_sql = "DELETE FROM book_genre WHERE book_id = $book_id";
    mysqli_query($conn, $delete_genres_sql);

    // Then insert selected genres
    foreach ($genres as $genre_name) {
        $genre_sql = "SELECT genre_id FROM genre WHERE genre_name = '$genre_name'";
        $genre_result = mysqli_query($conn, $genre_sql);
        if ($row = mysqli_fetch_assoc($genre_result)) {
            $genre_id = $row['genre_id'];
            $insert_genre_sql = "INSERT INTO book_genre (book_id, genre_id) VALUES ($book_id, $genre_id)";
            mysqli_query($conn, $insert_genre_sql);
        }
    }

    echo "Book updated successfully!";
    header("Location: edit_book.php?book_id=$book_id"); // Redirect back to the edit book page
    exit();
} else {
    echo "Invalid request.";
}
?>
