<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Book</title>
    <link rel="stylesheet" href="addbooks.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <div class="login-container">
        
        <!-- Back Button -->
        <a href="admindashboard.php?" class="btn btn-danger" style="display: inline-block; padding: 10px 20px; background-color: #d9534f; color: #fff; text-decoration: none; border: none; border-radius: 5px;">Back</a>

        <form class="login-form" action="addbooks_db.php" method="POST" enctype="multipart/form-data">
            <h2>Add Book</h2>

            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" id="title" name="Title" placeholder="Enter book title" required />
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="Author" placeholder="Enter author name" required />
            </div>
            <div class="form-group">
            <label for="isbn">ISBN</label>
<input type="text" name="isbn" id="isbn" required>

</div>

            <div class="form-group">
                <label for="genre">Select Genres</label>
                <select id="genre" name="Genre[]" multiple required>
                    <option value="Romance">Romance</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Romantasy">Romantasy</option>
                    <option value="Autobiography">Autobiography</option>
                    <option value="Distopian">Distopian</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Adventure Sports">Adventure</option>
                    <option value="Sports">Sports</option>
                    <option value="Self Help">Self Help</option>
                    <option value="Horror">Horror</option>
                    <option value="Classic">Classic</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pdf_file">Upload PDF</label>
                <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required />
            </div>

            <div class="form-group">
                <label for="book">Description Book</label>
                <textarea id="book" name="Description" placeholder="Enter book description" rows="4" maxlength="3000" required></textarea>
            </div>

            <div class="form-group">
                <label for="access_type">Access Type</label>
                <select id="access_type" name="AccessType" required>
                    <option value="0">Free</option>
                    <option value="1">Premium</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price (RS)</label>
                <input type="number" id="price" name="Price" placeholder="Enter price for premium books"  required />
            </div>

            <h2>Upload Book Cover</h2>
            <div class="show_grid">
                <div class="userinput">
                <input type="file" name="image" accept="image/*" required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="add_book" id="add_book">Add Book</button>
        </form>
    </div>
</body>
</html>
