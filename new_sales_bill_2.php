<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');


$ones = array(
 "",
 " one",
 " two",
 " three",
 " four",
 " five",
 " six",
 " seven",
 " eight",
 " nine",
 " ten",
 " eleven",
 " twelve",
 " thirteen",
 " fourteen",
 " fifteen",
 " sixteen",
 " seventeen",
 " eighteen",
 " nineteen"
);

$tens = array(
 "",
 "",
 " twenty",
 " thirty",
 " forty",
 " fifty",
 " sixty",
 " seventy",
 " eighty",
 " ninety"
);

$triplets = array(
 "",
 " thousand",
 " million",
 " billion",
 " trillion",
 " quadrillion",
 " quintillion",
 " sextillion",
 " septillion",
 " octillion",
 " nonillion"
);

// recursive fn, converts three digits per pass
function convertTri($num, $tri) {
  global $ones, $tens, $triplets;

  // chunk the number, ...rxyy
  $r = (int) ($num / 1000);
  $x = ($num / 100) % 10;
  $y = $num % 100;

  // init the output string
  $str = "";

  // do hundreds
  if ($x > 0)
   $str = $ones[$x] . " hundred";

  // do ones and tens
  if ($y < 20)
   $str .= $ones[$y];
  else
   $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

  // add triplet modifier only if there
  // is some output to be modified...
  if ($str != "")
   $str .= $triplets[$tri];

  // continue recursing?
  if ($r > 0)
   return convertTri($r, $tri+1).$str;
  else
   return $str;
 }

// returns the number as an anglicized string
function convertNum($num) {
 $num = (int) $num;    // make sure it's an integer

 if ($num < 0)
  return "negative".convertTri(-$num, 0);

 if ($num == 0)
  return "zero";

 return convertTri($num, 0);
}


//$sales_id = $_POST["print"];
// $sales_id = $_GET["print"];
//$customer_name;
//$sales_bill_no;
//$sales_bill_date;
//$product_details_array = array();
//$total_amount = 0;
//$no_of_items;
//$product_details_array_length = 0;
//$crate_count = 0;
//$crate_unit_price = 0;
//$crate_amount = 0;


$id = $_POST["print"];
$bill_number;
$bill_date;
$vehicle_number;
$supplier_name;
$supplier_address;
$supplier_mobile_number;
$pincode;
$city;
$state;
$amount_payable;
$amount_paid;
$total_product_amount = 0;
$total_expenses = 0;
$due;
$commission_percentage;
$commission_amount;
$amount_paid_in_words;
$l_expn = 0;
$labour_charges = 0;
$unloading_grading_charges = 0;
$truck_freight = 0;
$cooly = 0;
$rmc_charges = 0;
$other_charges = 0;
$crate_count  =0;
$crate_amount =0;
$count=0;
$previous_balance=0;
$total_amount=0;
$grand_amount=0;
$sales_id=0;
$person_id=0;
$acc_Id=0;
$sql = "SELECT HKSU.*,HKS.first_name,HKS.last_name,HKS.address_line_1,
        HKS.address_line_2,HKS.mobile_number,HKS.pincode,HKC.city_name,
        HKST.state_name,HKSC.commission_percentage,HKSC.commission_amount
        FROM  `hk_sales` AS HKSU
        LEFT JOIN `hk_persons` AS HKS ON HKSU.person_id = HKS.id
        LEFT JOIN `hk_cities` AS HKC ON HKS.city_id = HKC.id
        LEFT JOIN `hk_states` AS HKST ON HKS.state_id = HKST.id
        LEFT JOIN `hk_sales_commission` AS HKSC ON HKSC.sales_id = HKSU.id
  WHERE HKSU.id = '$id'";

$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

while( $rows = mysqli_fetch_assoc($resultset) ) {
  $bill_number=$rows['bill_number'];
  $bill_date=$rows['bill_date'];
  $bill_date = date("d-m-Y", strtotime($bill_date));
  $vehicle_number = $rows['vehicle_number'];
  $supplier_name=$rows['first_name']." ".$rows['last_name'];
  $supplier_address=$rows['address_line_1']." ".$rows['address_line_2'];
  $pincode=$rows['pincode'];
  $city=$rows['city_name'];
  $state=$rows['state_name'];
  $supplier_mobile_number=$rows['mobile_number'];

}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->Image('logo.jpeg',5,5,-400);
$pdf->SetFont('Arial','BU',10);

