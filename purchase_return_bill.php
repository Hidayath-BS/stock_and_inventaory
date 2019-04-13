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

 //  example of usage
 // echo "1111029 : ".convertNum(1111029)."<br>";
 // echo "1209 : ".convertNum(1209);


$id = $_POST["print"];
$purchase_bill_number;
$purchase_return_bill_number;
$bill_date;
$supplier_name;
$supplier_address;
$supplier_mobile_number;
$pincode;
$city;
$state;
$amount_receivable;
$amount_received;
$total_product_amount = 0;
$balance;
$amount_received_in_words;

$sql = "SELECT HKPRR.*,HKPR.bill_number as purchase_bill_no,HKP.first_name,HKP.last_name,HKP.address_line_1,
        HKP.address_line_2,HKP.mobile_number,HKP.pincode,HKC.city_name,
        HKST.state_name
        FROM  `hk_purchases_return` AS HKPRR
        LEFT JOIN `hk_purchases` AS HKPR ON HKPRR.purchase_id = HKPR.id
        LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
        LEFT JOIN `hk_cities` AS HKC ON HKP.city_id = HKC.id
        LEFT JOIN `hk_states` AS HKST ON HKP.state_id = HKST.id
        WHERE HKPRR.id = '$id'";

$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
while( $rows = mysqli_fetch_assoc($resultset) ) {
  $purchase_bill_number=$rows['purchase_bill_no'];
  $bill_date=$rows['date'];
  $bill_date = date("d-m-Y", strtotime($bill_date));
  $purchase_return_bill_number = $rows['purchase_return_bill_number'];
  $supplier_name=$rows['first_name']." ".$rows['last_name'];
  $supplier_address=$rows['address_line_1']." ".$rows['address_line_2'];
  $pincode=$rows['pincode'];
  $city=$rows['city_name'];
  $state=$rows['state_name'];
  $supplier_mobile_number=$rows['mobile_number'];
  $amount_receivable=$rows['return_amount'];
  $amount_received = $rows['amount_recieved'];
  $balance = $rows['balance'];
  $amount_received_in_words = convertNum($amount_received);
}


$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->Image('logo.jpeg',10,17,-300);
$pdf->SetFont('Arial','BU',15);

$pdf->Cell(180 , 8 ,'PURCHASE RETURN ACKNOWLEDGEMENT',0,1,'C');
$pdf->SetFont('Arial','',12);
//$pdf->Cell(0 , 5 ,'From :',0,0,'L');


$pdf->Cell(180,2,'',0,1);

$pdf->SetFont('Arial','B',18);
$pdf->Cell(180 , 8 ,'M/s MUNEER AHMED',0,0,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0 , 5 ,'Ph.No. STD : 08384',0,1,'R');



$pdf->SetFont('Arial','B',12);
$pdf->Cell(180 , 14 ,'Fruit Merchants & Commission Agents',0,0,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Shop   : 226874 /226674',0,1,'R');




$pdf->SetFont('Arial','',12);
$pdf->Cell(180 , 14 ,'Hubli Road, SIRSI - 581401 (N. K.)',0,0,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Resi    : 235274 /238763',0,1,'R');



$pdf->SetFont('Arial','',10);
$pdf->Cell(0 , 5 ,'Mobile    :    9448136674',0,1,'R');

$pdf->Line(10, 41, 220-20, 41);

$pdf->Cell(180 , 5 ,'',0,1);

$pdf->SetFont('Arial','',12);
//$pdf->Cell(140 , 8 ,'Billing Address:',0,0,'L');
$pdf->Cell(20 , 8 ,'Name : ',0,0);
$pdf->Cell(90 , 8 ,$supplier_name,0,0);

$pdf->Cell(50 , 8 ,'Purchase Return Bill No. : ',0,0,'R');
$pdf->Cell(30, 8 ,$purchase_return_bill_number,0,1);

$pdf->Cell(20 , 8 ,'Address : ',0,0);
$pdf->Cell(90 , 8 ,$supplier_address,0,0);

$pdf->Cell(50 , 8 ,'Billing Date : ',0,0,'R');
$pdf->Cell(30, 8 ,$bill_date,0,1);

$pdf->Cell(20 , 8 ,'',0,0);
$pdf->Cell(90 , 8 ,$city." ".$state."-".$pincode,0,0);

$pdf->Cell(50 , 8 ,'Purchase Bill No. : ',0,0,'R');
$pdf->Cell(30 , 8 ,$purchase_bill_number,0,1);

$pdf->Cell(20 , 8 ,'Phone : ',0,0);
$pdf->Cell(112 , 8 ,$supplier_mobile_number,0,1);

$pdf->Line(10, 78, 220-20, 78);

$pdf->Cell(190,6,'',0,1);

$pdf->Cell(131 , 5,'We have Returned your following goods ',0,1);

$pdf->Cell(190,6,'',0,1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(18, 7 ,'Sl.No.',1,0,'C');
$pdf->Cell(65 , 7 ,'Product Name',1,0,'C');
$pdf->Cell(25 , 7 ,'QTY',1,0,'C');
$pdf->Cell(25 , 7 ,'Rate',1,0,'C');
$pdf->Cell(57 , 7 ,'Amount',1,1,'C');
//$pdf->Cell(47 , 8 ,'Expenses',1,1,'C');

$pdf->SetFont('Arial','',12);

$query1 = "SELECT HKPRP.*,HKP.name,HKP.type,HKP.quantity_type from `hk_purchase_return_products` AS HKPRP
          LEFT JOIN `hk_products` AS HKP ON HKPRP.product_id = HKP.id
          WHERE HKPRP.purchase_return_id = '$id'";
$exe = mysqli_query($conn,$query1);
$x=1;
while($row = mysqli_fetch_array($exe)){
  $pdf->Cell(18, 7 ,$x,1,0,'C');
  $pdf->Cell(65 , 7 ,$row['name']." ".$row['type'],1,0,'C');
  $pdf->Cell(25 , 7 ,$row['quantity']." ".$row['quantity_type'],1,0,'C');
  $pdf->Cell(25 , 7 ,$row['rate'],1,0,'C');
  $pdf->Cell(57 , 7 ,$row['amount'],1,1,'C');
  $total_product_amount = $total_product_amount+$row['amount'];
$x++;
}


$pdf->SetFont('Arial','B',12);
$pdf->Cell(133, 8 ,'Total : ',1,0);
$pdf->Cell(57, 8 ,$total_product_amount,1,1,'C');

$pdf->Cell(133, 8 ,'Amount Received : ',1,0);
$pdf->Cell(57, 8 ,$amount_received,1,1,'C');

$pdf->Cell(133, 8 ,'Balance : ',1,0);
$pdf->Cell(57, 8 ,$balance,1,1,'C');



$pdf->Cell(190,5,'',0,1);
$pdf->Cell(40 ,5,'Amount in words : ',0,0);
$pdf->SetFont('','U');
$pdf->Cell(150,5,ucwords($amount_received_in_words)." only",0,1);
$pdf->Cell(190,30,'',0,1);

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
//$pdf->SetFont('Arial','B',9);
//$pdf->Cell(185 , 5 ,'Subject to SIRSI Jurisdiction',0,1,'C');
$pdf->Output();

?>
