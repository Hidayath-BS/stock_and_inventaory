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


$sales_id = $_POST["print"];
$bill_number;
$bill_date;
$vehicle_number;
$supplier_name;
$supplier_address;
$supplier_mobile_number;
$pincode;
$city;
$state;
$total_product_amount = 0;
$count=0;
$previous_balance=0;
$grand_amount=0;
$person_id;
$acc_Id =0;

$sql = "SELECT HKSU.*,HKS.first_name,HKS.last_name,HKS.address_line_1,
        HKS.address_line_2,HKS.mobile_number,HKS.pincode,HKC.city_name,
        HKST.state_name,HKSC.commission_percentage,HKSC.commission_amount
        FROM  `hk_sales` AS HKSU
        LEFT JOIN `hk_persons` AS HKS ON HKSU.person_id = HKS.id
        LEFT JOIN `hk_cities` AS HKC ON HKS.city_id = HKC.id
        LEFT JOIN `hk_states` AS HKST ON HKS.state_id = HKST.id
        LEFT JOIN `hk_sales_commission` AS HKSC ON HKSC.sales_id = HKSU.id
  WHERE HKSU.id = '$sales_id'";

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
          WHERE HKSS.sales_id = '$sales_id'";
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



$personIdQuery = "SELECT person_id,crate_total_amount,bill_number,bill_date FROM `hk_sales` WHERE id = '$sales_id'";
	$exe = mysqli_query($conn,$personIdQuery);
	while($row = mysqli_fetch_array($exe)){
			$person_id = $row['person_id'];
			$crate_amount = $row['crate_total_amount'];
			$bill_number = $row['bill_number'];
			$bill_date = $row['bill_date'];
	}


	
	// get id of row from person hk_account

	$getIdQ = "SELECT id FROM `hk_account_$person_id` WHERE `particulars` LIKE '%Bill No: $bill_number'";
	$getIdExe = mysqli_query($conn,$getIdQ);

	while ($getIdRow = mysqli_fetch_array($getIdExe)) {
		// code...
		$acc_Id = $getIdRow["id"];

	}


	$previousBalanceQuery = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$person_id."` WHERE date <'$bill_date' AND `active`=1";


 $exe = mysqli_query($conn,$previousBalanceQuery) or die("database error:". mysqli_error($conn));
   if(mysqli_num_rows($exe)==0){


		$row['debit']=0;
        $row['credit']=0;

	}
	else{
		while($row = mysqli_fetch_array($exe)){
          $previous_balance = ($row['debit']-$row['credit']);
		
			}
	}

if($previous_balance < 0){
$previous_balance = $previous_balance * (-1);
}
else
{
	$previous_balance = $previous_balance;
}

//$total_amount = $total_amount + $total_product_amount;
$grand_total = $total_product_amount + $previous_balance;
$pdf->Cell(105,6,'' ,0,0);
$pdf->Cell(84,6,'Previous Balance :                    '.$previous_balance,1,1,'L');
$pdf->Cell(105,6,'' ,0,0);
$pdf->Cell(84,6,'Grand Total Amount :              '.$grand_total,1,1,'L');

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


