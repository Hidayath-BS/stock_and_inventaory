<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');

$cash_amount = 0;
$ondate = $_POST["ondate"];
$fromdate = $_POST["fromdate"];
$todate = $_POST["todate"];

$dateType = $_POST["dateType"];


$actualOndate = $ondate;
$actualFromdate = $fromdate;
$actualTodate = $todate;

$ondate = date("d-m-Y", strtotime($ondate));
$fromdate = date("d-m-Y", strtotime($fromdate));
$todate = date("d-m-Y", strtotime($todate));

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




if($_POST["transaction_type"] == "cash"){
	if($dateType == "onDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }


				$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
        for($x = 0; $x<count($print_array); $x++ ){
          $pdf->Cell(20,9,$sl_no,0,0,'C');
          $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
          $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
          $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

          for($y = 0; $y<count($product_array[$x]); $y++){
            $pdf->Cell(20,9,'',0,0,'C');
            $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
          }
          $sl_no++;
        }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }



				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);


                $sl_no = 1;
                for($x = 0; $x<count($print_array); $x++ ){
                  $pdf->Cell(20,9,$sl_no,0,0,'C');
                  $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                  $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                  $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                  for($y = 0; $y<count($product_array[$x]); $y++){
                    $pdf->Cell(20,9,'',0,0,'C');
                    $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                  }
                  $sl_no++;
                }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }


        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
        for($x = 0; $x<count($print_array); $x++ ){
          $pdf->Cell(20,9,$sl_no,0,0,'C');
          $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
          $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
          $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

          for($y = 0; $y<count($product_array[$x]); $y++){
            $pdf->Cell(20,9,'',0,0,'C');
            $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
          }
          $sl_no++;
        }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }


				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
        for($x = 0; $x<count($print_array); $x++ ){
          $pdf->Cell(20,9,$sl_no,0,0,'C');
          $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
          $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
          $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

          for($y = 0; $y<count($product_array[$x]); $y++){
            $pdf->Cell(20,9,'',0,0,'C');
            $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
          }
          $sl_no++;
        }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
	}
	else if($dateType == "btDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

       $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }



        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];


        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
                 for($x = 0; $x<count($print_array); $x++ ){
                   $pdf->Cell(20,9,$sl_no,0,0,'C');
                   $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                   $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                   $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                   for($y = 0; $y<count($product_array[$x]); $y++){
                     $pdf->Cell(20,9,'',0,0,'C');
                     $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                   }
                   $sl_no++;
                 }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);


        $sl_no = 1;
        for($x = 0; $x<count($print_array); $x++ ){
          $pdf->Cell(20,9,$sl_no,0,0,'C');
          $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
          $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
          $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

          for($y = 0; $y<count($product_array[$x]); $y++){
            $pdf->Cell(20,9,'',0,0,'C');
            $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
          }
          $sl_no++;
        }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }


      	$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
	}
}




