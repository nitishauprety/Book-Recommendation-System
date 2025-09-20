<?php
session_start();
if(!isset($_SESSION["User_Id"])){
    header("Location:login.php");
    exit();
}
$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

$sql = "SELECT title FROM book WHERE book_id = $book_id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);
$title = $book ? $book['title'] : 'Book Preview';
$preview_file = 'Previews/Preview_' . $book_id . '.pdf';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?> - Preview</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
            text-align: center;
            background-color: #fdf6f0;
            color: #5c4b36;
        }
        iframe {
            width: 80%;
            height: 600px;
            border: 1px solid #A67C52;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            margin: 20px 10px;
            padding: 10px 20px;
            background-color: #A67C52;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #8B5E3C;
        }
    </style>
</head>
<body>

    <h2>Preview: <?php echo htmlspecialchars($title); ?></h2>

    <?php if (file_exists($preview_file)): ?>
        <iframe src="<?php echo $preview_file; ?>"></iframe>
    <?php else: ?>
        <p>Preview not available.</p>
    <?php endif; ?>

    <br>
    <a href="homepage.php" class="btn">← Back to Home</a>
    <a href="purchase.php?book_id=<?php echo $book_id; ?>" class="btn">Buy Now to Download Full Book</a>

</body>
</html>
