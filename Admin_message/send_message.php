<?php
    require('../dbconnect.php');
    if(isset($_GET["sendmessage"])){
        //echo "Hello <br>";

        date_default_timezone_set("Asia/calcutta");
$date = date('Y-m-d');

        //echo $date."<br>";


setlocale(LC_MONETARY, 'en_IN');

        //sales entry details

        $salessql = "SELECT COUNT(id) AS counter,SUM(total_amount) AS total FROM hk_sales WHERE bill_date ='$date' AND sales_active=1";
        echo $salessql;
    $sqlExe = mysqli_query($conn,$salessql);
    while($sqlRow = mysqli_fetch_array($sqlExe)){
        $salesCount = $sqlRow["counter"];
        $salesAmount = $sqlRow["total"];

    }
        if($salesAmount ==null){
            $salesAmount =0;
        }
       // echo "<br> $salesCount , $salesAmount<br>";


        //sales return details

        $salesreturnSql = "SELECT COUNT(id) AS counter,SUM(amount_to_be_paid) AS total FROM hk_sales_return WHERE date = '$date' AND sales_return_active=1";
        //echo $salesreturnSql;
        $salesretExe = mysqli_query($conn,$salesreturnSql);

        while($salesretRow = mysqli_fetch_array($salesretExe)){
            $salesRetCount = $salesretRow["counter"];
            $salesRetAmount = $salesretRow["total"];
        }
        if($salesRetAmount==null){
            $salesRetAmount = 0;
        }
        //echo "<br>$salesRetCount , $salesRetAmount<br>";




        //purchase entry details

        $purchaseSql = "SELECT COUNT(id) AS counter,SUM(amount_payable) AS total FROM hk_purchases WHERE bill_date = '$date' AND purchases_active=1";

        //echo $purchaseSql;

        $purchaseExe = mysqli_query($conn,$purchaseSql);
        while($purchaseRow = mysqli_fetch_array($purchaseExe)){
            $purchaseCount = $purchaseRow["counter"];
            $purchaseAmount = $purchaseRow["total"];
        }
        if($purchaseAmount== null){
            $purchaseAmount = 0;
        }
        //echo "<br>$purchaseCount , $purchaseAmount<br>";

        //purchase return details


        $purchasereturnQuery = "SELECT COUNT(id) AS counter,SUM(return_amount) AS total FROM hk_purchases_return WHERE date = '$date' AND purchase_return_active=1";

        //echo "$purchasereturnQuery";
        $purchasereturnExe = mysqli_query($conn,$purchasereturnQuery);

        while($purchasereturnRow = mysqli_fetch_array($purchasereturnExe)){
            $puRCount = $purchasereturnRow["counter"];
            $puRAmount = $purchasereturnRow["total"];
        }

        if($puRAmount == null){
            $puRAmount = 0;
        }

        //echo "<br>$puRCount , $puRAmount<br>";


        //select product id form products
        $selproid = "select id from  hk_products";
        $selproExe = mysqli_query($conn,$selproid);
        $i =0;
        while($selproRow = mysqli_fetch_array($selproExe)){
            $prodId[$i] = $selproRow["id"];
            $i++;
        }

        $j =0;


        //transaction details


        $transaction_deails = "SELECT SUM(cr) as credit ,SUM(dr) as debit FROM hk_cash_book WHERE active=1";

        $transactionExe = mysqli_query($conn,$transaction_deails);

        while($transactionRow = mysqli_fetch_array($transactionExe)){
            $credit = $transactionRow["credit"];
            $debit =  $transactionRow["debit"];
        }

        if($credit == null){
            $credit=0;
        }
        if($debit == null){
            $debit =0;
        }

        // echo "<br>$recipts , $payemts<br>";

        $bal = $debit-$credit;


        
         $salesAmount =   money_format('%!i',$salesAmount);
         $salesRetAmount = money_format('%!i', $salesRetAmount);
         $purchaseAmount = money_format('%!i', $purchaseAmount);
         $puRAmount = money_format('%!i', $puRAmount);
         $bal = money_format('%!i', $bal);

        $message = "On $date, This is the current status,sales of $salesCount transactions worth Rs. $salesAmount, sales return of $salesRetCount transactions worth Rs. $salesRetAmount, purchase of $purchaseCount transaction worth Rs. $purchaseAmount, purchase return of $puRCount transaction worth of Rs. $puRAmount, Closing balance is Rs. $bal.";
       echo $message."<br>";




            //mesaage plugin

$username = "ak.enterprise6874@gmail.com";
	$hash = "8f166b2804793ce6abc6a06c1a83ab96b92a37933c571dd7f12637a2c2ec36de";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";



    $sender = "HKHMSG"; // This is who the message appears to be from.
	$numbers = "919448486874";
    // $numbers = "919164503045";
     // A single number or a comma-seperated list of numbers
//	$message = "HI $supplierName, you have been credited with RS.$totalPaid on $purchaseTransType purchase on $date BY HKH \n With Regards HKH SIRSI.";

//echo $message;

    	$message = urlencode($message);
$data = "username= ".$username." &hash= ".$hash." &message= ".$message."&sender= ".$sender."&numbers= ".$numbers." &test= ".$test;
	$ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);


    //msg plugin

echo '<br>'. $result;


// header('Location: ../index.php');

       header('Location: ../index.php');
    }
else{
    echo "Sorry";
    header('Location: ../index.php');
}
?>
