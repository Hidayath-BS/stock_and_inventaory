<?php
require('fpdf181/fpdf.php');
require ('dbconnect.php');

$Customer_array = array();

$product_id = $_POST["product_id"];



$total_purchase_amount = 0;
$total_sales_amount = 0;


error_reporting(E_ERROR | E_PARSE);

$query = "SELECT * from hk_persons WHERE person_type_id = 2 && person_active =1";

$exe = mysqli_query($conn,$query);

$i=0;
while($row = mysqli_fetch_array($exe)){
    $Customer_array[$i]["sl"] = $i;
    $Customer_array[$i]["customer_id"] = $row["id"] ;
    $Customer_array[$i]["customer_name"] = $row["first_name"]." ".$row["last_name"];
    $Customer_array[$i]["count"] = 0;
    $i++;
}


$prodQ = "SELECT name, type, quantity_type from hk_products WHERE id =$product_id";

$prodExe = mysqli_query($conn,$prodQ);

while($prodRow = mysqli_fetch_array($prodExe)){
    $prodName = $prodRow["name"]." ".$prodRow["type"];
    $prodQ = $prodRow["quantity_type"];
}



$sales_array = array();
$sales_ret_array = array();
$customer_sales_count = array();
$customer_sales_ret_count = array();
   
for($i = 0;$i<count($Customer_array); $i++){

    $customer_id =$Customer_array[$i]["customer_id"];

    $sl_no = $Customer_array[$i]["sl"];
    $sales_array[$i] =salesId($customer_id); 
    $sales_ret_array[$i] = salesRetId($customer_id);

}


for($j = 0; $j<count($sales_array); $j++){
    
    $customer_sales_count[$j]["count"] = 0;
    $customer_sales_ret_count[$j]["count"] = 0;
    for($l =0; $l<count($sales_array[$j]); $l++){
        // customer sales count
        
        // $customer_sales_count[$i]["sales"]= 0;
         
            
           $customer_sales_count[$j]["count"] = $customer_sales_count[$j]["count"] + retSalesQuantity($sales_array[$j][$l]["sales_id"], $product_id);
           $customer_sales_ret_count[$j]["count"] = $customer_sales_ret_count[$j]["count"] + retSalesRetQuantity($sales_ret_array[$j][$l]["sales_id"], $product_id);
    }
        $Customer_array[$j]["count"] = $customer_sales_count[$j]["count"] - $customer_sales_ret_count[$j]["count"];
        if($Customer_array[$j]["count"] == ""){
            $Customer_array[$j]["count"] = 0;
        }

}

// print_r($customer_sales_count);

// print_r($sales_array);
// echo "<br>";
// print_r($customer_sales_count);

// echo "<br>";
// print_r($sales_ret_array);
// echo "<br>";
// print_r($customer_sales_ret_count);

// echo "<br>";
// print_r($Customer_array);



// Array ( [0] => Array ( [151] => Array ( [0] => Array ( [sales_id] => 1 ) ) ) [1] => Array ( [153] => Array ( [0] => Array ( [sales_id] => 2 ) ) ) ) 


function salesId($customer_id){
    require ('dbconnect.php');
    $query = "SELECT id from hk_sales WHERE person_id = $customer_id && `sales_active`=1";

    $exe = mysqli_query($conn,$query);

    $sales_row = array();
    $i = 0;
    while($row = mysqli_fetch_array($exe)){
        $sales_row[$i]["sales_id"] = $row["id"];
        $i++;
    }
    return $sales_row;
}

// print_r(salesId(153));

function retSalesQuantity($saleId , $product_id){
    require ('dbconnect.php');
    $query = "SELECT quantity from hk_sales_products WHERE sales_id = $saleId && product_id =$product_id";
    // echo "<br>$query <br>";
    $exe = mysqli_query($conn,$query);
    $quantity = "";
    while($row = mysqli_fetch_array($exe)){
        $quantity = $row["quantity"];
    }
    if($quantity == ""){
        $quantity = 0;
    }



    return $quantity;

}


function salesRetId($customer_id){
    require ('dbconnect.php');
    $query = "SELECT id FROM hk_sales_return WHERE person_id= $customer_id && sales_return_active = 1";

    $exe = mysqli_query($conn,$query);

    $sales_row = array();
    $i = 0;
    while($row = mysqli_fetch_array($exe)){
        $sales_row[$i]["sales_id"] = $row["id"];
        $i++;
    }
    return $sales_row;
}


function retSalesRetQuantity($saleId , $product_id){
    require ('dbconnect.php');
    $query = "SELECT quantity FROM hk_sales_return_products WHERE sales_return_id = $saleId && product_id = $product_id";
    // echo "<br>$query <br>";
    $exe = mysqli_query($conn,$query);
    $quantity = "";
    while($row = mysqli_fetch_array($exe)){
        $quantity = $row["quantity"];
    }
    if($quantity == ""){
        $quantity = 0;
    }



    return $quantity;

}

// echo retSalesQuantity(2, 2);

class PDF extends FPDF
{
    function Header(){
         $this->SetFont('Arial','B',15);
    // Move to the right
    // $this->Cell(80);
    // Title
    $this->Cell(190,10,'K.ABDUL KAREEM & SONS',0,0,'C');
    // Line break
    $this->Ln(20);
    }
    function Footer(){
        $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
  


               $pdf = new PDF();
               $pdf->AliasNbPages();
               $pdf->AddPage();
               
               $pdf->SetFont('Arial','B',12);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(130,5,'Crate Report of '.$prodName,0,1,'L');
               $pdf-> Ln();
               
               $width_cell=array(20,60,20,60);
               $pdf->SetFillColor(255,255,255);

               $pdf->SetFont('Arial','B',10);
               $pdf->SetTextColor(0,0,0);

               $pdf->Cell(20,8,'Sl No. ','B',0,'C',true);
               $pdf->Cell(85,8,'Customer Name','B',0,'L',true);
               $pdf->Cell(85,8,'Crate Balance','B',1,'R',true);
               
               $pdf->SetFont('Arial','',10);

               $j=1;
               for($i =0; $i<count($Customer_array); $i++){
                    $pdf->Cell(20,8,$j,0,0,'C');
                    $pdf->Cell(85,8,strtoupper($Customer_array[$i]["customer_name"]),0,0,'L');
                    $pdf->Cell(85,8,strtoupper($Customer_array[$i]["count"]." ".$prodQ),0,1,'R');
                    $j++;
               }


               $pdf->Ln();
               $pdf->output();
?>




