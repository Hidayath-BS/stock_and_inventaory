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

$id = $_POST["print"];
// $id = 1;
$bill_number;
$bill_date;
$vehicle_number;
$customer_name;
$customer_address;
$customer_mobile_number;
$pincode;
$city;
$state;
$amount_receivable;
$amount_received;
$total_product_amount = 0;
$total_expenses = 0;
$balance;
$commission_percentage;
$commission_amount = 0 ;
$amount_paid_in_words;
$labour_charges = 0;
$unloading_grading_charges = 0;
$truck_freight = 0;
$cooly = 0;
$rmc_charges = 0;
$other_charges = 0;
$location;

$sql = "SELECT HKS.*,HKP.first_name,HKP.last_name,HKP.address_line_1,
        HKP.address_line_2,HKP.mobile_number,HKP.pincode,HKC.city_name,
        HKST.state_name,HKSC.commission_percentage,HKSC.commission_amount
        FROM  `hk_sales` AS HKS
        LEFT JOIN `hk_persons` AS HKP ON HKS.person_id = HKP.id
        LEFT JOIN `hk_cities` AS HKC ON HKP.city_id = HKC.id
        LEFT JOIN `hk_states` AS HKST ON HKP.state_id = HKST.id
        LEFT JOIN `hk_sales_commission` AS HKSC ON HKSC.sales_id = HKS.id
        WHERE HKS.id = '$id'";

$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
while( $rows = mysqli_fetch_assoc($resultset) ) {
  $bill_number=$rows['bill_number'];
  $bill_date=$rows['bill_date'];
  $bill_date = date("d-m-Y", strtotime($bill_date));
  $vehicle_number = $rows['vehicle_number'];
  $location = $rows['loading_location'];
  $customer_name=$rows['first_name']." ".$rows['last_name'];
  $customer_address=$rows['address_line_1']." ".$rows['address_line_2'];
  $pincode=$rows['pincode'];
  $city=$rows['city_name'];
  $state=$rows['state_name'];
  $customer_mobile_number=$rows['mobile_number'];
  $amount_receivable=$rows['total_amount'];
  $amount_received = $rows['total_amount_received'];
  $balance = $rows['sales_balance'];
  $commission_percentage= $rows['commission_percentage'];
  $commission_amount= $rows['commission_amount'];
  $amount_paid_in_words = convertNum($amount_received);
}

$expensesSql = "SELECT * from `hk_sales_expenses` WHERE sales_id = '$id'";

$resultset = mysqli_query($conn, $expensesSql) or die("database error:". mysqli_error($conn));
while( $rows = mysqli_fetch_assoc($resultset) ) {
  $total_expenses = $total_expenses + $rows['amount'];
  if($rows['expense_type_id'] == 1){
    $labour_charges = $rows['amount'];
  }
  if($rows['expense_type_id'] == 2){
    $unloading_grading_charges = $rows['amount'];
  }
  if($rows['expense_type_id'] == 3){
    $truck_freight = $rows['amount'];
  }
  if($rows['expense_type_id'] == 4){
    $cooly = $rows['amount'];
  }
  if($rows['expense_type_id'] == 5){
    $rmc_charges = $rows['amount'];
  }
  if($rows['expense_type_id'] == 6){
    $other_charges = $rows['amount'];
  }

}


$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->Image('logo.jpeg',89,18,-400);
$pdf->SetFont('Arial','BU',15);


$pdf->Cell(180 , 8 ,'INVOICE',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0 , 5 ,'From :',0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0 , 7 ,'Ph.No. STD : 08384',0,1,'R');

$pdf->Cell(180,2,'',0,1);

