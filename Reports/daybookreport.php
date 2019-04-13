
<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
$ondate = $_POST["ondate"];
$fromdate = $_POST["fromdate"];
$todate = $_POST["todate"];

$actualOndate = $ondate;
$actualFromdate = $fromdate;
$actualTodate = $todate;
$dateType = $_POST["dateType"];
$ondate = date("d-m-Y", strtotime($ondate));
$fromdate = date("d-m-Y", strtotime($fromdate));
$todate = date("d-m-Y", strtotime($todate));

$selected_value = $_POST["daybook_report"];
$total_purchase_amount=0;
$purchases_credit_total=0;
$total_sale_amount=0;
$sales_credit_total=0;


setlocale(LC_MONETARY, 'en_IN');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
//    $this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    // $this->Cell(80);
    // Title
    $this->Cell(190,10,'K.ABDUL KAREEM & SONS',0,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}




if($dateType =="onDate"){

if($selected_value == "sales"){
	$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$sales_credit_total = $sales_credit_total+$row['amount'];
	}

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);

	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
		// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Sales',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(70,8,'Particulars','B',0,'C',true);
	$pdf->Cell(38,8,'Bill No.','B',0,'C',true);
	$pdf->Cell(41,8,'TYPE','B',0,'C',true);
	$pdf->Cell(41,8,'Amount','B',1,'C',true);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);


	$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
		$pdf->Cell(38,8,$row['bill_number'],0,0,'C');
		$pdf->Cell(41,8,$row['credit/cash'],0,0,'C');
		$pdf->Cell(41,8,$row['amount'],0,1,'R');

	}

	$pdf->Cell(149,8,'Total Amount :','T',0,true);
	$pdf->Cell(41,8,$sales_credit_total,'T',1,'R');
	$pdf->Ln();

	$pdf->output();
}

if($selected_value == "purchases"){
	$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$sales_credit_total = $sales_credit_total+$row['amount'];
	}

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);
	//
	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Purchases',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(70,8,'Particulars','B',0,'C',true);
	$pdf->Cell(38,8,'Bill No.','B',0,'C',true);
	$pdf->Cell(41,8,'TYPE','B',0,'C',true);
	$pdf->Cell(41,8,'Amount','B',1,'C',true);

	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);

	$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
		$pdf->Cell(38,8,$row['bill_number'],0,0,'C');
		$pdf->Cell(41,8,$row['credit/cash'],0,0,'C');
		$pdf->Cell(41,8,$row['amount'],0,1,'R');

	}

	$pdf->Cell(149,8,'Total Amount :','T',0,true);
	$pdf->Cell(41,8,$sales_credit_total,'T',1,'R');
	$pdf->Ln();

	$pdf->output();
}


