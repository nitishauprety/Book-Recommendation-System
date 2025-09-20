<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Genre</title>
    <link rel="stylesheet" href="addgenre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="genre_db.php" method="POST">

            <h2>Add Genre</h2>
            <div class="form-group">
                <label for="genre_name">Genre Name</label>
                <input type="text" id="genre" name="genre" placeholder="Enter genre name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter description" rows="4" maxlength="500" required></textarea>
            </div>
            <button type="submit" name="add_genre" id="add_genre">Add</button>

        </form>
    </div>
</body>
</html>
