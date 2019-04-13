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


$product_array = array();
$purchase_temp_array = array();
$sales_array = array();
$sales_temp_array = array();

$total_purchase_amount = 0;
$total_sales_amount = 0;

function purchase_stock($product_id,$actualOndate){
  require ('dbconnect.php');
  $product_array1 = array();

  $query = "SELECT SUM(HKST.add_stock) as purchase_stock,SUM(HKST.amount) AS purchase_amount
            FROM `hk_stock_tracker` AS HKST
            WHERE HKST.date = '$actualOndate' AND HKST.product_id = '$product_id' AND HKST.particulars = 'PURCHASE'";
  $exe = mysqli_query($conn,$query);

      $row = mysqli_fetch_array($exe);
      if($row['purchase_stock'] == ""){
        $row['purchase_stock'] = 0;
      }

      if($row['purchase_amount'] == ""){
        $row['purchase_amount'] = 0;
      }

      $product_array1["purchase_stock"] = $row['purchase_stock'];
      $product_array1["purchase_amount"] = $row['purchase_amount'];
      return $product_array1;
      }

      function purchase_stock_1($product_id,$actualFromdate,$actualTodate){
        require ('dbconnect.php');
        $product_array1 = array();

        $query = "SELECT SUM(HKST.add_stock) as purchase_stock,SUM(HKST.amount) AS purchase_amount
                  FROM `hk_stock_tracker` AS HKST
                  WHERE HKST.date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKST.product_id = '$product_id' AND HKST.particulars = 'PURCHASE'";
        $exe = mysqli_query($conn,$query);

            $row = mysqli_fetch_array($exe);
            if($row['purchase_stock'] == ""){
              $row['purchase_stock'] = 0;
            }

            if($row['purchase_amount'] == ""){
              $row['purchase_amount'] = 0;
            }

            $product_array1["purchase_stock"] = $row['purchase_stock'];
            $product_array1["purchase_amount"] = $row['purchase_amount'];
            return $product_array1;
            }


      function sales_stock($product_id,$actualOndate){
        require ('dbconnect.php');
        $product_array1 = array();

        $query = "SELECT SUM(HKST.sub_stock) as sales_stock,SUM(HKST.amount) AS sales_amount
                  FROM `hk_stock_tracker` AS HKST
                  WHERE HKST.date = '$actualOndate' AND HKST.product_id = '$product_id' AND HKST.particulars = 'SALES'";
        $exe = mysqli_query($conn,$query);

            $row = mysqli_fetch_array($exe);
            if($row['sales_stock'] == ""){
              $row['sales_stock'] = 0;
            }
            if($row['sales_amount'] == ""){
              $row['sales_amount'] = 0;
            }

            $product_array1["sales_stock"] = $row['sales_stock'];
            $product_array1["sales_amount"] = $row['sales_amount'];
            return $product_array1;
      }

      function sales_stock_1($product_id,$actualFromdate,$actualTodate){
        require ('dbconnect.php');
        $product_array1 = array();

        $query = "SELECT SUM(HKST.sub_stock) as sales_stock,SUM(HKST.amount) AS sales_amount
                  FROM `hk_stock_tracker` AS HKST
                  WHERE HKST.date BETWEEN '$actualFromdate' AND '$actualTodate' AND HKST.product_id = '$product_id' AND HKST.particulars = 'SALES'";
        $exe = mysqli_query($conn,$query);

            $row = mysqli_fetch_array($exe);
            if($row['sales_stock'] == ""){
              $row['sales_stock'] = 0;
            }
            if($row['sales_amount'] == ""){
              $row['sales_amount'] = 0;
            }

            $product_array1["sales_stock"] = $row['sales_stock'];
            $product_array1["sales_amount"] = $row['sales_amount'];
            return $product_array1;
      }

