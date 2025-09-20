<?php
if (isset($_POST['add_book'])) {
    session_start();
    $title = trim($_POST['Title']);
    $author = trim($_POST['Author']);
    $isbn = $_POST['isbn'];
    $price=$_POST['Price'];
    $description = trim($_POST['Description']);
    $access_type = (int)$_POST['AccessType'];
    $admin_id = 1;
    $genres = isset($_POST['Genre']) ? $_POST['Genre'] : [];

    // Connect to the database
    $conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $price = $_POST['Price'];
$access_type = (int)$_POST['AccessType'];

// Validation: Check price rules
if ($access_type === 0 && $price != 0) {
    echo "Error: Free books must have a price of 0.";
    exit();
}

if ($access_type === 1 && $price <= 0) {
    echo "Error: Premium books must have a price greater than 0.";
    exit();
}


    // Validation: Check if description exceeds 250 characters
    if (strlen($description) > 250) {
        echo "Error: Description should not exceed 250 characters.";
        exit();
    }

    // Validation: Check if description contains only allowed characters
    if (!preg_match("/^[a-zA-Z0-9\s.,'\"()_\-?]+$/", $description)) {
        echo "Error: Description contains invalid characters. Only letters, numbers, spaces, apostrophes, commas, hyphens, and full stops are allowed.";
        exit();
    }

    // Validation: Check if ISBN is exactly 13 digits
    if (!preg_match("/^\d{13}$/", $isbn)) {
        echo "Error: ISBN must be exactly 13 digits long.";
        exit();
    }

    // Validation: Check if the book with the same title already exists
    $check_duplicate_sql = "SELECT book_id FROM book WHERE title = '$title'";
    $result_check = mysqli_query($conn, $check_duplicate_sql);

    if (mysqli_num_rows($result_check) > 0) {
        echo "Error: A book with the same title already exists.";
        exit();
    }

    // Handle PDF Upload
    $pdfPath = '';
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $pdfFolder = 'Uploads/';
        $filename = time() . '_' . basename($_FILES['pdf_file']['name']);
        $pdfPath = $pdfFolder . $filename;

        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdfPath)) {
            echo "Error uploading PDF file.";
            exit();
        }
    } else {
        echo "No PDF file uploaded.";
        exit();
    }

    // Insert into book table
    $sql_insert_book = "INSERT INTO book (title, author, isbn, description, access_type, admin_id, pdflink, price) 
                         VALUES ('$title', '$author', '$isbn', '$description', '$access_type', '$admin_id', '$pdfPath',$price)";

    if (mysqli_query($conn, $sql_insert_book)) {
        $book_id = mysqli_insert_id($conn);
        echo "Book added successfully! Book ID: $book_id<br>";

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFolder = 'Uploads/';
            $imageFileName = time() . '_' . basename($_FILES['image']['name']);
            $imagePath = $imageFolder . $imageFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // Insert image details into image table
                $sql_insert_image = "INSERT INTO image (book_id, image_name) VALUES ('$book_id', '$imageFileName')";
                if (mysqli_query($conn, $sql_insert_image)) {
                    echo "Image uploaded and inserted successfully!";
                } else {
                    echo "Error inserting image into database: " . mysqli_error($conn);
                }
            } else {
                echo "Error moving image to the Uploads folder.";
            }
        } else {
            echo "No image uploaded or error occurred.";
        }

        // Insert into book_genre table
        if (!empty($genres)) {
            foreach ($genres as $genre_name) {
                // Get genre_id from the genre table
                $sql_find_genre = "SELECT genre_id FROM genre WHERE genre_name = '$genre_name'";
                $result_genre = mysqli_query($conn, $sql_find_genre);

                if ($row = mysqli_fetch_assoc($result_genre)) {
                    $genre_id = $row['genre_id'];

                    // Insert into book_genre table
                    $sql_insert_book_genre = "INSERT INTO book_genre (book_id, genre_id) VALUES ('$book_id', '$genre_id')";

                    if (mysqli_query($conn, $sql_insert_book_genre)) {
                        echo "Genre '$genre_name' added to book_genre table.<br>";
                    } else {
                        echo "Error adding genre to book_genre table: " . mysqli_error($conn);
                    }
                } else {
                    echo "Genre not found: $genre_name<br>";
                }
            }
        } else {
            echo "No genres selected.<br>";
        }
    } else {
        echo "Error adding book: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
