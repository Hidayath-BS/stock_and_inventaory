<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');
$person_array = array();
$print_array = array();
$total_amount = 0;
$person_array_length = 0;
$print_array_index = 0;
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


	if($_POST["city"] == "allcities"){

		$query  = "SELECT id,first_name,last_name FROM  `hk_persons` WHERE person_type_id = '1'";
	             $exe = mysqli_query($conn,$query);
	             $person_array_length = mysqli_num_rows($exe);
	               $x = 0;
	             while($row = mysqli_fetch_array($exe)){
	               $person_array[$x]["id"] = $row['id'];
	               $person_array[$x]["supplier_name"] = $row['first_name']." ".$row['last_name'];
	               $x++;
	             }

							 for($x=0;$x<$person_array_length;$x++){

							   $query = "SELECT SUM(cr) as credit,SUM(dr) as debit FROM hk_account_".$person_array[$x]["id"]." WHERE active = 1";
							     $exe = mysqli_query($conn,$query);
                   if(mysqli_num_rows($exe)==0){
                     $personBal = 0;
                   }
                   else{
                     while($row = mysqli_fetch_array($exe)){
  							        $personBal = $row["debit"]-$row["credit"];
  							     }
                   }

							   if($personBal < 0){
							     $personBal = $personBal;
							     $print_array[$print_array_index]["supplier_name"] = $person_array[$x]["supplier_name"];
							     $print_array[$print_array_index]["balance"] = $personBal;
									 $total_amount = $total_amount + $personBal;
							     $print_array_index++;
							   }

							 }

    $date = date('d-m-Y');
		$pdf = new PDF();
	  $pdf->AliasNbPages();
    $pdf->AddPage();
    // $pdf->SetFont('Arial', 'B', 20);
    // $pdf->SetTextColor(0,0,255);
		//
    // $pdf->Cell(190,5,'M/s MUNEER AHMED',0,10,'C');
    // $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0,0,0);

    $pdf->Cell(130,5,'Supplier Balance Report',0,1,'L');
    $pdf->Ln();
    $pdf->Cell(20,5,'On Date : ',0,0,'L');
    $pdf->Cell(30,5,$date,0,1,'L');

    $pdf->Cell(190,5,'',0,1);
    $width_cell=array(20,60,20,60);
    $pdf->SetFillColor(255,255,255);

    $pdf->Cell(30,8,'Sl No. ','B',0,'C',true);
    $pdf->Cell(110,8,'Supplier Name','B',0,'L',true);
    $pdf->Cell(50,8,'Balance Amount','B',1,'R',true);
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(0,0,0);

    if(count($print_array) == 0){

    }else{
      $x = 1;
      for($y=0;$y<count($print_array);$y++){
         $pdf->Cell(30,8,$x,0,0,'C',true);
         $pdf->Cell(110,8,strtoupper($print_array[$y]["supplier_name"]),0,0,'L',true);
         $pdf->Cell(50,8,money_format('%!i',$print_array[$y]["balance"]),0,1,'R',true);
         $x++;
       }
    }


    if(count($print_array) == 0){
      $pdf->Cell(140,8,'Total : ','T',0,'R',true);
  		 $pdf->Cell(50,8,'0','T',1,'R',true);
    }else{
      $pdf->Cell(140,8,'Total : ','T',0,'R',true);
  		 $pdf->Cell(50,8,money_format('%!i',$total_amount),'T',1,'R',true);
    }

		 $pdf->Ln();
    $pdf->output();
  }else if($_POST["city"] == "selectedcities"){
    $city = "";
    $city_id = $_POST["city_id"];
    $query = "SELECT city_name FROM `hk_cities` WHERE id = '$city_id'";
    $exe = mysqli_query($conn,$query);
    while($row = mysqli_fetch_array($exe)){
      $city = $row['city_name'];
    }

		$query  = "SELECT id,first_name,last_name FROM  `hk_persons` WHERE person_type_id = '1' AND city_id = '$city_id'";
	             $exe = mysqli_query($conn,$query);
	             $person_array_length = mysqli_num_rows($exe);
	               $x = 0;
	             while($row = mysqli_fetch_array($exe)){
	               $person_array[$x]["id"] = $row['id'];
	               $person_array[$x]["supplier_name"] = $row['first_name']." ".$row['last_name'];
	               $x++;
	             }
							 for($x=0;$x<$person_array_length;$x++){

                 $query = "SELECT SUM(cr) as credit,SUM(dr) as debit FROM hk_account_".$person_array[$x]["id"]." WHERE active = 1";
							     $exe = mysqli_query($conn,$query);
                   if(mysqli_num_rows($exe)==0){
                     $personBal = 0;
                   }
                   else{
                     while($row = mysqli_fetch_array($exe)){
  							        $personBal = $row["debit"]-$row["credit"];
  							     }
                   }

                 if($personBal < 0){
							     $personBal = $personBal;
							     $print_array[$print_array_index]["supplier_name"] = $person_array[$x]["supplier_name"];
							     $print_array[$print_array_index]["balance"] = $personBal;
									  $total_amount = $total_amount + $personBal;
							     $print_array_index++;
							   }

							 }

    $date = date('d-m-Y');
		$pdf = new PDF();
		$pdf->AliasNbPages();
    $pdf->AddPage();
    // $pdf->SetFont('Arial', 'B', 20);
    // $pdf->SetTextColor(0,0,255);
		//
    // $pdf->Cell(190,5,'M/s MUNEER AHMED',0,10,'C');
    // $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0,0,0);

    $pdf->Cell(130,5,'Supplier Balance Report of '.$city.' City',0,1,'L');
    $pdf->Ln();
    $pdf->Cell(20,5,'On Date : ',0,0,'L');
    $pdf->Cell(30,5,$date,0,1,'L');

    $pdf->Cell(190,5,'',0,1);
    $width_cell=array(20,60,20,60);
    $pdf->SetFillColor(255,255,255);

    $pdf->Cell(30,8,'Sl No. ','B',0,'C',true);
    $pdf->Cell(110,8,'Supplier Name','B',0,'L',true);
    $pdf->Cell(50,8,'Balance Amount','B',1,'R',true);
		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(0,0,0);

    if(count($print_array) == 0){

    }else{
      $x = 1;
      for($y=0;$y<count($print_array);$y++){
         $pdf->Cell(30,8,$x,0,0,'C',true);
         $pdf->Cell(110,8,strtoupper($print_array[$y]["supplier_name"]),0,0,'L',true);
         $pdf->Cell(50,8,money_format('%!i',$print_array[$y]["balance"]),0,1,'R',true);
         $x++;
       }
    }


    if(count($print_array) == 0){
      $pdf->Cell(140,8,'Total : ','T',0,'R',true);
      $pdf->Cell(50,8,'0','T',1,'R',true);
    }else{
      $pdf->Cell(140,8,'Total : ','T',0,'R',true);
      $pdf->Cell(50,8,money_format('%!i',$total_amount),'T',1,'R',true);
    }
		 $pdf->Ln();
    $pdf->output();
  }


 ?>
