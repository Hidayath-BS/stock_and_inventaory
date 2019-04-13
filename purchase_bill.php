<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
//setlocale(LC_MONETARY, 'en_IN');
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


$sql = "SELECT HKPU.*,HKP.first_name,HKP.last_name,HKP.address_line_1,
        HKP.address_line_2,HKP.mobile_number,HKP.pincode,HKC.city_name,
        HKST.state_name,HKPC.commission_percentage,HKPC.commission_amount
        FROM  `hk_purchases` AS HKPU
        LEFT JOIN `hk_persons` AS HKP ON HKPU.person_id = HKP.id
        LEFT JOIN `hk_cities` AS HKC ON HKP.city_id = HKC.id
        LEFT JOIN `hk_states` AS HKST ON HKP.state_id = HKST.id
        LEFT JOIN `hk_purchase_commission` AS HKPC ON HKPC.purchase_id = HKPU.id
        WHERE HKPU.id = '$id'";

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
  $amount_payable=$rows['amount_payable'];
  $amount_paid = $rows['amount_paid'];
  $due = $amount_paid-$amount_payable;
  $commission_percentage= $rows['commission_percentage'];
  $commission_amount= $rows['commission_amount'];
  $amount_paid_in_words = convertNum($amount_paid);
  $crate_count = $rows["crate_count"];
  $crate_amount = $rows["crate_total_amount"];
}

$expensesSql = "SELECT * from `hk_purchase_expenses` WHERE purchase_id = '$id' && `expenses_active`=1";

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


$l_expn = $total_expenses+$commission_amount;

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->Image('logo.jpeg',5,5,-400);
$pdf->SetFont('Arial','BU',10);

$pdf->Cell(180 , 5 ,'PURCHASE ACKNOWLEDGEMENT',0,0,'C');
$pdf->SetFont('Arial','',10);
//$pdf->Cell(0 , 5 ,'From :',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0 , 5 ,'Ph.No. STD : 08384',0,1,'R');

// $pdf->Cell(180,2,'',0,0);

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

// $pdf->Cell(180 , 5 ,'',0,1);

$pdf->SetFont('Arial','',10);
//$pdf->Cell(140 , 8 ,'Billing Address:',0,0,'L');
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

// $pdf->Cell(20 , 5 ,'Phone : ',0,0);
// $pdf->Cell(112 , 5 ,$supplier_mobile_number,0,1);

$pdf->Line(10, 45, 220-20, 45);

// $pdf->Cell(190,6,'',0,1);

$pdf->Cell(131 , 5,'We have Received your goods undermentioned and sold at the highest possible market price.',0,1);

$pdf->Cell(190,3,'',0,1);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(18, 5 ,'Sl.No.',1,0,'C');
$pdf->Cell(47 , 5 ,'Product Name',1,0,'C');
$pdf->Cell(20 , 5 ,'QTY',1,0,'C');
$pdf->Cell(20 , 5 ,'PETCH',1,0,'C');
$pdf->Cell(20 , 5 ,'NET QTY',1,0,'C');
$pdf->Cell(20 , 5 ,'Rate',1,0,'C');
$pdf->Cell(45 , 5 ,'Amount',1,1,'C');
//$pdf->Cell(47 , 8 ,'Expenses',1,1,'C');

$pdf->SetFont('Arial','',10);

$query1 = "SELECT HKPP.*,HKP.name,HKP.type,HKP.quantity_type from `hk_purchased_products` AS HKPP
          LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
          WHERE HKPP.purchase_id = '$id'";
$exe = mysqli_query($conn,$query1);
$x=1;
while($row = mysqli_fetch_array($exe)){
  $pdf->Cell(18, 5 ,$x,1,0,'C');
  $pdf->Cell(47 , 5 ,$row['name']." ".$row['type'],1,0,'L');
  $pdf->Cell(20 , 5 ,$row['quantity']." ".$row['quantity_type'],1,0,'C');
  $pdf->Cell(20 , 5 ,$row['shrink']." ".$row['quantity_type'],1,0,'C');
  $pdf->Cell(20 , 5 ,$row['final_quantity']." ".$row['quantity_type'],1,0,'C');
     $pdf->Cell(20 , 5 ,$row['rate'],1,0,'R');
//  $pdf->Cell(20 , 5 ,money_format('%!i',$row['rate']),1,0,'R');
//  $pdf->Cell(45 , 5 ,money_format('%!i',$row['amount']),1,1,'R');
      $pdf->Cell(45 , 5 ,$row['amount'],1,1,'R');
  $total_product_amount = $total_product_amount+$row['amount'];
$x++;
}


