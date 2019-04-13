<?php
session_start();
require('../dbconnect.php');
$particulars = ucwords($_POST["particulars"]);
$amount = $_POST["cashamount"];
$date = $_POST["date"];
$cashBal = 0;

if(isset($_POST["income"])){
  $query = " INSERT INTO `hk_transaction_table` (`transaction_date`,`particulars`,`payment`) VALUES
   ('$date','$particulars','$amount')";

  if(mysqli_query($conn,$query)){
      echo "success";

      $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
      $cashBalExe = mysqli_query($conn,$cashBalQ);
      while($cashBalRow = mysqli_fetch_array($cashBalExe)){
        $cashBal = $cashBalRow["balance"];
      }

        if($cashBal == ""){
          $cashBal=0;
        }

        // $particulars = "Supplier Advance to $sname";
        $cr = 0;
        $dr = $amount;
        $balance = $cashBal + $dr;

        $Query = "INSERT INTO `hk_cash_book` (`particulars`, `date`, `cr`, `dr`, `balance`)
        VALUES ('$particulars','$date','$cr','$dr','$balance')";



        if(mysqli_query($conn,$Query)){
          echo "success";
          $_SESSION['message']="Cash Added successfully";
          header("Location: ../cashbook_list.php");

        }else{
          $_SESSION['message']="Sorry!!!".mysqli_error($conn);
          echo "sorry".mysqli_error($conn);
        }



  }
  else{
      echo "sorry".mysqli_error($conn);
  }
}
else if(isset($_POST["expense"])){
  $query = " INSERT INTO `hk_transaction_table` (`transaction_date`,`particulars`,`receipts`) VALUES
   ('$date','$particulars','$amount')";

  if(mysqli_query($conn,$query)){
      echo "success";
      $cashBalQ = "SELECT balance FROM hk_cash_book ORDER BY id DESC LIMIT 1";
      $cashBalExe = mysqli_query($conn,$cashBalQ);
      while($cashBalRow = mysqli_fetch_array($cashBalExe)){
        $cashBal = $cashBalRow["balance"];
      }

        if($cashBal == ""){
          $cashBal=0;
        }

        // $particulars = "Supplier Advance to $sname";
        $cr = $amount;
        $dr = 0;
        $balance = $cashBal - $cr;

        $Query = "INSERT INTO `hk_cash_book` (`particulars`, `date`, `cr`, `dr`, `balance`)
        VALUES ('$particulars','$date','$cr','$dr','$balance')";



        if(mysqli_query($conn,$Query)){
          echo "success";


          $query = "INSERT INTO `hk_expense_account` (`date`, `particulars`, `amount`)
                   VALUES ('$date','$particulars','$amount')";
                   if(mysqli_query($conn,$query)){
                    echo "Success advace account entry";
                      header("Location: ../cashbook_list.php");
                  }else{
                    echo "failure advance account entry";
                  }



        }else{
          $_SESSION['message']="Cash Added successfully";
          echo "sorry".mysqli_error($conn);
        }

  }
  else{
    $_SESSION['message']="Sorry!!!".mysqli_error($conn);
      echo "sorry".mysqli_error($conn);
  }
}




?>