$pdf->Cell(180 , 5 ,'SALES ACKNOWLEDGEMENT',0,0,'C');
$pdf->SetFont('Arial','',10);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(0 , 5 ,'Ph.No. STD : 08384',0,1,'R');



$pdf->SetFont('Arial','B',12);
$pdf->Cell(180 , 5 ,'M/s MUNEER AHMED',0,0,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Shop   : 226874 /226674',0,1,'R');




$pdf->SetFont('Arial','B',10);
$pdf->Cell(180 , 5 ,'Fruit Merchants & Commission Agents',0,0,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Resi    : 235274 /238763',0,1,'R');




$pdf->SetFont('Arial','',10);
$pdf->Cell(180 , 5 ,'Hubli Road, SIRSI - 581401 (N. K.)',0,0,'C');





$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Mobile    :    9448136674',0,1,'R');

$pdf->Line(10, 30, 220-20, 30);



$pdf->SetFont('Arial','',10);

$pdf->Cell(20 , 5 ,'Name : ',0,0);
$pdf->Cell(112 , 5 ,$supplier_name,0,0);

$pdf->Cell(10 , 5 ,'Bill No. : ',0,0);
$pdf->Cell(48, 5 ,$bill_number,0,1,'R');

$pdf->Cell(20 , 5 ,'Address : ',0,0);
$pdf->Cell(112 , 5 ,$supplier_address,0,0);

$pdf->Cell(10 , 5 ,'Billing Date : ',0,0);
$pdf->Cell(48, 5 ,$bill_date,0,1,'R');

$pdf->Cell(20 , 5 ,'',0,0);
$pdf->Cell(112 , 5 ,$city." ".$state."-".$pincode,0,0);

$pdf->Cell(10 , 5 ,'Truck No. : ',0,0);
$pdf->Cell(48 , 5 ,$vehicle_number,0,1,'R');


$pdf->Line(10, 45, 220-20, 45);



$pdf->Cell(131 , 5,'',0,1);

$pdf->Cell(190,3,'',0,1);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(18, 5 ,'Sl.No.',1,0,'C');
$pdf->Cell(57 , 5 ,'Product Name',1,0,'C');
$pdf->Cell(30 , 5 ,'QTY',1,0,'C');

$pdf->Cell(30 , 5 ,'Rate',1,0,'C');
$pdf->Cell(54 , 5 ,'Amount',1,1,'C');


$pdf->SetFont('Arial','',10);

$query1 = "SELECT HKSS.*,HKS.name,HKS.type,HKS.quantity_type from `hk_sales_products` AS HKSS
          LEFT JOIN `hk_products` AS HKS ON HKSS.product_id = HKS.id
          WHERE HKSS.sales_id = '$id'";
$exe = mysqli_query($conn,$query1);
$x=1;
while($row = mysqli_fetch_array($exe)){
  $pdf->Cell(18, 5 ,$x,1,0,'C');
  $pdf->Cell(57 , 5 ,$row['name']." ".$row['type'],1,0,'L');
  $pdf->Cell(30 , 5 ,$row['quantity']." ".$row['quantity_type'],1,0,'C');

  $pdf->Cell(30 , 5 ,$row['rate'],1,0,'C');
  $pdf->Cell(54 , 5 ,$row['amount'],1,1,'C');
  $total_product_amount = $total_product_amount+$row['amount'];
 

$x++;
}
$count=$x-1;
$pdf->SetFont('','B',10);
$pdf->Cell(105,6,'No. Of Items : '.$count ,1,0);

$pdf->Cell(84,6,'Total :                                         '.$total_product_amount ,1,1,'L');


$pdf->Cell(190,3,'',0,1);

$pdf->Cell(40 ,3,'Amount in words : ',0,0);
$pdf->SetFont('','U');
$amount_paid_in_words = convertNum($total_product_amount);
$pdf->Cell(150,3,ucwords($amount_paid_in_words)." only",0,1);
$pdf->Cell(190,5,'',0,1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(133 , 6,'Party Signature',0,1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(133 , 5 ,'E. & O. E.',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(48,5,'For',0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(7,5,'MUNEER AHMED',0,1,'R');

$pdf->Output();

?>