if($selected_value == "sales and purchases"){
	// echo $selected_value;

	$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$sales_credit_total = $sales_credit_total+$row['amount'];
	}

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);
	//
	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Sales',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(70,8,'Particulars','B',0,'C',true);
	$pdf->Cell(38,8,'Bill No.','B',0,'C',true);
	$pdf->Cell(41,8,'TYPE','B',0,'C',true);
	$pdf->Cell(41,8,'Amount','B',1,'C',true);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);

	$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
		$pdf->Cell(38,8,$row['bill_number'],0,0,'C');
		$pdf->Cell(41,8,$row['credit/cash'],0,0,'C');
		$pdf->Cell(41,8,$row['amount'],0,1,'R');

	}

	$pdf->Cell(149,8,'Total Amount :','T',0,true);
	$pdf->Cell(41,8,$sales_credit_total,'T',1,'R');
	$pdf->Ln();

	$purchase_credit_total = 0;
	$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$purchase_credit_total = $purchase_credit_total+$row['amount'];
	}

	// $pdf= new FPDF('p','mm','A4');
	// $pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);
	//
	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Purchases',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(70,8,'Particulars','B',0,'C',true);
	$pdf->Cell(38,8,'Bill No.','B',0,'C',true);
	$pdf->Cell(41,8,'TYPE','B',0,'C',true);
	$pdf->Cell(41,8,'Amount','B',1,'C',true);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);


	$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` = '$actualOndate' AND active = 1";
	$exe = mysqli_query($conn,$query1);
	while($row = mysqli_fetch_array($exe)){
		$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
		$pdf->Cell(38,8,$row['bill_number'],0,0,'C');
		$pdf->Cell(41,8,$row['credit/cash'],0,0,'C');
		$pdf->Cell(41,8,$row['amount'],0,1,'R');

	}

	$pdf->Cell(149,8,'Total Amount :','T',0,true);
	$pdf->Cell(41,8,$purchase_credit_total,'T',1,'R');
	$pdf->Ln();



	$pdf->output();

}


if($selected_value == "payments"){
	// echo $selected_value;

$print_array = array();
$total_amount = 0;
	$query1 = "SELECT dr,particulars,date FROM hk_cash_book WHERE `cr` ='0' AND (`particulars` NOT LIKE '%CR%' AND `particulars` NOT LIKE '%Cash S To%' AND `particulars` NOT LIKE '%Cash Purchase Return%') AND `date`='$actualOndate' AND `active`='1'";



  $exe = mysqli_query($conn,$query1);
  $x = 0;
  if(mysqli_num_rows($exe) != 0){
    while($row = mysqli_fetch_array($exe)){
       $print_array[$x]["date"] = $row['date'];
       $print_array[$x]["particulars"] = $row['particulars'];
       $print_array[$x]["dr"] = $row['dr'];
       $total_amount = $total_amount+$row['dr'];
       $x++;
      }
  }



	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);
	//
	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Payments',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(20,8,'Sl No.','B',0,'C',true);
	$pdf->Cell(20,8,'Date','B',0,'C',true);
	$pdf->Cell(110,8,'Particulars','B',0,'C',true);
	$pdf->Cell(40,8,'Payments','B',1,'C',true);

	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);

  $x = 1;
  for($y=0;$y<count($print_array);$y++){
		$pdf->Cell(20,8,$x,0,0,'C');
		$pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C');
		$pdf->Cell(110,8,strtoupper($print_array[$y]["particulars"]),0,0,'L');
		$pdf->Cell(40,8,$print_array[$y]["dr"],0,1,'R');
    $x++;
  }


	$pdf->Cell(149,8,'Total :','T',0,true);
	$pdf->Cell(41,8,$total_amount,'T',1,'R');
	$pdf->Ln();

	$pdf->output();

}


if($selected_value == "receipts"){
	// echo $selected_value;

$print_array = array();
$total_amount = 0;
	$query1 = "SELECT `dr`,`cr`,`particulars`,`date` FROM `hk_cash_book`
  WHERE (`particulars` NOT LIKE '%CR%' AND `particulars` NOT LIKE '%Paid Due%' AND `particulars` NOT LIKE '%Cash P%' AND `particulars` NOT LIKE '%Supplier Advance%') AND `date`='$actualOndate' AND `active`='1'";

  $exe = mysqli_query($conn,$query1);
  $x = 0;
  if(mysqli_num_rows($exe) != 0){
    while($row = mysqli_fetch_array($exe)){
       $print_array[$x]["date"] = $row['date'];
       $print_array[$x]["particulars"] = $row['particulars'];
       $print_array[$x]["cr"] = $row['cr']+$row['dr'];
       $total_amount = $total_amount+$row['cr']+$row['dr'];
       $x++;
      }
  }



	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	// $pdf->SetFont('Arial', 'B', 20);
	// $pdf->SetTextColor(0,0,255);
	//
	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);

	$pdf->Cell(130,5,'Day Book Report of : Receipts',0,1,'L');
	$pdf->Ln();
	$pdf->Cell(20,5,'On Date : ',0,0,'L');
	$pdf->Cell(30,5,$ondate,0,1,'L');

	$pdf->Cell(190,5,'',0,1);
	$width_cell=array(20,60,20,60);
	$pdf->SetFillColor(255,255,255);


	$pdf->Cell(20,8,'Sl No.','B',0,'C',true);
	$pdf->Cell(20,8,'Date','B',0,'C',true);
	$pdf->Cell(110,8,'Particulars','B',0,'C',true);
	$pdf->Cell(40,8,'Receipts','B',1,'C',true);

	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);

  $x = 1;
  for($y=0;$y<count($print_array);$y++){
		$pdf->Cell(20,8,$x,0,0,'C');
		$pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C');
		$pdf->Cell(110,8,strtoupper($print_array[$y]["particulars"]),0,0,'C');
		$pdf->Cell(40,8,$print_array[$y]["cr"],0,1,'C');
    $x++;
  }


	$pdf->Cell(149,8,'Total :','T',0,true);
	$pdf->Cell(41,8,$total_amount,'T',1,'C');
	$pdf->Ln();

	$pdf->output();

}


}

if($dateType=="btDate"){

if(strtotime($actualTodate) < strtotime($actualFromdate)){
	echo "<script>
alert('Please Select Second Date Greater Than First Date');
window.location.href='../daybook_reports.php';
</script>";

}
else{
	// echo "correct";
	if($selected_value == "sales"){


$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
$exe = mysqli_query($conn,$query1);
while($row = mysqli_fetch_array($exe)){
	$sales_credit_total = $sales_credit_total+$row['amount'];
}

		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		// $pdf->SetFont('Arial', 'B', 20);
		// $pdf->SetTextColor(0,0,255);
		//
		// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
		// $pdf->Ln();
		$pdf->SetFont('Arial','B',12);
		$pdf->SetTextColor(0,0,0);

		$pdf->Cell(130,5,'Day Book Report of : Sales',0,1,'L');
		$pdf->Ln();
		$pdf->Cell(32,5,'Between Date : ',0,0,'L');
		$pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

		$pdf->Cell(190,5,'',0,1);
		$width_cell=array(20,60,20,60);
		$pdf->SetFillColor(255,255,255);

		$pdf->Cell(25,8,'Date','B',0,'C',true);
		$pdf->Cell(70,8,'Particulars','B',0,'C',true);
		$pdf->Cell(35,8,'Bill No.','B',0,'C',true);
		$pdf->Cell(30,8,'TYPE','B',0,'C',true);
		$pdf->Cell(30,8,'Amount','B',1,'C',true);
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(0,0,0);


		$query1 = "SELECT * FROM `hk_sales_account` WHERE `date`  BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
		$exe = mysqli_query($conn,$query1);
		while($row = mysqli_fetch_array($exe)){
			$pdf->Cell(25,8,date("d-m-Y", strtotime($row['date'])),0,0,'C');
			$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
			$pdf->Cell(35,8,$row['bill_number'],0,0,'C');
			$pdf->Cell(30,8,$row['credit/cash'],0,0,'C');
			$pdf->Cell(30,8,$row['amount'],0,1,'R');

		}

		$pdf->Cell(160,8,'Total Amount :','T',0,true);
		$pdf->Cell(30,8,$sales_credit_total,'T',1,'R');
		$pdf->Ln();

		$pdf->output();
	}

	if($selected_value == "purchases"){
		$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
		$exe = mysqli_query($conn,$query1);
		while($row = mysqli_fetch_array($exe)){
			$sales_credit_total = $sales_credit_total+$row['amount'];
		}

				$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();
				// $pdf->SetFont('Arial', 'B', 20);
				// $pdf->SetTextColor(0,0,255);
				//
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);

				$pdf->Cell(130,5,'Day Book Report of : Purchases',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(32,5,'Between Date : ',0,0,'L');
				$pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

				$pdf->Cell(190,5,'',0,1);
				$width_cell=array(20,60,20,60);
				$pdf->SetFillColor(255,255,255);

				$pdf->Cell(25,8,'Date','B',0,'C',true);
				$pdf->Cell(70,8,'Particulars','B',0,'C',true);
				$pdf->Cell(35,8,'Bill No.','B',0,'C',true);
				$pdf->Cell(30,8,'TYPE','B',0,'C',true);
				$pdf->Cell(30,8,'Amount','B'	,1,'C',true);
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);


				$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date`  BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
				$exe = mysqli_query($conn,$query1);
				while($row = mysqli_fetch_array($exe)){
					$pdf->Cell(25,8,date("d-m-Y", strtotime($row['date'])),0,0,'C');
					$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
					$pdf->Cell(35,8,$row['bill_number'],0,0,'C');
					$pdf->Cell(30,8,$row['credit/cash'],0,0,'C');
					$pdf->Cell(30,8,$row['amount'],0,1,'R');

				}

				$pdf->Cell(160,8,'Total Amount :','T',0,true);
				$pdf->Cell(30,8,$sales_credit_total,'T',1,'R');
				$pdf->Ln();

				$pdf->output();
	}


	if($selected_value == "sales and purchases"){
		// echo $selected_value;
		$query1 = "SELECT * FROM `hk_sales_account` WHERE `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
		$exe = mysqli_query($conn,$query1);
		while($row = mysqli_fetch_array($exe)){
			$sales_credit_total = $sales_credit_total+$row['amount'];
		}

				$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();
				// $pdf->SetFont('Arial', 'B', 20);
				// $pdf->SetTextColor(0,0,255);
				//
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);

				$pdf->Cell(130,5,'Day Book Report of : Sales',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(32,5,'Between Date : ',0,0,'L');
				$pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

				$pdf->Cell(190,5,'',0,1);
				$width_cell=array(20,60,20,60);
				$pdf->SetFillColor(255,255,255);

				$pdf->Cell(25,8,'Date','B',0,'C',true);
				$pdf->Cell(70,8,'Particulars','B',0,'C',true);
				$pdf->Cell(35,8,'Bill No.','B',0,'C',true);
				$pdf->Cell(30,8,'TYPE','B',0,'C',true);
				$pdf->Cell(30,8,'Amount','B',1,'C',true);
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);


				$query1 = "SELECT * FROM `hk_sales_account` WHERE `date`  BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
				$exe = mysqli_query($conn,$query1);
				while($row = mysqli_fetch_array($exe)){
					$pdf->Cell(25,8,date("d-m-Y", strtotime($row['date'])),0,0,'C');
					$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
					$pdf->Cell(35,8,$row['bill_number'],0,0,'C');
					$pdf->Cell(30,8,$row['credit/cash'],0,0,'C');
					$pdf->Cell(30,8,$row['amount'],0,1,'R');

				}

				$pdf->Cell(160,8,'Total Amount :','T',0,true);
				$pdf->Cell(30,8,$sales_credit_total,'T',1,'R');
				$pdf->Ln();


				$purchase_credit_total =0;
				$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
				$exe = mysqli_query($conn,$query1);
				while($row = mysqli_fetch_array($exe)){
					$purchase_credit_total = $purchase_credit_total+$row['amount'];
				}

						// $pdf= new FPDF('p','mm','A4');
						// $pdf->AddPage();
						// $pdf->SetFont('Arial', 'B', 20);
						// $pdf->SetTextColor(0,0,255);
						//
						// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
						// $pdf->Ln();
						$pdf->SetFont('Arial','B',12);
						$pdf->SetTextColor(0,0,0);

						$pdf->Cell(130,5,'Day Book Report of : Purchases',0,1,'L');
						$pdf->Ln();
						$pdf->Cell(32,5,'Between Date : ',0,0,'L');
						$pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

						$pdf->Cell(190,5,'',0,1);
						$width_cell=array(20,60,20,60);
						$pdf->SetFillColor(255,255,255);

						$pdf->Cell(25,8,'Date','B',0,'C',true);
						$pdf->Cell(70,8,'Particulars','B',0,'C',true);
						$pdf->Cell(35,8,'Bill No.','B',0,'C',true);
						$pdf->Cell(30,8,'TYPE','B',0,'C',true);
						$pdf->Cell(30,8,'Amount','B',1,'C',true);
						$pdf->SetFont('Arial','',10);
						$pdf->SetTextColor(0,0,0);


						$query1 = "SELECT * FROM `hk_purchase_account` WHERE `date`  BETWEEN '$actualFromdate' AND '$actualTodate' AND active = 1";
						$exe = mysqli_query($conn,$query1);
						while($row = mysqli_fetch_array($exe)){
							$pdf->Cell(25,8,date("d-m-Y", strtotime($row['date'])),0,0,'C');
							$pdf->Cell(70,8,strtoupper($row['particulars']),0,0,'L');
							$pdf->Cell(35,8,$row['bill_number'],0,0,'C');
							$pdf->Cell(30,8,$row['credit/cash'],0,0,'C');
							$pdf->Cell(30,8,$row['amount'],0,1,'R');

						}

						$pdf->Cell(160,8,'Total Amount :','T',0,true);
						$pdf->Cell(30,8,$purchase_credit_total,'T',1,'R');
						$pdf->Ln();

				$pdf->output();
	}

  if($selected_value == "payments"){
  	// echo $selected_value;

  $print_array = array();
  $total_amount = 0;
  	$query1 = "SELECT * FROM `hk_cash_book` WHERE `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND `cr` = '0' AND (`particulars` NOT LIKE '%CR%' AND `particulars` NOT LIKE '%Cash S To%' AND `particulars` NOT LIKE '%Cash Purchase Return%') AND active = 1";
  	$exe = mysqli_query($conn,$query1);
    $x = 0;
    if(mysqli_num_rows($exe) != 0){
      while($row = mysqli_fetch_array($exe)){
         $print_array[$x]["date"] = $row['date'];
         $print_array[$x]["particulars"] = $row['particulars'];
         $print_array[$x]["dr"] = $row['dr'];
         $total_amount = $total_amount+$row['dr'];
         $x++;
        }
    }



  	$pdf = new PDF();
  	$pdf->AliasNbPages();
  	$pdf->AddPage();
  	// $pdf->SetFont('Arial', 'B', 20);
  	// $pdf->SetTextColor(0,0,255);
  	//
  	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
  	// $pdf->Ln();
  	$pdf->SetFont('Arial','B',12);
  	$pdf->SetTextColor(0,0,0);

  	$pdf->Cell(130,5,'Day Book Report of : Payments',0,1,'L');
  	$pdf->Ln();
    $pdf->Cell(32,5,'Between Date : ',0,0,'L');
    $pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

  	$pdf->Cell(190,5,'',0,1);
  	$width_cell=array(20,60,20,60);
  	$pdf->SetFillColor(255,255,255);


  	$pdf->Cell(20,8,'Sl No.','B',0,'C',true);
  	$pdf->Cell(20,8,'Date','B',0,'C',true);
  	$pdf->Cell(110,8,'Particulars','B',0,'C',true);
  	$pdf->Cell(40,8,'Payments','B',1,'C',true);

  	$pdf->SetFont('Arial','',10);
  	$pdf->SetTextColor(0,0,0);

    $x = 1;
    for($y=0;$y<count($print_array);$y++){
  		$pdf->Cell(20,8,$x,0,0,'C');
  		$pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C');
  		$pdf->Cell(110,8,strtoupper($print_array[$y]["particulars"]),0,0,'L');
  		$pdf->Cell(40,8,$print_array[$y]["dr"],0,1,'R');
      $x++;
    }


  	$pdf->Cell(149,8,'Total :','T',0,true);
  	$pdf->Cell(41,8,$total_amount,'T',1,'R');
  	$pdf->Ln();

  	$pdf->output();

  }


  if($selected_value == "receipts"){
  	// echo $selected_value;

  $print_array = array();
  $total_amount = 0;
  	$query1 = "SELECT `dr`,`cr`,`particulars`,`date` FROM `hk_cash_book`
    WHERE (`particulars` NOT LIKE '%CR%' AND `particulars` NOT LIKE '%Paid Due%' AND `particulars` NOT LIKE '%Cash P%' AND `particulars` NOT LIKE '%Supplier Advance%') AND `date` BETWEEN '$actualFromdate' AND '$actualTodate' AND `active`='1'";


  	$exe = mysqli_query($conn,$query1);
    $x = 0;
    if(mysqli_num_rows($exe) != 0){
      while($row = mysqli_fetch_array($exe)){
         $print_array[$x]["date"] = $row['date'];
         $print_array[$x]["particulars"] = $row['particulars'];
         $print_array[$x]["cr"] = $row['cr']+$row['dr'];
         $total_amount = $total_amount+$row['cr']+$row['dr'];
         $x++;
        }
    }



  	$pdf = new PDF();
  	$pdf->AliasNbPages();
  	$pdf->AddPage();
  	// $pdf->SetFont('Arial', 'B', 20);
  	// $pdf->SetTextColor(0,0,255);
  	//
  	// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
  	// $pdf->Ln();
  	$pdf->SetFont('Arial','B',12);
  	$pdf->SetTextColor(0,0,0);

  	$pdf->Cell(130,5,'Day Book Report of : Receipts',0,1,'L');
  	$pdf->Ln();
    $pdf->Cell(32,5,'Between Date : ',0,0,'L');
    $pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

  	$pdf->Cell(190,5,'',0,1);
  	$width_cell=array(20,60,20,60);
  	$pdf->SetFillColor(255,255,255);


  	$pdf->Cell(20,8,'Sl No.','B',0,'C',true);
  	$pdf->Cell(20,8,'Date','B',0,'C',true);
  	$pdf->Cell(110,8,'Particulars','B',0,'C',true);
  	$pdf->Cell(40,8,'Receipts','B',1,'C',true);

  	$pdf->SetFont('Arial','',10);
  	$pdf->SetTextColor(0,0,0);

    $x = 1;
    for($y=0;$y<count($print_array);$y++){
  		$pdf->Cell(20,8,$x,0,0,'C');
  		$pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C');
  		$pdf->Cell(110,8,strtoupper($print_array[$y]["particulars"]),0,0,'L');
  		$pdf->Cell(40,8,$print_array[$y]["cr"],0,1,'R');
      $x++;
    }


  	$pdf->Cell(149,8,'Total :','T',0,true);
  	$pdf->Cell(41,8,$total_amount,'T',1,'R');
  	$pdf->Ln();

  	$pdf->output();

  }



}

}





?>
