<?php
session_start();
require('../dbconnect.php');
$supplierName = ucwords($_POST["supplierName"]);
$lastname = ucwords($_POST["lastname"]) ;
$supplierType = $_POST["supplierType"];
$address1 = ucwords($_POST["address1"]);
$address2 = ucwords($_POST["address2"]);
$supplierCity = ucwords($_POST["city"]);
$supplierState = $_POST["state"];
$pincode = $_POST["pincode"];
$supplierPhone = $_POST["supplierPhone"];
$altPhone = $_POST["altPhone"];
$emailid = $_POST["email1"];
$suppAccname = ucwords($_POST["suppAccname"]);
$suppAccountno = $_POST["suppAccountno"];
$suppBranch = ucwords($_POST["suppBranch"]);
$suppIfsc = strtoupper($_POST["suppIfsc"]);
$bankName = $_POST["bankName"];

//
//if ($pincode == null ){
//    $pincode = NULL;
//}


$query = "INSERT INTO `hk_persons` (person_type_id,first_name, last_name , person_role_type_id ,address_line_1,address_line_2 ,city_id ,state_id ,pincode,mobile_number,landline_number,email,bank_ac_number ,ac_holders_name ,branch ,ifsc_code,bank_id)
          VALUES (1,'$supplierName','$lastname','$supplierType','$address1','$address2','$supplierCity','$supplierState','$pincode' ,'$supplierPhone' ,'$altPhone' , '$emailid','$suppAccountno' ,'$suppAccname' ,'$suppBranch' ,'$suppIfsc' ,'$bankName')";
          if(mysqli_query($conn,$query)){
              echo "success";
              $last_id = mysqli_insert_id($conn);
              $query1 = " INSERT INTO `hk_person_balance` (`person_id`) VALUES ('$last_id')";

              if(mysqli_query($conn,$query1)){
                echo "success";
              }
              else{
                  echo "sorry".mysqli_error($conn);
              }
              $query2 = " INSERT INTO `hk_person_due` (`person_id`) VALUES ('$last_id')";

              if(mysqli_query($conn,$query2)){
                echo "success";
              }
              else{
                  echo "sorry".mysqli_error($conn);
              }

                $query3 = "CREATE TABLE hk_account_".$last_id." (
id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
date DATE NOT NULL,
particulars VARCHAR(255) NOT NULL,
cr FLOAT NOT NULL,
dr FLOAT NOT NULL,
balance FLOAT NOT NULL,
active TINYINT(1) NOT NULL DEFAULT 1
)";

 if(mysqli_query($conn,$query3)){
      echo "table created";
      $_SESSION['message']="Supplier has been added successfully";
    }
    else{
      $_SESSION['message']="Sorry!!!".mysqli_error($conn);
        echo "sorry while createing table".mysqli_error($conn);
    }



              header("Location: ../supplier_list.php");
          }
          else{
              echo "sorry".mysqli_error($conn);
          }


?>
