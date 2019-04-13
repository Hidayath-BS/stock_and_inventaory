<?php
session_start();
require("../dbconnect.php");

$crateTrackerId = $_POST["crateTrackerId"];
$crate_count = $_POST["crate_count"];
$crate_amount = $_POST["crate_amount"];

$personData = "SELECT HKCT.person_id,HKP.first_name,HKP.last_name
              FROM `hk_crate_tracker` AS HKCT
              left JOIN hk_persons AS HKP ON HKP.id = HKCT.person_id
              WHERE id = '$crateTrackerId'";
$exe = mysqli_query($conn,$personData);
while($row = mysqli_fetch_array($exe)){
  $person_id = $row["person_id"];
  $person_name = $row["first_name"]." ".$row["last_name"];
}

$date = $_POST["date"];

function cashBal(){
  require("../dbconnect.php");

  $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
  $cashBalExe = mysqli_query($conn,$cashBalQ);
  while($cashBalRow = mysqli_fetch_array($cashBalExe)){
    $cashBal = $cashBalRow["balance"];
  }

  if($cashBal == ""){
    $cashBal=0;
  }

return $cashBal;

}

function cashBookEntry($particulars,$date,$cr,$dr,$balane){

  require("../dbconnect.php");

  $Query = "INSERT INTO `hk_cash_book`(`particulars`, `date`, `cr`, `dr`, `balance`)
   VALUES ('$particulars','$date','$cr','$dr','$balane')";

   if(mysqli_query($conn,$Query)){
     return true;
   }
   else{
     return false;
   }

}

function personBalance($personId){

  require("../dbconnect.php");

  $query = "SELECT balance FROM hk_account_".$personId." ORDER by id DESC LIMIT 1";
  $exe = mysqli_query($conn,$query);
  while($row = mysqli_fetch_array($exe)){
    $personBal = $row["balance"];
  }
if($personBal == ""){
  $personBal = 0;
}
return $personBal;

}

// entry to particular person account
function personAccEntry($supplier_id,$date,$particulars,$cr,$dr,$balance){

  require("../dbconnect.php");

  $query = "INSERT INTO `hk_account_".$supplier_id."`(`date`, `particulars`, `cr`, `dr`, `balance`)
   VALUES ('$date','$particulars','$cr','$dr','$balance')";

   if(mysqli_query($conn,$query)){
    return true;
  }else{
    return false;
  }

}


$cashBal = cashBal();
// cashbook Entry
$cr = $crate_amount;
$dr = 0;
$particulars = "Crate return from $person_name";
$balance = $cashBal-$cr;

cashBookEntry($particulars,$date,$cr,$dr,$balance);

$person_Bal = personBalance($person_id);
$person_particulars = "Crate Return";
$person_cr = 0;
$person_dr = $crate_amount;
$personbalance = $person_Bal+$person_dr;
personAccEntry($person_id,$date,$person_particulars,$person_cr,$person_dr,$personbalance);



$crateParticulars = "Crates Return From: $person_name";



$crateAccQ = "INSERT INTO `hk_crate_account`(`particulars`, `date`, `number_of_crates`, `given/return`, `amount`)
 VALUES ('$crateParticulars','$date','$crate_count','RETURN','$crate_amount')";

mysqli_query($conn,$crateAccQ);


// update in crate tracker
$crateTrackerQ = "UPDATE `hk_crate_tracker` SET `number_of_crates`=`number_of_crates`-$crate_count,`amount`=`amount`-$crate_amount WHERE `id` = $crateTrackerId";

if(mysqli_query($conn,$crateTrackerQ)){
  $_SESSION['message']="Crate Return successfully";
}else{
$_SESSION['message']="Sorry!!!".mysqli_error($conn);
}

header("Location:../cret_system_list.php");

 ?>