else if($_POST["transaction_type"] == "credit"){
	if($dateType == "onDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
      for($x = 0; $x<count($print_array); $x++ ){
        $pdf->Cell(20,9,$sl_no,0,0,'C');
        $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
        $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
        $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

        for($y = 0; $y<count($product_array[$x]); $y++){
          $pdf->Cell(20,9,'',0,0,'C');
          $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
        }
        $sl_no++;
      }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
              for($x = 0; $x<count($print_array); $x++ ){
                $pdf->Cell(20,9,$sl_no,0,0,'C');
                $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                for($y = 0; $y<count($product_array[$x]); $y++){
                  $pdf->Cell(20,9,'',0,0,'C');
                  $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                }
                $sl_no++;
              }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
         for($x = 0; $x<count($print_array); $x++ ){
           $pdf->Cell(20,9,$sl_no,0,0,'C');
           $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
           $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
           $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

           for($y = 0; $y<count($product_array[$x]); $y++){
             $pdf->Cell(20,9,'',0,0,'C');
             $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
           }
           $sl_no++;
         }


				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
         for($x = 0; $x<count($print_array); $x++ ){
           $pdf->Cell(20,9,$sl_no,0,0,'C');
           $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
           $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
           $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

           for($y = 0; $y<count($product_array[$x]); $y++){
             $pdf->Cell(20,9,'',0,0,'C');
             $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
           }
           $sl_no++;
         }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
	}
	else if($dateType == "btDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

        $print_array = array();
      $product_array = array();
      $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1'";
                $exe = mysqli_query($conn,$query);
                $x = 0;
                while($row = mysqli_fetch_array($exe)){
                  $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                  $print_array[$x]["bill_number"] = $row['bill_number'];
                  $print_array[$x]["amount_paid"] = $row['amount_payable'];
                  $cash_amount = $cash_amount+$row['amount_payable'];
                  $purchase_id = $row['id'];

                  $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                            FROM `hk_purchased_products` AS HKPP
                            LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                            WHERE HKPP.purchase_id = '$purchase_id'";
                            $exe1 = mysqli_query($conn,$query1);
                            $y = 0;
                            while($row1 = mysqli_fetch_array($exe1)){
                              $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                              $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                              $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                              $product_array[$x][$y]["rate"] = $row1['rate'];
                              $product_array[$x][$y]["amount"] = $row1['amount'];
                              $y++;
                            }
                  $x++;
                }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
            for($x = 0; $x<count($print_array); $x++ ){
              $pdf->Cell(20,9,$sl_no,0,0,'C');
              $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
              $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
              $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

              for($y = 0; $y<count($product_array[$x]); $y++){
                $pdf->Cell(20,9,'',0,0,'C');
                $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
              }
              $sl_no++;
            }


				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
				$pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
                for($x = 0; $x<count($print_array); $x++ ){
                  $pdf->Cell(20,9,$sl_no,0,0,'C');
                  $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                  $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                  $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                  for($y = 0; $y<count($product_array[$x]); $y++){
                    $pdf->Cell(20,9,'',0,0,'C');
                    $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                  }
                  $sl_no++;
                }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
      $product_array = array();
      $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                $exe = mysqli_query($conn,$query);
                $x = 0;
                while($row = mysqli_fetch_array($exe)){
                  $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                  $print_array[$x]["bill_number"] = $row['bill_number'];
                  $print_array[$x]["amount_paid"] = $row['amount_payable'];
                  $cash_amount = $cash_amount+$row['amount_payable'];
                  $purchase_id = $row['id'];

                  $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                            FROM `hk_purchased_products` AS HKPP
                            LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                            WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                            $exe1 = mysqli_query($conn,$query1);
                            $y = 0;
                            while($row1 = mysqli_fetch_array($exe1)){
                              $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                              $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                              $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                              $product_array[$x][$y]["rate"] = $row1['rate'];
                              $product_array[$x][$y]["amount"] = $row1['amount'];
                              $y++;
                            }
                  $x++;
                }


        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
       for($x = 0; $x<count($print_array); $x++ ){
         $pdf->Cell(20,9,$sl_no,0,0,'C');
         $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
         $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
         $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

         for($y = 0; $y<count($product_array[$x]); $y++){
           $pdf->Cell(20,9,'',0,0,'C');
           $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
         }
         $sl_no++;
       }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
	}
}