function stock($product_id,$actualOndate){
require ('dbconnect.php');

  $query = "SELECT SUM(add_stock) AS purchase_stock, SUM(sub_stock) AS sales_stock
            FROM `hk_stock_tracker` WHERE product_id = '$product_id' AND date <= '$actualOndate'";
  $exe = mysqli_query($conn,$query);
  $row = mysqli_fetch_array($exe);
  $purchase_stock = $row['purchase_stock'];
  $sales_stock = $row['sales_stock'];
  return $purchase_stock-$sales_stock;

}

function stock_1($product_id,$actualFromdate,$actualTodate){
require ('dbconnect.php');

  $query = "SELECT SUM(add_stock) AS purchase_stock, SUM(sub_stock) AS sales_stock
            FROM `hk_stock_tracker` WHERE product_id = '$product_id' AND date <= '$actualTodate'";
  $exe = mysqli_query($conn,$query);
  $row = mysqli_fetch_array($exe);
  $purchase_stock = $row['purchase_stock'];
  $sales_stock = $row['sales_stock'];
  return $purchase_stock-$sales_stock;

}


if($dateType=="onDate"){
  if($_POST["product"] == "allproducts"){


    $query = "SELECT * from `hk_products`";
    $exe = mysqli_query($conn,$query);
    $x= 0;
    while($row = mysqli_fetch_array($exe)){
            		$product_array[$x]["product_id"] = $row['id'];
            		$product_array[$x]["product_name"] = $row['name']." ".$row['type']." ".$row['quantity_type'];
                $x++;
            	}

    for($x = 0;$x < count($product_array); $x++){
    $purchase_temp_array = purchase_stock($product_array[$x]["product_id"],$actualOndate);
    $product_array[$x]["purchase_stock"] = $purchase_temp_array["purchase_stock"];
    $product_array[$x]["purchase_amount"] = $purchase_temp_array["purchase_amount"];

    $sales_temp_array = sales_stock($product_array[$x]["product_id"],$actualOndate);
    $product_array[$x]["sales_stock"] = $sales_temp_array["sales_stock"];
    $product_array[$x]["sales_amount"] = $sales_temp_array["sales_amount"];

    $product_array[$x]["product_stock"] = stock($product_array[$x]["product_id"],$actualOndate);


    }

for($x = 0;$x < count($product_array); $x++){
  $total_purchase_amount = $total_purchase_amount + $product_array[$x]["purchase_amount"];
  $total_sales_amount = $total_sales_amount + $product_array[$x]["sales_amount"];
}


    $pdf= new FPDF('p','mm','A4');
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 20);

    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(130,5,'Stock Report',0,1,'L');
    $pdf->Ln();
    $pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);

    $width_cell=array(10,30,20,20,40,20,60,20,60,20,60);
    $pdf->SetFillColor(255,255,255);

    $pdf->Cell(15,14,'Sl No.','B',0,'C',true);
    $pdf->Cell(55,14,'Product Name','B',0,'C',true);

    $pdf->Cell(45,7,'Purchase',0,0,'C',true);

    $pdf->Cell(40,7,'Sales',0,0,'C',true);
    $pdf->Cell(35,7,'Stock',0,1,'C',true);

    $pdf->Cell(70,7,'',0,0,'C');

    $pdf->Cell(17,7,'Qty','B',0,'C',true);
    $pdf->Cell(28,7,'Amount','B',0,'C',true);
    $pdf->Cell(17,7,'Qty','B',0,'C',true);
    $pdf->Cell(23,7,'Amount','B',0,'C',true);
    $pdf->Cell(35,7,'Qty','B',1,'C',true);

    $pdf->SetFont('Arial','',10);
    $pdf->SetFillColor(255,255,255);
    for($i=0;$i<count($product_array);$i++){
      $pdf->Cell(15,14,$i+1,0,0,'C');
      $pdf->Cell(55,14,$product_array[$i]["product_name"],0,0,'C');
      $pdf->Cell(17,14,$product_array[$i]["purchase_stock"],0,0,'C');
      $pdf->Cell(28,14,$product_array[$i]["purchase_amount"],0,0,'C');
      $pdf->Cell(17,14,$product_array[$i]["sales_stock"],0,0,'C');
      $pdf->Cell(23,14,$product_array[$i]["sales_amount"],0,0,'C');
      $pdf->Cell(35,14,$product_array[$i]["product_stock"],0,1,'C');
    }


    $pdf->Cell(70,14,'Total : ','T',0,'R',true);
    $pdf->Cell(17,14,'','T',0,'C');
    $pdf->Cell(28,14,$total_purchase_amount,'T',0,'C');
    $pdf->Cell(17,14,'','T',0,'C');
    $pdf->Cell(23,14,$total_sales_amount,'T',0,'C');
    $pdf->Cell(35,14,'','T',1,'C');

    $pdf->output();
  }

  else{
    $product_id = $_POST["product_id"];

    $query = "SELECT * from `hk_products` WHERE id = '$product_id'";
    $exe = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($exe);
            		$product_array[0]["product_id"] = $row['id'];
            		$product_array[0]["product_name"] = $row['name']." ".$row['type']." ".$row['quantity_type'];

    $purchase_temp_array = purchase_stock($product_array[0]["product_id"],$actualOndate);
    $product_array[0]["purchase_stock"] = $purchase_temp_array["purchase_stock"];
    $product_array[0]["purchase_amount"] = $purchase_temp_array["purchase_amount"];

    $sales_temp_array = sales_stock($product_array[0]["product_id"],$actualOndate);
    $product_array[0]["sales_stock"] = $sales_temp_array["sales_stock"];
    $product_array[0]["sales_amount"] = $sales_temp_array["sales_amount"];

    $product_array[0]["product_stock"] = stock($product_array[0]["product_id"],$actualOndate);


  $total_purchase_amount = $product_array[0]["purchase_amount"];
  $total_sales_amount =  $product_array[0]["sales_amount"];




$pdf= new FPDF('p','mm','A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 20);

$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(130,5,'Stock Report',0,1,'L');
$pdf->Ln();
$pdf->Cell(130,5,'Date : '.$ondate,0,1,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',10);

$width_cell=array(10,30,20,20,40,20,60,20,60,20,60);
$pdf->SetFillColor(255,255,255);

$pdf->Cell(15,14,'Sl No.','B',0,'C',true);
$pdf->Cell(55,14,'Product Name','B',0,'C',true);

$pdf->Cell(45,7,'Purchase',0,0,'C',true);

$pdf->Cell(40,7,'Sales',0,0,'C',true);
$pdf->Cell(35,7,'Stock',0,1,'C',true);
$pdf->Cell(15,7,'',0,0,'C');
$pdf->Cell(55,7,'',0,0,'C');

$pdf->Cell(17,7,'Qty','B',0,'C',true);
$pdf->Cell(28,7,'Amount','B',0,'C',true);
$pdf->Cell(17,7,'Qty','B',0,'C',true);
$pdf->Cell(23,7,'Amount','B',0,'C',true);
$pdf->Cell(35,7,'Qty','B',1,'C',true);

$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(255,255,255);
  $pdf->Cell(15,14,'1',0,0,'C');
  $pdf->Cell(55,14,$product_array[0]["product_name"],0,0,'C');
  $pdf->Cell(17,14,$product_array[0]["purchase_stock"],0,0,'C');
  $pdf->Cell(28,14,$product_array[0]["purchase_amount"],0,0,'C');
  $pdf->Cell(17,14,$product_array[0]["sales_stock"],0,0,'C');
  $pdf->Cell(23,14,$product_array[0]["sales_amount"],0,0,'C');
  $pdf->Cell(35,14,$product_array[0]["product_stock"],0,1,'C');


$pdf->Cell(70,14,'Total : ','T',0,'R',true);
$pdf->Cell(17,14,'','T',0,'C');
$pdf->Cell(28,14,$total_purchase_amount,'T',0,'C');
$pdf->Cell(17,14,'','T',0,'C');
$pdf->Cell(23,14,$total_sales_amount,'T',0,'C');
$pdf->Cell(35,14,'','T',1,'C');


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
    if($_POST["product"] == "allproducts"){

      $query = "SELECT * from `hk_products`";
      $exe = mysqli_query($conn,$query);
      $x= 0;
      while($row = mysqli_fetch_array($exe)){
                  $product_array[$x]["product_id"] = $row['id'];
                  $product_array[$x]["product_name"] = $row['name']." ".$row['type']." ".$row['quantity_type'];
                  $x++;
                }

      for($x = 0;$x < count($product_array); $x++){
      $purchase_temp_array = purchase_stock_1($product_array[$x]["product_id"],$actualFromdate,$actualTodate);
      $product_array[$x]["purchase_stock"] = $purchase_temp_array["purchase_stock"];
      $product_array[$x]["purchase_amount"] = $purchase_temp_array["purchase_amount"];

      $sales_temp_array = sales_stock_1($product_array[$x]["product_id"],$actualFromdate,$actualTodate);
      $product_array[$x]["sales_stock"] = $sales_temp_array["sales_stock"];
      $product_array[$x]["sales_amount"] = $sales_temp_array["sales_amount"];

      $product_array[$x]["product_stock"] = stock_1($product_array[$x]["product_id"],$actualFromdate,$actualTodate);


      }

  for($x = 0;$x < count($product_array); $x++){
    $total_purchase_amount = $total_purchase_amount + $product_array[$x]["purchase_amount"];
    $total_sales_amount = $total_sales_amount + $product_array[$x]["sales_amount"];
  }



  $pdf= new FPDF('p','mm','A4');
  $pdf->AddPage();

  $pdf->SetFont('Arial', 'B', 20);

  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
  $pdf->Ln();
  $pdf->SetFont('Arial','B',14);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(130,5,'Stock Report',0,1,'L');
  $pdf->Ln();
  $pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
  $pdf->Ln();
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);

  $width_cell=array(10,30,20,20,40,20,60,20,60,20,60);
  $pdf->SetFillColor(255,255,255);

  $pdf->Cell(15,14,'Sl No.','B',0,'C',true);
  $pdf->Cell(55,14,'Product Name','B',0,'C',true);

  $pdf->Cell(45,7,'Purchase',0,0,'C',true);

  $pdf->Cell(40,7,'Sales',0,0,'C',true);
  $pdf->Cell(35,7,'Stock',0,1,'C',true);

  $pdf->Cell(70,7,'',0,0,'C');

  $pdf->Cell(17,7,'Qty','B',0,'C',true);
  $pdf->Cell(28,7,'Amount','B',0,'C',true);
  $pdf->Cell(17,7,'Qty','B',0,'C',true);
  $pdf->Cell(23,7,'Amount','B',0,'C',true);
  $pdf->Cell(35,7,'Qty','B',1,'C',true);

  $pdf->SetFont('Arial','',10);
  $pdf->SetFillColor(255,255,255);
  for($i=0;$i<count($product_array);$i++){
    $pdf->Cell(15,14,$i+1,0,0,'C');
    $pdf->Cell(55,14,$product_array[$i]["product_name"],0,0,'C');
    $pdf->Cell(17,14,$product_array[$i]["purchase_stock"],0,0,'C');
    $pdf->Cell(28,14,$product_array[$i]["purchase_amount"],0,0,'C');
    $pdf->Cell(17,14,$product_array[$i]["sales_stock"],0,0,'C');
    $pdf->Cell(23,14,$product_array[$i]["sales_amount"],0,0,'C');
    $pdf->Cell(35,14,$product_array[$i]["product_stock"],0,1,'C');
  }


  $pdf->Cell(70,14,'Total : ','T',0,'R',true);
  $pdf->Cell(17,14,'','T',0,'C');
  $pdf->Cell(28,14,$total_purchase_amount,'T',0,'C');
  $pdf->Cell(17,14,'','T',0,'C');
  $pdf->Cell(23,14,$total_sales_amount,'T',0,'C');
  $pdf->Cell(35,14,'','T',1,'C');

  $pdf->output();
    }
    else{
      $product_id = $_POST["product_id"];

      $query = "SELECT * from `hk_products` WHERE id = '$product_id'";
      $exe = mysqli_query($conn,$query);
      $row = mysqli_fetch_array($exe);
              		$product_array[0]["product_id"] = $row['id'];
              		$product_array[0]["product_name"] = $row['name']." ".$row['type']." ".$row['quantity_type'];

      $purchase_temp_array = purchase_stock_1($product_array[0]["product_id"],$actualFromdate,$actualTodate);
      $product_array[0]["purchase_stock"] = $purchase_temp_array["purchase_stock"];
      $product_array[0]["purchase_amount"] = $purchase_temp_array["purchase_amount"];

      $sales_temp_array = sales_stock_1($product_array[0]["product_id"],$actualFromdate,$actualTodate);
      $product_array[0]["sales_stock"] = $sales_temp_array["sales_stock"];
      $product_array[0]["sales_amount"] = $sales_temp_array["sales_amount"];

      $product_array[0]["product_stock"] = stock_1($product_array[0]["product_id"],$actualFromdate,$actualTodate);


    $total_purchase_amount = $product_array[0]["purchase_amount"];
    $total_sales_amount =  $product_array[0]["sales_amount"];

  $pdf= new FPDF('p','mm','A4');
  $pdf->AddPage();

  $pdf->SetFont('Arial', 'B', 20);

  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(190,5,'K.ABDUL KAREEM & SONS',0,10,'C');
  $pdf->Ln();
  $pdf->SetFont('Arial','B',14);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell(130,5,'Stock Report',0,1,'L');
  $pdf->Ln();
  $pdf->Cell(130,5,'Between Date : from '.$fromdate." to ".$todate,0,1,'L');
  $pdf->Ln();
  $pdf->Ln();
  $pdf->SetFont('Arial','B',10);

  $width_cell=array(10,30,20,20,40,20,60,20,60,20,60);
  $pdf->SetFillColor(255,255,255);

  $pdf->Cell(15,14,'Sl No.','B',0,'C',true);
  $pdf->Cell(55,14,'Product Name','B',0,'C',true);

  $pdf->Cell(45,7,'Purchase',0,0,'C',true);

  $pdf->Cell(40,7,'Sales',0,0,'C',true);
  $pdf->Cell(35,7,'Stock',0,1,'C',true);
  $pdf->Cell(15,7,'',0,0,'C');
  $pdf->Cell(55,7,'',0,0,'C');

  $pdf->Cell(17,7,'Qty','B',0,'C',true);
  $pdf->Cell(28,7,'Amount','B',0,'C',true);
  $pdf->Cell(17,7,'Qty','B',0,'C',true);
  $pdf->Cell(23,7,'Amount','B',0,'C',true);
  $pdf->Cell(35,7,'Qty','B',1,'C',true);

  $pdf->SetFont('Arial','',10);
  $pdf->SetFillColor(255,255,255);
    $pdf->Cell(15,14,'1',0,0,'C');
    $pdf->Cell(55,14,$product_array[0]["product_name"],0,0,'C');
    $pdf->Cell(17,14,$product_array[0]["purchase_stock"],0,0,'C');
    $pdf->Cell(28,14,$product_array[0]["purchase_amount"],0,0,'C');
    $pdf->Cell(17,14,$product_array[0]["sales_stock"],0,0,'C');
    $pdf->Cell(23,14,$product_array[0]["sales_amount"],0,0,'C');
    $pdf->Cell(35,14,$product_array[0]["product_stock"],0,1,'C');


  $pdf->Cell(70,14,'Total : ','T',0,'R',true);
  $pdf->Cell(17,14,'','T',0,'C');
  $pdf->Cell(28,14,$total_purchase_amount,'T',0,'C');
  $pdf->Cell(17,14,'','T',0,'C');
  $pdf->Cell(23,14,$total_sales_amount,'T',0,'C');
  $pdf->Cell(35,14,'','T',1,'C');

  $pdf->output();
    }
  }
}


?>
