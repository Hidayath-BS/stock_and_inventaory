<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
$ondate = $_POST["ondate"];

setlocale(LC_MONETARY, 'en_IN');

$actualOndate = $ondate;

$ondate = date("d-m-Y", strtotime($ondate));

$print_array = array();
$print_array_length = 0;
$previous_credit_total = 0;
$previous_debit_total = 0;
$opening_balance = 0;
$cr_total = 0;
$dr_total = 0;

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

$opening_balance_query = "SELECT SUM(cr) as credit,SUM(dr) as debit from hk_cash_book WHERE date < '$actualOndate' AND active = '1'";
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

  $query  = "SELECT * FROM `hk_cash_book` WHERE date = '$actualOndate' AND active = '1'";
             $exe = mysqli_query($conn,$query);
             if(mysqli_num_rows($exe)!=0){
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

             $pdf->Cell(130,5,'DAY BOOK ENTRY',0,1,'L');
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
             $pdf->Cell(68,8,'Particulars','B',0,'C',true);
             $pdf->Cell(30,8,'Cr','B',0,'C',true);
             $pdf->Cell(30,8,'Dr','B',0,'C',true);
             $pdf->Cell(30,8,'Balance','B',1,'C',true);

             $pdf->SetFont('Arial','',10);
             $pdf->SetTextColor(0,0,0);

             $pdf->Cell(12,8,'',0,0,'C',true);
             $pdf->Cell(20,8,'',0,0,'C',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(68,8,'OPENING BALANCE',0,0,'L',true);
             $pdf->SetFont('Arial','',10);
             $pdf->Cell(30,8,'-',0,0,'R',true);
             $pdf->Cell(30,8,'-',0,0,'R',true);
             $pdf->Cell(30,8,money_format('%!i',$opening_balance),0,1,'R',true);

             $x = 1;
             for($y=0;$y<count($print_array);$y++){
                $pdf->Cell(12,8,$x,0,0,'C',true);
                $pdf->Cell(20,8,$ondate,0,0,'C',true);
                $pdf->SetFont('Arial','',9);
                $pdf->Cell(68,8,strtoupper($print_array[$y]["particulars"]),0,0,'L',true);
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,8,money_format('%!i',$print_array[$y]["cr"]),0,0,'R',true);
                $pdf->Cell(30,8,money_format('%!i',$print_array[$y]["dr"]),0,0,'R',true);
                $pdf->Cell(30,8,money_format('%!i',$print_array[$y]["balance"]),0,1,'R',true);
                $x++;
              }

              if(count($print_array) == 0){
                $pdf->Cell(12,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'','T',0,'C',true);
                $pdf->Cell(68,8,'Total','T',0,'L',true);
                $pdf->Cell(30,8,'0','T',0,'C',true);
                $pdf->Cell(30,8,'0','T',0,'C',true);
                $pdf->Cell(30,8,'0','T',1,'C',true);
              }else{
                $pdf->Cell(12,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'','T',0,'C',true);
                $pdf->Cell(68,8,'Total','T',0,'L',true);
                $pdf->Cell(30,8,money_format('%!i',$cr_total),'T',0,'R',true);
                $pdf->Cell(30,8,money_format('%!i',$dr_total),'T',0,'R',true);
                $pdf->Cell(30,8,money_format('%!i',$print_array[count($print_array)-1]["balance"]),'T',1,'R');
              }
              $pdf->Ln();

              $pdf->output();


?>
