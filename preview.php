<?php
session_start();
if(!isset($_SESSION["User_Id"])){
    header("Location:login.php");
    exit();
}
require_once('fpdf18/fpdf.php');
require_once('FPDI-2.6.3/src/autoload.php');
use setasign\Fpdi\Fpdi;

$conn = mysqli_connect('localhost:3307', 'root', '', 'project6');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
if ($book_id == 0) {
    die("Invalid Book ID.");
}

$sql = "SELECT title, pdflink FROM book WHERE book_id = $book_id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);
if (!$book) {
    die("Book not found.");
}

$title = $book['title'];
$pdf_link = 'Uploads/' . $book['pdflink'];

$preview_file = 'Previews/Preview_' . $book_id . '.pdf'; // Save in Previews folder

$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($pdf_link);
$previewPages = min(4, $pageCount);

for ($i = 1; $i <= $previewPages; $i++) {
    $templateId = $pdf->importPage($i);
    $size = $pdf->getTemplateSize($templateId);
    $pdf->addPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($templateId);
}

$pdf->Output('F', $preview_file); // Save to file

// Redirect to preview display page
header("Location: preview_page.php?book_id=$book_id");
exit;
?>
