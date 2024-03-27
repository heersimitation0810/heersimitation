<?php
session_start();
include_once("config.php");
require_once 'vendor/autoload.php'; 

$imitation = new imitation();
use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'];
ob_start();
include_once 'invoice1.php';
$html = ob_get_clean();

$options = new Options();
$options->setChroot(__DIR__);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->render();
$pdf_content = $dompdf->output();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="#0000'. base64_decode($id) .'.pdf"');
header('Content-Length: ' . strlen($pdf_content));

echo $pdf_content;

$file_path = $id . '_invoice.pdf';
file_put_contents($file_path, $pdf_content);
?>