$pdf->SetFont('Arial','B',10);
$pdf->Cell(133,7,'Expenses',1,0,'C');
$pdf->Cell(30, 7 ,'Total : ',1,0);
//$pdf->Cell(27, 7 ,money_format('%!i',$total_product_amount),1,1,'R');
$pdf->Cell(27, 7 ,$total_product_amount,1,1,'R');
//$pdf->Cell(93,6,'Commission :  @    '.$commission_percentage." %",1,0);
$pdf->Cell(93,6,'Commission :',1,0);
//$pdf->Cell(40,6,money_format('%!i',$commission_amount),1,0,'R');
$pdf->Cell(40,6,$commission_amount,1,0,'R');
$pdf->Cell(30, 6 ,'L.Expn. : ',1,0);
//$pdf->Cell(27, 6 ,money_format('%!i',$l_expn+$crate_amount),1,1,'R');
$pdf->Cell(27, 6 ,$l_expn+$crate_amount,1,1,'R');
$pdf->Cell(93,6,'Labour Charges : ',1,0);
//$pdf->Cell(40,6,money_format('%!i',$labour_charges),1,0,'R');
$pdf->Cell(40,6,$labour_charges,1,0,'R');
$pdf->Cell(30, 6 ,'N.Amt. : ',1,0);
//$pdf->Cell(27, 6 ,money_format('%!i',$amount_payable),1,1,'R');
$pdf->Cell(27, 6 ,$amount_payable,1,1,'R');
$pdf->Cell(93,6,'Truck Freight : ',1,0);
//$pdf->Cell(40,6,money_format('%!i',$truck_freight),1,0,'R');
$pdf->Cell(40,6,$truck_freight,1,0,'R');
$pdf->Cell(30, 6 ,'Amount Paid : ',1,0);

$pdf->Cell(27, 6 ,$amount_paid,1,1,'R');
$pdf->Cell(93,6,'Crate Charges ( '.$crate_count.' ) :',1,0);
//$pdf->Cell(40,6,money_format('%!i',$crate_amount),1,0,'R');
$pdf->Cell(40,6,$crate_amount,1,0,'R');
$pdf->Cell(30, 6 ,'Balance : ',1,0);
//$pdf->Cell(27, 6 ,money_format('%!i',$due),1,1,'R');
$pdf->Cell(27, 6 ,$due,1,1,'R');

// $pdf->Cell(93,6,'Cooly : ',1,0);
// $pdf->Cell(40,6,money_format('%!i',$cooly),1,0,'R');
// $pdf->Cell(57, 6 ,'',1,1);

// $pdf->Cell(93,6,'RMC Charges :',1,0);
// $pdf->Cell(40,6,money_format('%!i',$rmc_charges),1,0,'R');
// $pdf->Cell(57, 6 ,'',1,1);

$pdf->Cell(93,6,'Other Charges :',1,0);
//$pdf->Cell(40,6,money_format('%!i',$other_charges),1,0,'R');
$pdf->Cell(40,6,$other_charges,1,0,'R');
$pdf->Cell(57, 6 ,'',1,1);




$pdf->Cell(93,6,'Total : ',1,0);
//$pdf->Cell(40,6,money_format('%!i',$l_expn+$crate_amount),1,0,'R');
$pdf->Cell(40,6,$l_expn+$crate_amount,1,0,'R');
$pdf->Cell(57, 6 ,'',1,1);

$pdf->Cell(190,3,'',0,1);

$pdf->Cell(40 ,3,'Amount in words : ',0,0);
$pdf->SetFont('','U');
$pdf->Cell(150,3,ucwords($amount_paid_in_words)." only",0,1);
$pdf->Cell(190,5,'',0,1);

//$pdf->Cell(5,8,'',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(133 , 6,'Party Signature',0,1);


//$pdf->Cell(190,4,'',0,1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(133 , 5 ,'E. & O. E.',0,0,'L');
$pdf->SetFont('Arial','B',10);
//$pdf->Cell(133,7,'',0,0);
$pdf->Cell(48,5,'For',0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(7,5,'MUNEER AHMED',0,1,'R');
//$pdf->SetFont('Arial','B',9);
//$pdf->Cell(185 , 5 ,'Subject to SIRSI Jurisdiction',0,1,'C');
$pdf->Output();

?>
