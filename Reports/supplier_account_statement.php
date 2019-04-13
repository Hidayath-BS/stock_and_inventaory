<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
$ondate = $_POST["ondate"];
$fromdate = $_POST["fromdate"];
$todate = $_POST["todate"];
$dateType = $_POST["dateType"];
$customer_id = $_POST["id"];

$actualOndate = $ondate;
$actualFromdate = $fromdate;
$actualTodate = $todate;
$ondate = date("d-m-Y", strtotime($ondate));
$fromdate = date("d-m-Y", strtotime($fromdate));
$todate = date("d-m-Y", strtotime($todate));
$print_array = array();
$print_array_length = 0;
$product_array = array();

$opening_balance = 0;
$cr_total = 0;
$dr_total = 0;
$previous_credit_total = 0;
$previous_debit_total = 0;

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


if(isset($_POST["details"])){

  if($dateType=="onDate"){

  $supplierQuery = "SELECT first_name,last_name FROM `hk_persons` WHERE id = '$customer_id'";
   $exe = mysqli_query($conn,$supplierQuery);
   while($row = mysqli_fetch_array($exe)){
    $supplier_name =  $row['first_name']." ".$row['last_name'];
   }


   $opening_balance_query = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$customer_id."` WHERE date < '$actualOndate' AND active =1";
   $exe = mysqli_query($conn,$opening_balance_query);
   if(mysqli_num_rows($exe)==0){
     $previous_debit_total = 0;
     $previous_credit_total = 0;
   }
   else{
     while($row = mysqli_fetch_array($exe)){
       $previous_credit_total = $row["credit"];
       $previous_debit_total = $row["debit"];
       }
   }

   $opening_balance = $previous_debit_total - $previous_credit_total;


    $query  = "SELECT * FROM `hk_account_".$customer_id."` WHERE date = '$actualOndate' AND active =1";
               $exe = mysqli_query($conn,$query);
               $print_array_length = mysqli_num_rows($exe);
                 $x = 0;
               while($row = mysqli_fetch_array($exe)){
                 $print_array[$x]["date"] = $row['date'];
                 $print_array[$x]["particulars"] = $row['particulars'];
                 $print_array[$x]["cr"] = $row['cr'];
                 $print_array[$x]["dr"] = $row['dr'];
                 if($x == 0){
                   $print_array[$x]["balance"] = ($opening_balance+$row['dr'])-$row['cr'];
                 }else{
                   $print_array[$x]["balance"] = ($print_array[$x-1]["balance"]+$row['dr'])-$row['cr'];
                 }
                 $cr_total = $cr_total+$row['cr'];
                 $dr_total = $dr_total+$row['dr'];
                 $x++;
               }

               for($x = 0; $x < count($print_array); $x++){
                 if(strpos($print_array[$x]["particulars"],"CR P on Bill No: ") !== false){
                   $purchase_bill_no = substr($print_array[$x]["particulars"], 17);
                   $purchase_bill_no = trim($purchase_bill_no);
                   $query = "SELECT HKPR.name,HKPR.type,HKPR.quantity_type,HKPP.final_quantity,HKPP.rate,HKPP.amount
                              FROM `hk_purchases` AS HKP
                              LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKP.id
                              LEFT JOIN `hk_products` AS HKPR ON HKPP.product_id = HKPR.id
                              WHERE HKP.bill_number = '$purchase_bill_no'";
                              $exe1 = mysqli_query($conn,$query);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['final_quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }

                 }
                 else if(strpos($print_array[$x]["particulars"],"Credit Purchase Return on Bill No: ") !== false){
                   $purchase_return_bill_no = substr($print_array[$x]["particulars"], 35);
                   $purchase_return_bill_no = trim($purchase_return_bill_no);
                   $query = "SELECT HKP.name,HKP.type,HKP.quantity_type,HKPRP.quantity,HKPRP.rate,HKPRP.amount
                              FROM `hk_purchases_return` AS HKPR
                              LEFT JOIN `hk_purchase_return_products` AS HKPRP ON HKPRP.purchase_return_id = HKPR.id
                              LEFT JOIN `hk_products` AS HKP ON HKPRP.product_id = HKP.id
                              WHERE HKPR.purchase_return_bill_number = '$purchase_return_bill_no'";
                              $exe1 = mysqli_query($conn,$query);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
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

               $pdf->Cell(130,5,''.$supplier_name.' (Supplier) Balance Sheet',0,1,'L');
               $pdf->Ln();
               $pdf->Cell(20,5,'On Date : ',0,0,'L');
               $pdf->Cell(30,5,$ondate,0,1,'L');

               $pdf->Cell(190,5,'',0,1);
               $width_cell=array(20,60,20,60);
               $pdf->SetFillColor(255,255,255);

               $pdf->SetFont('Arial','B',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'Sl No. ','B',0,'C',true);
               $pdf->Cell(20,8,'Date','B',0,'C',true);
               $pdf->Cell(83,8,'Particulars','B',0,'C',true);
               $pdf->Cell(25,8,'Cr','B',0,'C',true);
               $pdf->Cell(25,8,'Dr','B',0,'C',true);
               $pdf->Cell(25,8,'Balance','B',1,'C',true);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'',0,0,'C',true);
               $pdf->Cell(20,8,'',0,0,'C',true);
               $pdf->Cell(83,8,'Opening Balance',0,0,'L',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);


                 $pdf->Cell(25,8,$opening_balance,0,1,'R',true);


               $x = 1;
               for($y=0;$y<count($print_array);$y++){
                  $pdf->Cell(12,8,$x,0,0,'C',true);
                  $pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C',true);
                  $pdf->Cell(83,8,strtoupper($print_array[$y]["particulars"]),0,0,'L',true);
                  $pdf->Cell(25,8,$print_array[$y]["cr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["dr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["balance"],0,1,'R',true);

                  if(!empty($product_array[$y])){
                    for($k = 0;$k<count($product_array[$y]);$k++){
                      $pdf->Cell(32,9,'',0,0,'C');
                      $pdf->Cell(83,9,strtoupper($product_array[$y][$k]["product_name"]." ".$product_array[$y][$k]["product_quantity"]." ".$product_array[$y][$k]["quantity_type"]." ".$product_array[$y][$k]["amount"]),0,1,'L');
                    }
                  }


                  $x++;
                }

                // $pdf->Cell(165,8,'Balance :','T',0,true);
                if(count($print_array) == 0){
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',1,'C',true);
                }else{
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,$cr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$dr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$print_array[count($print_array)-1]["balance"],'T',1,'R');
                }

                $pdf->Ln();
                $pdf->Cell(190,8,'Note : If balance is in Positive(+ve) Amount will be Due else It is balance',0,0,true);
                $pdf->Ln();
                $pdf->output();

  }else if($dateType=="btDate"){

    $supplierQuery = "SELECT first_name,last_name FROM `hk_persons` WHERE id = '$customer_id'";
     $exe = mysqli_query($conn,$supplierQuery);
     while($row = mysqli_fetch_array($exe)){
      $supplier_name =  $row['first_name']." ".$row['last_name'];
     }


     $opening_balance_query = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$customer_id."` WHERE date < '$actualFromdate' AND active =1";
     $exe = mysqli_query($conn,$opening_balance_query);
     if(mysqli_num_rows($exe)==0){
       $previous_debit_total = 0;
       $previous_credit_total = 0;
     }
     else{
       while($row = mysqli_fetch_array($exe)){
         $previous_credit_total = $row["credit"];
         $previous_debit_total = $row["debit"];
         }
     }

     $opening_balance = $previous_debit_total - $previous_credit_total;



    $query  = "SELECT * FROM `hk_account_".$customer_id."` WHERE date BETWEEN '$actualFromdate' AND '$actualTodate' AND active =1 ORDER BY date";
               $exe = mysqli_query($conn,$query);
               $print_array_length = mysqli_num_rows($exe);
                 $x = 0;
               while($row = mysqli_fetch_array($exe)){
                 $print_array[$x]["date"] = $row['date'];
                 $print_array[$x]["particulars"] = $row['particulars'];
                 $print_array[$x]["cr"] = $row['cr'];
                 $print_array[$x]["dr"] = $row['dr'];
                 if($x == 0){
                   $print_array[$x]["balance"] = ($opening_balance+$row['dr'])-$row['cr'];
                 }else{
                   $print_array[$x]["balance"] = ($print_array[$x-1]["balance"]+$row['dr'])-$row['cr'];
                 }
                 $cr_total = $cr_total+$row['cr'];
                 $dr_total = $dr_total+$row['dr'];
                 $x++;
               }


               for($x = 0; $x < count($print_array); $x++){
                 if(strpos($print_array[$x]["particulars"],"CR P on Bill No: ") !== false){
                   $purchase_bill_no = substr($print_array[$x]["particulars"], 17);
                   $purchase_bill_no = trim($purchase_bill_no);
                   $query = "SELECT HKPR.name,HKPR.type,HKPR.quantity_type,HKPP.final_quantity,HKPP.rate,HKPP.amount
                              FROM `hk_purchases` AS HKP
                              LEFT JOIN `hk_purchased_products` AS HKPP ON HKPP.purchase_id = HKP.id
                              LEFT JOIN `hk_products` AS HKPR ON HKPP.product_id = HKPR.id
                              WHERE HKP.bill_number = '$purchase_bill_no'";
                              $exe1 = mysqli_query($conn,$query);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['final_quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }

                 }
                 else if(strpos($print_array[$x]["particulars"],"Credit Purchase Return on Bill No: ") !== false){
                   $purchase_return_bill_no = substr($print_array[$x]["particulars"], 35);
                   $purchase_return_bill_no = trim($purchase_return_bill_no);
                   $query = "SELECT HKP.name,HKP.type,HKP.quantity_type,HKPRP.quantity,HKPRP.rate,HKPRP.amount
                              FROM `hk_purchases_return` AS HKPR
                              LEFT JOIN `hk_purchase_return_products` AS HKPRP ON HKPRP.purchase_return_id = HKPR.id
                              LEFT JOIN `hk_products` AS HKP ON HKPRP.product_id = HKP.id
                              WHERE HKPR.purchase_return_bill_number = '$purchase_return_bill_no'";
                              $exe1 = mysqli_query($conn,$query);
                              $y = 0;
                              while($row1 = mysqli_fetch_array($exe1)){
                                $product_array[$x][$y]["product_name"] = $row1['name']." ".$row1['type'];
                                $product_array[$x][$y]["quantity_type"] = $row1['quantity_type'];
                                $product_array[$x][$y]["product_quantity"] = $row1['quantity'];
                                $product_array[$x][$y]["rate"] = $row1['rate'];
                                $product_array[$x][$y]["amount"] = $row1['amount'];
                                $y++;
                              }
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

               $pdf->Cell(130,5,''.$supplier_name.' (Supplier) Balance Sheet',0,1,'L');
               $pdf->Ln();
               $pdf->Cell(32,5,'Between Date : ',0,0,'L');
               $pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

               $pdf->Cell(190,5,'',0,1);
               $width_cell=array(20,60,20,60);
               $pdf->SetFillColor(255,255,255);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'Sl No. ','B',0,'C',true);
               $pdf->Cell(20,8,'Date','B',0,'C',true);
               $pdf->Cell(83,8,'Particulars','B',0,'C',true);
               $pdf->Cell(25,8,'Cr','B',0,'C',true);
               $pdf->Cell(25,8,'Dr','B',0,'C',true);
               $pdf->Cell(25,8,'Balance','B',1,'C',true);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'',0,0,'C',true);
               $pdf->Cell(20,8,'',0,0,'C',true);
               $pdf->Cell(83,8,'Opening Balance',0,0,'L',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);


                 $pdf->Cell(25,8,$opening_balance,0,1,'R',true);


                $x = 1;
               for($y=0;$y<count($print_array);$y++){
                  $pdf->Cell(12,8,$x,0,0,'C',true);
                  $pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C',true);
                  $pdf->Cell(83,8,strtoupper($print_array[$y]["particulars"]),0,0,'L',true);
                  $pdf->Cell(25,8,$print_array[$y]["cr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["dr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["balance"],0,1,'R',true);

                  if(!empty($product_array[$y])){
                    for($k = 0;$k<count($product_array[$y]);$k++){
                      $pdf->Cell(32,9,'',0,0,'C');
                      $pdf->Cell(83,9,strtoupper($product_array[$y][$k]["product_name"]." ".$product_array[$y][$k]["product_quantity"]." ".$product_array[$y][$k]["quantity_type"]." ".$product_array[$y][$k]["amount"]),0,1,'L');
                    }
                  }


                  $x++;
                }

                if(count($print_array) == 0){
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',1,'C',true);
                }else{
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,$cr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$dr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$print_array[count($print_array)-1]["balance"],'T',1,'R');
                }

                $pdf->Ln();

                $pdf->Cell(190,8,'Note : If balance is in Positive(+ve) Amount will be Due else It is balance',0,1,true);
                $pdf->Ln();

                $pdf->output();


  }

}
else{
  if($dateType=="onDate"){

  $supplierQuery = "SELECT first_name,last_name FROM `hk_persons` WHERE id = '$customer_id'";
   $exe = mysqli_query($conn,$supplierQuery);
   while($row = mysqli_fetch_array($exe)){
    $supplier_name =  $row['first_name']." ".$row['last_name'];
   }


   $opening_balance_query = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$customer_id."` WHERE date < '$actualOndate' AND active =1";
   $exe = mysqli_query($conn,$opening_balance_query);
   if(mysqli_num_rows($exe)==0){
     $previous_debit_total = 0;
     $previous_credit_total = 0;
   }
   else{
     while($row = mysqli_fetch_array($exe)){
       $previous_credit_total = $row["credit"];
       $previous_debit_total = $row["debit"];
       }
   }

   $opening_balance = $previous_debit_total - $previous_credit_total;


    $query  = "SELECT * FROM `hk_account_".$customer_id."` WHERE date = '$actualOndate' AND active =1";
               $exe = mysqli_query($conn,$query);
               $print_array_length = mysqli_num_rows($exe);
                 $x = 0;
               while($row = mysqli_fetch_array($exe)){
                 $print_array[$x]["date"] = $row['date'];
                 $print_array[$x]["particulars"] = $row['particulars'];
                 $print_array[$x]["cr"] = $row['cr'];
                 $print_array[$x]["dr"] = $row['dr'];
                 if($x == 0){
                   $print_array[$x]["balance"] = ($opening_balance+$row['dr'])-$row['cr'];
                 }else{
                   $print_array[$x]["balance"] = ($print_array[$x-1]["balance"]+$row['dr'])-$row['cr'];
                 }
                 $cr_total = $cr_total+$row['cr'];
                 $dr_total = $dr_total+$row['dr'];
                 $x++;
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

               $pdf->Cell(130,5,''.$supplier_name.' (Supplier) Balance Sheet',0,1,'L');
               $pdf->Ln();
               $pdf->Cell(20,5,'On Date : ',0,0,'L');
               $pdf->Cell(30,5,$ondate,0,1,'L');

               $pdf->Cell(190,5,'',0,1);
               $width_cell=array(20,60,20,60);
               $pdf->SetFillColor(255,255,255);

               $pdf->SetFont('Arial','B',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'Sl No. ','B',0,'C',true);
               $pdf->Cell(20,8,'Date','B',0,'C',true);
               $pdf->Cell(83,8,'Particulars','B',0,'C',true);
               $pdf->Cell(25,8,'Cr','B',0,'C',true);
               $pdf->Cell(25,8,'Dr','B',0,'C',true);
               $pdf->Cell(25,8,'Balance','B',1,'C',true);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'',0,0,'C',true);
               $pdf->Cell(20,8,'',0,0,'C',true);
               $pdf->Cell(83,8,'Opening Balance',0,0,'L',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);


                 $pdf->Cell(25,8,$opening_balance,0,1,'R',true);


               $x = 1;
               for($y=0;$y<count($print_array);$y++){
                  $pdf->Cell(12,8,$x,0,0,'C',true);
                  $pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C',true);
                  $pdf->Cell(83,8,strtoupper($print_array[$y]["particulars"]),0,0,'L',true);
                  $pdf->Cell(25,8,$print_array[$y]["cr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["dr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["balance"],0,1,'R',true);
                  $x++;
                }

                // $pdf->Cell(165,8,'Balance :','T',0,true);
                if(count($print_array) == 0){
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',1,'R',true);
                }else{
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,$cr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$dr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$print_array[count($print_array)-1]["balance"],'T',1,'R');
                }

                $pdf->Ln();
                $pdf->Cell(190,8,'Note : If balance is in Positive(+ve) Amount will be Due else It is balance',0,0,true);
                  $pdf->Ln();
                $pdf->output();

  }else if($dateType=="btDate"){

    $supplierQuery = "SELECT first_name,last_name FROM `hk_persons` WHERE id = '$customer_id'";
     $exe = mysqli_query($conn,$supplierQuery);
     while($row = mysqli_fetch_array($exe)){
      $supplier_name =  $row['first_name']." ".$row['last_name'];
     }


     $opening_balance_query = "SELECT SUM(cr) as credit,SUM(dr) as debit from `hk_account_".$customer_id."` WHERE date < '$actualFromdate' AND active =1";
     $exe = mysqli_query($conn,$opening_balance_query);
     if(mysqli_num_rows($exe)==0){
       $previous_debit_total = 0;
       $previous_credit_total = 0;
     }
     else{
       while($row = mysqli_fetch_array($exe)){
         $previous_credit_total = $row["credit"];
         $previous_debit_total = $row["debit"];
         }
     }

     $opening_balance = $previous_debit_total - $previous_credit_total;



    $query  = "SELECT * FROM `hk_account_".$customer_id."` WHERE date BETWEEN '$actualFromdate' AND '$actualTodate' AND active =1 ORDER BY date";
               $exe = mysqli_query($conn,$query);
               $print_array_length = mysqli_num_rows($exe);
                 $x = 0;
               while($row = mysqli_fetch_array($exe)){
                 $print_array[$x]["date"] = $row['date'];
                 $print_array[$x]["particulars"] = $row['particulars'];
                 $print_array[$x]["cr"] = $row['cr'];
                 $print_array[$x]["dr"] = $row['dr'];
                 if($x == 0){
                   $print_array[$x]["balance"] = ($opening_balance+$row['dr'])-$row['cr'];
                 }else{
                   $print_array[$x]["balance"] = ($print_array[$x-1]["balance"]+$row['dr'])-$row['cr'];
                 }
                 $cr_total = $cr_total+$row['cr'];
                 $dr_total = $dr_total+$row['dr'];
                 $x++;
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

               $pdf->Cell(130,5,''.$supplier_name.' (Supplier) Balance Sheet',0,1,'L');
               $pdf->Ln();
               $pdf->Cell(32,5,'Between Date : ',0,0,'L');
               $pdf->Cell(30,5,"from ".$fromdate." to ".$todate,0,1,'L');

               $pdf->Cell(190,5,'',0,1);
               $width_cell=array(20,60,20,60);
               $pdf->SetFillColor(255,255,255);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'Sl No. ','B',0,'C',true);
               $pdf->Cell(20,8,'Date','B',0,'C',true);
               $pdf->Cell(83,8,'Particulars','B',0,'C',true);
               $pdf->Cell(25,8,'Cr','B',0,'C',true);
               $pdf->Cell(25,8,'Dr','B',0,'C',true);
               $pdf->Cell(25,8,'Balance','B',1,'C',true);

               $pdf->SetFont('Arial','',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(12,8,'',0,0,'C',true);
               $pdf->Cell(20,8,'',0,0,'C',true);
               $pdf->Cell(83,8,'Opening Balance',0,0,'L',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);
               $pdf->Cell(25,8,'-',0,0,'C',true);


                 $pdf->Cell(25,8,$opening_balance,0,1,'R',true);


                $x = 1;
               for($y=0;$y<count($print_array);$y++){
                  $pdf->Cell(12,8,$x,0,0,'C',true);
                  $pdf->Cell(20,8,date("d-m-Y", strtotime($print_array[$y]["date"])),0,0,'C',true);
                  $pdf->Cell(83,8,strtoupper($print_array[$y]["particulars"]),0,0,'L',true);
                  $pdf->Cell(25,8,$print_array[$y]["cr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["dr"],0,0,'C',true);
                  $pdf->Cell(25,8,$print_array[$y]["balance"],0,1,'R',true);
                  $x++;
                }

                if(count($print_array) == 0){
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',0,'C',true);
                  $pdf->Cell(25,8,'0','T',1,'R',true);
                }else{
                  $pdf->Cell(12,8,'','T',0,'C',true);
                  $pdf->Cell(20,8,'','T',0,'C',true);
                  $pdf->Cell(83,8,'Total','T',0,'C',true);
                  $pdf->Cell(25,8,$cr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$dr_total,'T',0,'C',true);
                  $pdf->Cell(25,8,$print_array[count($print_array)-1]["balance"],'T',1,'R');
                }

                $pdf->Ln();

                $pdf->Cell(190,8,'Note : If balance is in Positive(+ve) Amount will be Due else It is balance',0,1,true);
                $pdf->Ln();

                $pdf->output();


  }


}




?>
