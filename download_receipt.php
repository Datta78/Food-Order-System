<?php
ob_start();
error_reporting(0);

require('fpdf186/fpdf.php');
include("db.php");

/* GET DATE */

$date = isset($_GET['date']) ? $_GET['date'] : '';

if($date == ''){
die("Invalid Order");
}

/* ORDER DATA */

$query = "SELECT * FROM orders WHERE created_at='$date'";
$result = mysqli_query($conn,$query);

if(!$result || mysqli_num_rows($result)==0){
die("Order not found");
}

$order = mysqli_fetch_assoc($result);

/* CUSTOMER INFO */

$customer_name = $order['customer_name'];
$mobile = $order['mobile'];

$order_date = date("d M Y h:i A",strtotime($order['created_at']));

/* PDF */

$pdf = new FPDF();
$pdf->AddPage();

/* LOGO */

$pdf->Image('images/logo.png',10,10,25);

/* TITLE */

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Online Food Ordering',0,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,'123 Food Street, Pune',0,1,'C');
$pdf->Cell(0,6,'Phone: +91 9876543210',0,1,'C');

$pdf->Ln(5);
$pdf->Cell(0,0,'','T',1);
$pdf->Ln(8);

/* CUSTOMER INFORMATION */

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Customer Information",0,1);

$pdf->SetFont('Arial','',11);

$pdf->Cell(100,7,"Customer Name: ".$customer_name,0,0);
$pdf->Cell(0,7,"Order Date: ".$order_date,0,1);

$pdf->Cell(100,7,"Mobile: ".$mobile,0,1);

$pdf->Ln(8);

/* TABLE HEADER */

$pdf->SetFont('Arial','B',12);

$pdf->Cell(80,10,"Item",1);
$pdf->Cell(30,10,"Price",1);
$pdf->Cell(30,10,"Qty",1);
$pdf->Cell(50,10,"Total",1);

$pdf->Ln();

/* ITEMS */

$total = 0;

mysqli_data_seek($result,0);

$pdf->SetFont('Arial','',12);

while($row=mysqli_fetch_assoc($result)){

$food = $row['food_name'];
$price = $row['price'];
$qty = $row['quantity'];
$sub = $row['total_amount'];

$total += $sub;

$pdf->Cell(80,10,$food,1);
$pdf->Cell(30,10,"Rs ".$price,1);
$pdf->Cell(30,10,$qty,1);
$pdf->Cell(50,10,"Rs ".$sub,1);

$pdf->Ln();
}

/* GRAND TOTAL */

$pdf->Ln(10);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(140,10,"Grand Total",1);
$pdf->Cell(50,10,"Rs ".$total,1);

$pdf->Ln(15);

/* FOOTER */

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,"Thank you for your order!",0,1,'C');

$pdf->Output();

ob_end_flush();
exit;

?>