$pdf->SetFont('Arial','B',18);
$pdf->Cell(180 , 5 ,'M/s MUNEER AHMED',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Shop   : 226874 /226674',0,1,'R');

$pdf->SetFont('Arial','',12);
$pdf->Cell(180 , 11 ,'Wholesale Mango Suppliers & Fruit Commission Agents',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Resi    : 235274 /238763',0,1,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(180 , 11 ,'Hubli Road, SIRSI - 581401 (N. K.)',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Mobile    :    9448136674',0,1,'R');

$pdf->Line(10, 46, 220-20, 46);

$pdf->Cell(180 , 6 ,'',0,1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(130 , 8 ,'Billing Address:',0,0,'L');
$pdf->SetFont('Arial','',12);

$pdf->Cell(60 , 8 ,'Bill No. : '.$bill_number,0,1);
$pdf->Cell(130 , 8 ,'Name     : '.$customer_name,0,0);
$pdf->Cell(60 , 8 ,'Billing Date : '.$bill_date,0,1);

// $pdf->Cell(175 , 8 ,'Company Name : ',0,1);

$pdf->Cell(175 , 8 ,'Address : '.$customer_address,0,1);
$pdf->Cell(19 , 8 ,'',0,0);
$pdf->Cell(170 , 8 ,$city." ".$state." ".$pincode,0,1);

$pdf->Cell(175 , 8 ,'Phone   : '.$customer_mobile_number,0,1);
$pdf->Line(10, 89, 220-20, 89);
$pdf->Cell(190,5,'',0,1);
$pdf->Cell(140 , 5,'The following products are dispatched to you vide your order by truck No.',0,0);
// $pdf->SetFont('','U');
// $pdf->Line(38, 98, 138, 98);
// $pdf->Cell(38 , 5,'2',0,0);
// $pdf->SetFont('Arial','',12);

// $pdf->Cell(18 , 5,'products are dispatched to you vide your ',0,1);
// $pdf->Cell(35 , 13,'order by truck No.',0,0);
$pdf->SetFont('','U');
$pdf->Cell(30, 5,$vehicle_number, 0,1);
$pdf->SetFont('Arial','',12);
//$pdf->Cell(70 , 13,'D. C. No.',0,1);
//$pdf->Line(146, 107, 200, 107);
$pdf->Cell(14 , 13,'Dated',0,0);
$pdf->SetFont('','U');
$pdf->Cell(25, 13,$bill_date,0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(28 , 13,'Dispatched to',0,0);
$pdf->SetFont('','U');
$pdf->Cell(34, 13,$location,0,1);
$pdf->SetFont('Arial','',12);

$pdf->Cell(190,3,'',0,1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(18 , 7 ,'Sl No.',1,0,'C');
$pdf->Cell(60 , 7 ,'Product Name',1,0,'C');
$pdf->Cell(30 , 7 ,'QTY',1,0,'C');
$pdf->Cell(25 , 7 ,'Rate',1,0,'C');
$pdf->Cell(57 , 7 ,'Amount',1,1,'C');

$pdf->SetFont('Arial','',12);

$query1 = "SELECT HKSP.*,HKP.name,HKP.type,HKP.quantity_type from `hk_sales_products` AS HKSP
          LEFT JOIN `hk_products` AS HKP ON HKSP.product_id = HKP.id
          WHERE HKSP.sales_id = '$id'";
$exe = mysqli_query($conn,$query1);
$x=1;
while($row = mysqli_fetch_array($exe)){
  $pdf->Cell(18, 7 ,$x,1,0,'C');
  $pdf->Cell(60 , 7 ,$row['name']." ".$row['type'],1,0,'C');
  $pdf->Cell(30 , 7 ,$row['quantity']." ".$row['quantity_type'],1,0,'C');
  $pdf->Cell(25 , 7 ,$row['rate'],1,0,'C');
  $pdf->Cell(57 , 7 ,$row['amount'],1,1,'C');
  $total_product_amount = $total_product_amount+$row['amount'];
  $x++;
}

$pdf->SetFont('Arial','B',12);
$pdf->Cell(133,8,'',1,0,'C');
$pdf->Cell(30, 8 ,'Total : ',1,0);
$pdf->Cell(27, 8 ,$total_product_amount,1,1,'C');

$pdf->Cell(133,8,'Commission :  @    '.$commission_percentage." %",1,0);
// $pdf->Cell(40,8,$commission_amount,1,0,'C');
$pdf->Cell(30,8,'CA. : ',1,0);
$pdf->Cell(27, 8 ,$commission_amount,1,1,'C');

$pdf->Cell(133,8,'Expenses',1,0,'C');
$pdf->Cell(30, 8 ,'L.Expn. : ',1,0);
$pdf->Cell(27, 8 ,$total_expenses,1,1,'C');

$pdf->Cell(93,8,'Labour Charges : ',1,0);
$pdf->Cell(40,8,$labour_charges,1,0,'C');
$pdf->Cell(30, 8 ,'N.Amt. : ',1,0);
$pdf->Cell(27, 8 ,$amount_receivable,1,1,'C');

$pdf->Cell(93,8,'Unloading and Grading Charges : ',1,0);
$pdf->Cell(40,8,$unloading_grading_charges,1,0,'C');
$pdf->Cell(30,8,'Amt Rcvd. : ',1,0);
$pdf->Cell(27,8,$amount_received,1,1,'C');

$pdf->Cell(93,8,'Truck Freight : ',1,0);
$pdf->Cell(40,8,$truck_freight,1,0,'C');
$pdf->Cell(30, 8 ,'Balance : ',1,0);
$pdf->Cell(27, 8 ,$balance,1,1,'C');

$pdf->Cell(93,8,'Cooly : ',1,0);
$pdf->Cell(40,8,$cooly,1,0,'C');
$pdf->Cell(57, 8 ,'',1,1);

$pdf->Cell(93,8,'RMC Charges :',1,0);
$pdf->Cell(40,8,$rmc_charges,1,0,'C');
$pdf->Cell(57, 8 ,'',1,1);

$pdf->Cell(93,8,'Other Charges :',1,0);
$pdf->Cell(40,8,$other_charges,1,0,'C');
$pdf->Cell(57, 8 ,'',1,1);

$pdf->Cell(93,8,'Total : ',1,0);
$pdf->Cell(40,8,$total_expenses,1,0,'C');
$pdf->Cell(57, 8 ,'',1,1);

$pdf->Cell(190,5,'',0,1);

$pdf->Cell(40 ,5,'Amount in words : ',0,0);
$pdf->SetFont('','U');
$pdf->Cell(150 ,5,ucwords($amount_paid_in_words)." only",0,1);
$pdf->Cell(190,10,'',0,1);

//$pdf->Cell(5,8,'',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(133 , 8,'Party Signature',0,1);


//$pdf->Cell(190,4,'',0,1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(133 , 5 ,'E. & O. E.',0,0,'L');
$pdf->SetFont('Arial','B',12);
//$pdf->Cell(133,7,'',0,0);
$pdf->Cell(48,5,'For',0,0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(7,5,'MUNEER AHMED',0,1,'R');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(185 , 5 ,'Subject to SIRSI Jurisdiction',0,1,'C');
$pdf->Output();

?>
