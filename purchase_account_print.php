<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
$ondate = $_POST["ondate"];

$actualOndate = $ondate;

$ondate = date("d-m-Y", strtotime($ondate));

$print_array = array();
$print_array_length = 0;
$total_amount = 0;

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



  $query  = "SELECT * FROM `hk_purchase_account` WHERE date = '$actualOndate' &&  `active`='1'";
             $exe = mysqli_query($conn,$query);
             $print_array_length = mysqli_num_rows($exe);
               $x = 0;
             while($row = mysqli_fetch_array($exe)){
               $print_array[$x]["date"] = $row['date'];
               $print_array[$x]["particulars"] = $row['particulars'];
               $print_array[$x]["bill_number"] = $row['bill_number'];
               // $print_array[$x]["first_name"] = $row['first_name'];
               $print_array[$x]["credit/cash"] = $row['credit/cash'];
               $print_array[$x]["amount"] = $row['amount'];
               $total_amount = $total_amount + $row['amount'];
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

             $pdf->Cell(130,5,'PURCHASE ACCOUNT ENTRIES',0,1,'L');
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
             $pdf->Cell(90,8,'Particulars','B',0,'C',true);
             $pdf->Cell(28,8,'Bill Number','B',0,'C',true);
             $pdf->Cell(20,8,'Mode','B',0,'C',true);
             $pdf->Cell(20,8,'amount','B',1,'C',true);

             $pdf->SetFont('Arial','',10);
             $pdf->SetTextColor(0,0,0);
             $x = 1;
             for($y=0;$y<count($print_array);$y++){
                $pdf->Cell(12,8,$x,0,0,'C',true);
                $pdf->Cell(20,8,$ondate,0,0,'C',true);
                $pdf->Cell(90,8,$print_array[$y]["particulars"],0,0,'C',true);
                $pdf->Cell(28,8,$print_array[$y]["bill_number"],0,0,'C',true);
                $pdf->Cell(20,8,$print_array[$y]["credit/cash"],0,0,'C',true);
                $pdf->Cell(20,8,$print_array[$y]["amount"],0,1,'C',true);
                $x++;
              }

              if(count($print_array) == 0){
                $pdf->Cell(12,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'','T',0,'C',true);
                $pdf->Cell(90,8,'','T',0,'C',true);
                $pdf->Cell(28,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'Total : ','T',0,'C',true);
                $pdf->Cell(20,8,'0','T',1,'C',true);
              }else{
                $pdf->Cell(12,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'','T',0,'C',true);
                $pdf->Cell(90,8,'','T',0,'C',true);
                $pdf->Cell(28,8,'','T',0,'C',true);
                $pdf->Cell(20,8,'Total : ','T',0,'C',true);
                $pdf->Cell(20,8,$total_amount,'T',1,'C',true);
              }


              $pdf->Ln();

              $pdf->output();


?>