//setlocale(LC_MONETARY, 'en_IN');
//
//class PDF extends FPDF
//{
//	// Page header
//	function Header()
//	{
//		//Border
//		require ('dbconnect.php');
//		$customer_name;
//		$sales_bill_no;
//		$sales_bill_date;
//		$sales_id = $_POST["print"];
//		$query = "SELECT HKS.bill_date,HKS.bill_number,HKP.first_name,HKP.last_name FROM `hk_sales` AS HKS
//		LEFT JOIN `hk_persons` AS HKP ON HKS.person_id = HKP.id WHERE HKS.id = '$sales_id'";
//		$exe = mysqli_query($conn,$query);
//		while($row = mysqli_fetch_array($exe)){
//			$sales_bill_no = $row["bill_number"];
//			$sales_bill_date = date("d-m-Y", strtotime($row["bill_date"]));
//			$customer_name = $row["first_name"]." ".$row["last_name"];
//		}
//
//		
//		$this->SetFont('Times','B',15);
//		
//		$this->Cell(0, -5 , '', 0, 1,'C');
//
//		$this->Ln(-1);
//		$this->SetFont('Times','',11);
//		
//		$this->Cell(0 , 5 , '', 0, 1,'C');
//		$this->SetFont('Times','',11);
//		$this->Cell(0 , 3, $sales_bill_date, 0, 1,'R');
//		
//
//		$this->Cell(10,4, "", 0, 0,'L');
//		$this->Cell(44,4, $customer_name, 0, 0,'L');
//		$this->SetFont('Times','B',12);
//		$this->Cell(0 , 5, 'Bill No : '.$sales_bill_no, 0, 1,'R');
//		$this->SetFont('Times','B',10);
//		$this->Cell(3,5,"",0,0,'C');
//		
//		$this->Cell(35 , 5, '', 0, 0,'C');
//		$this->Cell(18 , 5, '', 0, 0,'C');
//		$this->Cell(16 , 5, '', 0, 0,'C');
//		$this->Cell(16 , 5, '', 0, 0,'C');
//		$this->Cell(28.8 , 5, '', 0, 1,'C');
//
//		$this->Ln(5);
//
//	}
//
//	// Page footer
//	function Footer()
//	{
//		require ('dbconnect.php');
//		$product_details_array = array();
//		$total_amount = 0;
//		$no_of_items = 0;
//		$product_details_array_length = 0;
//		$sales_id = $_POST["print"];
//		$person_id;
//		$previous_balance = 0;
//		$grand_total = 0;
//		$crate_amount = 0;
//
//		$query  = "SELECT HKP.name,HKP.type,HKP.quantity_type,HKSP.* FROM `hk_sales_products` AS HKSP
//		LEFT JOIN `hk_products` AS HKP ON HKSP.product_id = HKP.id
//		WHERE HKSP.sales_id = '$sales_id'";
//		$exe = mysqli_query($conn,$query);
//		$product_details_array_length = mysqli_num_rows($exe);
//		// $x = 0;
//		while($row = mysqli_fetch_array($exe)){
//					$total_amount = $total_amount+$row['amount'];
//			// $x++;
//		}
//
//		$personIdQuery = "SELECT person_id,crate_total_amount,bill_number,bill_date FROM `hk_sales` WHERE id = '$sales_id'";
//	$exe = mysqli_query($conn,$personIdQuery);
//	while($row = mysqli_fetch_array($exe)){
//			$person_id = $row['person_id'];
//			$crate_amount = $row['crate_total_amount'];
//			$bill_number = $row['bill_number'];
//			$bill_date = $row['bill_date'];
//	}
//
//
//	// get id of row from person hk_account
//
//	$getIdQ = "SELECT id FROM `hk_account_$person_id` WHERE `particulars` LIKE '%Bill No: $bill_number'";
//	$getIdExe = mysqli_query($conn,$getIdQ);
//
//	while ($getIdRow = mysqli_fetch_array($getIdExe)) {
//		// code...
//		$acc_Id = $getIdRow["id"];
//
//	}
//
//
//	$previousBalanceQuery = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$person_id."` WHERE id<$acc_Id AND date <='$bill_date' AND `active`='1'";
//
//
//	$exe = mysqli_query($conn,$previousBalanceQuery);
//
//	if(mysqli_num_rows($exe)==0){
//		$previous_balance = 0;
//
//	}
//	else{
//		while($row = mysqli_fetch_array($exe)){
//		 $previous_balance = ($row['debit']-$row['credit']);
//			}
//	}
//
//
//
//
//if($previous_balance < 0){
//$previous_balance = $previous_balance * (-1);
//}
//else{
//	$previous_balance = 0;
//}
//
//$total_amount = $total_amount + $crate_amount;
//$grand_total = $total_amount + $previous_balance;
//
//		// Position at 1.5 cm from bottom
//		$this->SetY(-21);
//		// Arial italic 8
//		$this->SetFont('Times','I',8);
//		// Page number
//		// $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
//		$this->Cell(3,5,"",0,0,'C');
//		$this->SetFont('Times','B',10);
//		$this->Cell(30 , 5, "No.of Items ", 0, 0,'R');
//		$this->Cell(10 , 5, $product_details_array_length, 0, 0,'R');
//		// $this->Cell(45 , 5, "Total", 1, 0,'R');
//		$this->Cell(45 , 5, '', 0, 0,'R');
////		$this->Cell(29 , 5, money_format('%!i',$total_amount), 0, 0,'R');
//        	$this->Cell(29 , 5, $total_amount, 0, 0,'R');
//		$this->Ln(7);
//		$this->Cell(3,5,"",0,0,'C');
//		// $this->Cell(85 , 5, "Previous Balance", 1, 0,'R');
//		$this->Cell(85 , 5, '', 0, 0,'R');
////		$this->Cell(29 , 5, money_format('%!i',$previous_balance), 0, 0,'R');
//     
//        $this->Cell(29 , 5, $previous_balance, 0, 0,'R');
//		$this->Ln(7);
//		$this->Cell(3,5,"",0,0,'C');
//		// $this->Cell(85 , 5, "Grand Total", 1, 0,'R');
//		$this->Cell(85 , 5, '', 0, 0,'R');
////		$this->Cell(29 , 5, money_format('%!i',$grand_total), 0, 0,'R');
//$this->Cell(29 , 5,$grand_total, 0, 0,'R');
//
//
//	}
//}
//
//
//$query  = "SELECT HKP.name,HKP.type,HKP.quantity_type,HKSP.* FROM `hk_sales_products` AS HKSP
//LEFT JOIN `hk_products` AS HKP ON HKSP.product_id = HKP.id
//WHERE HKSP.sales_id = '$sales_id'";
//$exe = mysqli_query($conn,$query);
//$product_details_array_length = mysqli_num_rows($exe);
//$x = 0;
//while($row = mysqli_fetch_array($exe)){
//	$product_details_array[$x]["product_name"] = $row['name']."".$row['type'];
//	$product_details_array[$x]["count"] = $row['quantity_type'];
//	$product_details_array[$x]["quantity"] = $row['quantity'];
//	$product_details_array[$x]["rate"] = $row['rate'];
//	$product_details_array[$x]["amount"] = $row['amount'];
//	$total_amount = $total_amount+$row['amount'];
//	$x++;
//}
//
//$crateQuery = "SELECT crate_count,crate_unit_price,crate_total_amount FROM `hk_sales` WHERE id = '$sales_id'";
//$exe = mysqli_query($conn,$crateQuery);
//while($row = mysqli_fetch_array($exe)){
//		$crate_count = $row['crate_count'];
//		$crate_unit_price = $row['crate_unit_price'];
//		$crate_amount = $row['crate_total_amount'];
//}
//
//                // $pdf = new FPDF('L','mm',array(107, 133));
//$pdf = new PDF('L','mm',array(107, 133));
//$pdf->AliasNbPages();
//
//$pdf->AddPage();
//
//$pdf->SetFont('Times','',11);
//
//for($x=0;$x<$product_details_array_length;$x++){
//	$pdf->Cell(3,5,"",0,0,'C');
//	$pdf->Cell(35 , 5, $product_details_array[$x]["product_name"], 0, 0,'L');
//	$pdf->Cell(18 , 5, $product_details_array[$x]["count"], 0, 0,'L');
//	$pdf->Cell(12 , 5, $product_details_array[$x]["quantity"], 0, 0,'R');
//	$pdf->Cell(24 , 5, $product_details_array[$x]["rate"], 0, 0,'R');
//    $pdf->Cell(24.8 , 5, $product_details_array[$x]["amount"], 0, 1,'R');
////	$pdf->Cell(24.8 , 5, money_format('%!i',$product_details_array[$x]["amount"]), 0, 1,'R');
//}
//
//if($crate_count != 0){
//	$pdf->Cell(3,5,"",0,0,'C');
//	$pdf->Cell(35 , 5, 'Crates', 0, 0,'L');
//	$pdf->Cell(18 , 5, '', 0, 0,'L');
//	$pdf->Cell(12 , 5, $crate_count, 0, 0,'R');
//	$pdf->Cell(24 , 5, $crate_unit_price, 0, 0,'R');
//    	$pdf->Cell(24.8 , 5, $crate_amount, 0, 1,'R');
////	$pdf->Cell(24.8 , 5, money_format('%!i',$crate_amount), 0, 1,'R');
//}
//
//$pdf->Output();

?>