else{
	if($dateType == "onDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }


				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();
				//
				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
        for($x = 0; $x<count($print_array); $x++ ){
          $pdf->Cell(20,9,$sl_no,0,0,'C');
          $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
          $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
          $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

          for($y = 0; $y<count($product_array[$x]); $y++){
            $pdf->Cell(20,9,'',0,0,'C');
            $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
          }
          $sl_no++;
        }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
      for($x = 0; $x<count($print_array); $x++ ){
        $pdf->Cell(20,9,$sl_no,0,0,'C');
        $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
        $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
        $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

        for($y = 0; $y<count($product_array[$x]); $y++){
          $pdf->Cell(20,9,'',0,0,'C');
          $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
        }
        $sl_no++;
      }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  			$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
                 for($x = 0; $x<count($print_array); $x++ ){
                   $pdf->Cell(20,9,$sl_no,0,0,'C');
                   $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                   $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                   $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                   for($y = 0; $y<count($product_array[$x]); $y++){
                     $pdf->Cell(20,9,'',0,0,'C');
                     $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                   }
                   $sl_no++;
                 }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
      $product_array = array();
      $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                $exe = mysqli_query($conn,$query);
                $x = 0;
                while($row = mysqli_fetch_array($exe)){
                  $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                  $print_array[$x]["bill_number"] = $row['bill_number'];
                  $print_array[$x]["amount_paid"] = $row['amount_payable'];
                  $cash_amount = $cash_amount+$row['amount_payable'];
                  $purchase_id = $row['id'];

                  $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                            FROM `hk_purchased_products` AS HKPP
                            LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                            WHERE HKPP.purchase_id = '$purchase_id'";
                            $exe1 = mysqli_query($conn,$query1);
                            $y = 0;
                            while($row1 = mysqli_fetch_array($exe1)){
                              $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                              $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                              $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                              $product_array[$x][$y]["rate"] = $row1['rate'];
                              $product_array[$x][$y]["amount"] = $row1['amount'];
                              $y++;
                            }
                  $x++;
                }


				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
             for($x = 0; $x<count($print_array); $x++ ){
               $pdf->Cell(20,9,$sl_no,0,0,'C');
               $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
               $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
               $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

               for($y = 0; $y<count($product_array[$x]); $y++){
                 $pdf->Cell(20,9,'',0,0,'C');
                 $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
               }
               $sl_no++;
             }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');
				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

				$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
       for($x = 0; $x<count($print_array); $x++ ){
         $pdf->Cell(20,9,$sl_no,0,0,'C');
         $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
         $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
         $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

         for($y = 0; $y<count($product_array[$x]); $y++){
           $pdf->Cell(20,9,'',0,0,'C');
           $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
         }
         $sl_no++;
       }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date = '$actualOndate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
         for($x = 0; $x<count($print_array); $x++ ){
           $pdf->Cell(20,9,$sl_no,0,0,'C');
           $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
           $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
           $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

           for($y = 0; $y<count($product_array[$x]); $y++){
             $pdf->Cell(20,9,'',0,0,'C');
             $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
           }
           $sl_no++;
         }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');
				$pdf->output();
			}

		}
	}else if($dateType == "btDate"){
		if($_POST["product"] == "allproducts"){
			if($_POST["supplier"] == "allsuppliers"){

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }


      	$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
           for($x = 0; $x<count($print_array); $x++ ){
             $pdf->Cell(20,9,$sl_no,0,0,'C');
             $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
             $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
             $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

             for($y = 0; $y<count($product_array[$x]); $y++){
               $pdf->Cell(20,9,'',0,0,'C');
               $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
             }
             $sl_no++;
           }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
     $product_array = array();
     $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
               LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
               WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1'";
               $exe = mysqli_query($conn,$query);
               $x = 0;
               while($row = mysqli_fetch_array($exe)){
                 $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                 $print_array[$x]["bill_number"] = $row['bill_number'];
                 $print_array[$x]["amount_paid"] = $row['amount_payable'];
                 $cash_amount = $cash_amount+$row['amount_payable'];
                 $purchase_id = $row['id'];

                 $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                           FROM `hk_purchased_products` AS HKPP
                           LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                           WHERE HKPP.purchase_id = '$purchase_id'";
                           $exe1 = mysqli_query($conn,$query1);
                           $y = 0;
                           while($row1 = mysqli_fetch_array($exe1)){
                             $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                             $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                             $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                             $product_array[$x][$y]["rate"] = $row1['rate'];
                             $product_array[$x][$y]["amount"] = $row1['amount'];
                             $y++;
                           }
                 $x++;
               }

				$pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
           for($x = 0; $x<count($print_array); $x++ ){
             $pdf->Cell(20,9,$sl_no,0,0,'C');
             $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
             $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
             $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

             for($y = 0; $y<count($product_array[$x]); $y++){
               $pdf->Cell(20,9,'',0,0,'C');
               $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
             }
             $sl_no++;
           }
				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];

        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  			$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
                 for($x = 0; $x<count($print_array); $x++ ){
                   $pdf->Cell(20,9,$sl_no,0,0,'C');
                   $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                   $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                   $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                   for($y = 0; $y<count($product_array[$x]); $y++){
                     $pdf->Cell(20,9,'',0,0,'C');
                     $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                   }
                   $sl_no++;
                 }


				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
      $product_array = array();
      $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPR.person_id = '$supplier_id'";
                $exe = mysqli_query($conn,$query);
                $x = 0;
                while($row = mysqli_fetch_array($exe)){
                  $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                  $print_array[$x]["bill_number"] = $row['bill_number'];
                  $print_array[$x]["amount_paid"] = $row['amount_payable'];
                  $cash_amount = $cash_amount+$row['amount_payable'];
                  $purchase_id = $row['id'];

                  $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                            FROM `hk_purchased_products` AS HKPP
                            LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                            WHERE HKPP.purchase_id = '$purchase_id'";
                            $exe1 = mysqli_query($conn,$query1);
                            $y = 0;
                            while($row1 = mysqli_fetch_array($exe1)){
                              $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                              $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                              $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                              $product_array[$x][$y]["rate"] = $row1['rate'];
                              $product_array[$x][$y]["amount"] = $row1['amount'];
                              $y++;
                            }
                  $x++;
                }

				$pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(130,5,'Credit Purchase Of All Products',0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
               for($x = 0; $x<count($print_array); $x++ ){
                 $pdf->Cell(20,9,$sl_no,0,0,'C');
                 $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
                 $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
                 $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

                 for($y = 0; $y<count($product_array[$x]); $y++){
                   $pdf->Cell(20,9,'',0,0,'C');
                   $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
                 }
                 $sl_no++;
               }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
		else{
			if($_POST["supplier"] == "allsuppliers"){
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}


        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

        $pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  			$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
         for($x = 0; $x<count($print_array); $x++ ){
           $pdf->Cell(20,9,$sl_no,0,0,'C');
           $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
           $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
           $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

           for($y = 0; $y<count($product_array[$x]); $y++){
             $pdf->Cell(20,9,'',0,0,'C');
             $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
           }
           $sl_no++;
         }


				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');


        $cash_amount = 0;
        $print_array = array();
      $product_array = array();
      $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id'";
                $exe = mysqli_query($conn,$query);
                $x = 0;
                while($row = mysqli_fetch_array($exe)){
                  $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                  $print_array[$x]["bill_number"] = $row['bill_number'];
                  $print_array[$x]["amount_paid"] = $row['amount_payable'];
                  $cash_amount = $cash_amount+$row['amount_payable'];
                  $purchase_id = $row['id'];

                  $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                            FROM `hk_purchased_products` AS HKPP
                            LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                            WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                            $exe1 = mysqli_query($conn,$query1);
                            $y = 0;
                            while($row1 = mysqli_fetch_array($exe1)){
                              $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                              $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                              $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                              $product_array[$x][$y]["rate"] = $row1['rate'];
                              $product_array[$x][$y]["amount"] = $row1['amount'];
                              $y++;
                            }
                  $x++;
                }

				$pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
       for($x = 0; $x<count($print_array); $x++ ){
         $pdf->Cell(20,9,$sl_no,0,0,'C');
         $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
         $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
         $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

         for($y = 0; $y<count($product_array[$x]); $y++){
           $pdf->Cell(20,9,'',0,0,'C');
           $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
         }
         $sl_no++;
       }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}else{
				$supplier_id = $_POST["supplier_id"];
				$product_id = $_POST["product_id"];
				$product_name;
				$productQuery = "SELECT * from `hk_products` WHERE id = '$product_id'";
				$exe = mysqli_query($conn,$productQuery);
				while($row = mysqli_fetch_array($exe)){
					$product_name = $row['name']." ".$row['type'];
				}

        $print_array = array();
        $product_array = array();
        $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                  LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                  LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                  WHERE HKPR.purchase_transaction_type_id = '1' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                  $exe = mysqli_query($conn,$query);
                  $x = 0;
                  while($row = mysqli_fetch_array($exe)){
                    $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                    $print_array[$x]["bill_number"] = $row['bill_number'];
                    $print_array[$x]["amount_paid"] = $row['amount_payable'];
                    $cash_amount = $cash_amount+$row['amount_payable'];
                    $purchase_id = $row['id'];

                    $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                              FROM `hk_purchased_products` AS HKPP
                              LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                              WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                              $exe1 = mysqli_query($conn,$query1);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
                    $x++;
                  }

      	$pdf = new PDF();
        $pdf->AliasNbPages();
				$pdf->AddPage();

				// $pdf->SetFont('Arial', 'B', 20);
				//
				// $pdf->SetTextColor(0,0,255);
				// $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
				// $pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(130,5,'Purchase Register Report :',0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
				$pdf->Ln();
				$pdf->Cell(130,5,'Cash Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
            for($x = 0; $x<count($print_array); $x++ ){
              $pdf->Cell(20,9,$sl_no,0,0,'C');
              $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
              $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
              $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

              for($y = 0; $y<count($product_array[$x]); $y++){
                $pdf->Cell(20,9,'',0,0,'C');
                $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
              }
              $sl_no++;
            }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

        $cash_amount = 0;
        $print_array = array();
       $product_array = array();
       $query = "SELECT HKPR.*,HKP.first_name,HKP.last_name FROM `hk_purchases` AS HKPR
                 LEFT JOIN `hk_persons` AS HKP ON HKPR.person_id = HKP.id
                 LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKPR.id
                 WHERE HKPR.purchase_transaction_type_id = '2' AND HKPR.bill_date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKPR.purchases_active = '1' AND HKPP.product_id = '$product_id' AND HKPR.person_id = '$supplier_id'";
                 $exe = mysqli_query($conn,$query);
                 $x = 0;
                 while($row = mysqli_fetch_array($exe)){
                   $print_array[$x]["customer_name"] = $row['first_name']." ".$row['last_name'];
                   $print_array[$x]["bill_number"] = $row['bill_number'];
                   $print_array[$x]["amount_paid"] = $row['amount_payable'];
                   $cash_amount = $cash_amount+$row['amount_payable'];
                   $purchase_id = $row['id'];

                   $query1 = "SELECT HKPP.quantity,HKPP.rate,HKPP.amount,HKP.name,HKP.type,HKP.quantity_type
                             FROM `hk_purchased_products` AS HKPP
                             LEFT JOIN `hk_products` AS HKP ON HKPP.product_id = HKP.id
                             WHERE HKPP.purchase_id = '$purchase_id' AND HKPP.product_id = '$product_id'";
                             $exe1 = mysqli_query($conn,$query1);
                             $y = 0;
                             while($row1 = mysqli_fetch_array($exe1)){
                               $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                               $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                               $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                               $product_array[$x][$y]["rate"] = $row1['rate'];
                               $product_array[$x][$y]["amount"] = $row1['amount'];
                               $y++;
                             }
                   $x++;
                 }

				$pdf->Ln();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(130,5,'Credit Purchase Of '.$product_name,0,1,'L');

				$pdf->SetFont('Arial','B',12);

				$pdf->Ln(5);
				$width_cell=array(60,50,40);
				$pdf->SetFillColor(255,255,255);
        $pdf->Cell(20,9,'Sl No.','B',0,'C');
  				$pdf->Cell(60,9,'Particulars/Name','B',0,'C');
				$pdf->Cell(42,9,'Receipt No','B',0,'C');
				$pdf->Cell(68,9,'Amount','B',1,'C');

				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);

        $sl_no = 1;
          for($x = 0; $x<count($print_array); $x++ ){
            $pdf->Cell(20,9,$sl_no,0,0,'C');
            $pdf->Cell(60,9,strtoupper($print_array[$x]["customer_name"]),0,0,'L');
            $pdf->Cell(42,9,$print_array[$x]["bill_number"],0,0,'C');
            $pdf->Cell(68,9,$print_array[$x]["amount_paid"],0,1,'R');

            for($y = 0; $y<count($product_array[$x]); $y++){
              $pdf->Cell(20,9,'',0,0,'C');
              $pdf->Cell(60,9,strtoupper($product_array[$x][$y]["product_name"]." ".$product_array[$x][$y]["product_quantity"]." ".$product_array[$x][$y]["quantity_type"]." ".$product_array[$x][$y]["amount"]),0,1,'L');
            }
            $sl_no++;
          }

				$pdf->Cell(122,9,'Total : ','T',0,'R');
				$pdf->Cell(68,9,$cash_amount,'T',1,'R');

				$pdf->output();
			}

		}
	}
}


?>
