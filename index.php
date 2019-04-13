<?php
session_start();
require("logout.php");
date_default_timezone_set("Asia/calcutta");
if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{


       error_reporting(E_ALL ^ E_NOTICE);
       setlocale(LC_MONETARY, 'en_IN');
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>HK</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">




<!--    date-->

        <link rel='stylesheet' type='text/css' href="css/stylesheet1.css">

<!--    date-->
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>
-->
<script>
function toggle() {
    var x = document.getElementById("balance");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>

    </head>



<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
   <?php
    require('header.php');
    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Current Transaction Summary</li>
          <form style="margin-left:47%;margin-bottom:0px" action="Admin_message/send_message.php" method="get">
            <button class="btn btn-default" name="sendmessage" style="float:right;border:2px solid #428bcb">SEND UPDATES TO ADMIN</button>
          </form>

      </ol>

<!--        First row-->




          <div class="container">
              <div class="row">
                  <div class="col-md-10">
    <div class="spanel spanel-primary panel-success stock_scroll">
      <div class="spanel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3 class="panel-title ph"> <span class="fa fa-list-alt"> </span> Stock </h3>
          </div>

        </div>
      </div>

      <div class="spanel-body">
        <div class="container thirdcont">
            <?php
            require('dbconnect.php');
            $stockArray = array();
            $rows = 0;
            $stockTableQuery = "SELECT hkst.*,hkp.name,hkp.type,hkp.quantity_type
                                from hk_stocks AS hkst
                                left join hk_products AS hkp on hkst.product_id = hkp.id";
            $stockExe = mysqli_query($conn,$stockTableQuery);
            while($stockRow = mysqli_fetch_array($stockExe)){

                $stockArray[$rows] = $stockRow;


            ?>
            <div>
    <a href="#" class="card">
        <i class="card__circle"></i>
        <i class="card__icon fa fa-info"></i>
        <p><?php echo $stockArray[$rows]['name']." ".$stockArray[$rows]['type']; ?></p>
        <div id="talkbubble"><span class="scount"><?php echo $stockArray[$rows]['quantity']; ?></span><small> <?php echo $stockArray[$rows]['quantity_type']; ?></small></div>
    </a>
            </div>

            <?php


            $rows++;
            }
            ?>


                </div>
      </div>
</div>

    </div>
           <div class="col-md-2">
    <div class="spanel spanel-primary panel-success">
      <div class="spanel-heading">
        <div class="row">

            <h3 class="panel-title"> <span class="fa fa-list-alt"> </span> Cash </h3>


        </div>
      </div>

      <div class="spanel-body">

<!--                <h3>Stock</h3>-->
        <div class="container da">
      <div class="circle-tile">
                            <a href="#">
                                <div class="circle-tile-heading green">
                                    <i class="fa fa-rupee fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile-content green">
                                <div class="circle-tile-description text-faded">
                                    Revenue
                                </div>
                                <?php
                                $cashQuery ="SELECT SUM(cr) as credit,SUM(dr) as debit from hk_cash_book WHERE active = '1'";
                                $cashExe = mysqli_query($conn,$cashQuery);
                                while($cashRow = mysqli_fetch_array($cashExe)){
                                $cashReady = $cashRow["debit"] - $cashRow["credit"];
                                }
                                ?>
                                <div class="circle-tile-number text-faded staff">
                                    <?php echo $cashReady; ?>
                                </div>
                                <a href="#" class="circle-tile-footer"> <i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>

                </div>


      </div>
</div>
                     </div>
                  </div>
  </div>


<div class="container" style="margin-top: 20px;margin-bottom: 20px;">
<button class="btn btn-default" onclick="toggle()">Show Balance</button>
  <div class="row" id="balance" style="display: none">
    <div class="col-md-12">
      <div class="spanel spanel-primary panel-success stock_scroll">
      <div class="spanel-heading">
        <div class="row">
	
          <div class="col-md-6" >
            <h3 class="panel-title ph"> <span class="fa fa-list-alt"> </span> Customer Limit Exceeded Accounts </h3>
          </div>

        </div>
      </div>

      <div class="spanel-body"  >
        <div class="container thirdcont">
            
          <table class="table">
            <thead>
              <tr>
                <th>Sl No.</th>
                <th style="text-align: left;">Name</th>
                <th style="text-align: right;">Limit amount <i class="fa fa-rupee"></i> </th>
                <th style="text-align: right;">Balance Amount (Receivable) <i class="fa fa-rupee"></i></th>
                <th style="text-align: right;"> Difrence Amount <i class="fa fa-rupee"></i></th>
              </tr>
            </thead>

            <?php
            
            function getBalList($a){
              
              require('dbconnect.php');

              $result = array();
              $j =0;
             for($i =0; $i <count($a); $i++){

              $query = "SELECT ABS(SUM(HKA.cr) -SUM(HKA.dr)) AS bal FROM hk_account_".$a[$i]["id"]." AS HKA  WHERE HKA.active= 1";
              // echo "$query <br>";

              $balExe = mysqli_query($conn,$query);

              $bal = mysqli_fetch_array($balExe);
              if(is_null($bal["bal"])){
                $bal["bal"] = 0;  
              }

              if($bal["bal"] > $a[$i]["acc_limit"]){

                $result[$j]["name"] = $a[$i]["cust_name"];
                $result[$j]["acc_limit"] = $a[$i]["acc_limit"];
                $result[$j]["balance"] = $bal["bal"];
                $result[$j]["diffrence"] = $bal["bal"] - $a[$i]["acc_limit"];

                $j++;
              }


             }

            return $result;
             // print_r($result);
            }


            // customer id and account_credit_limit for customers will  be fetched here 
            $CustomerQ = "SELECT id, acc_limit,first_name,last_name FROM hk_persons WHERE person_type_id=2";

            $CustomerExe = mysqli_query($conn,$CustomerQ);

            $i= 0;
            while ($Row = mysqli_fetch_array($CustomerExe)){
              # code...
              $CustomerRow[$i]["id"] = $Row["id"];
              $CustomerRow[$i]["acc_limit"] = $Row["acc_limit"]; 
              $CustomerRow[$i]["cust_name"] = $Row["first_name"]." ".$Row["last_name"]; 
              $i++;
            }

          $res = getBalList($CustomerRow);
          
             ?>

            <tbody>
              <?php
              $sl_no = 1;
              for($i = 0; $i< count($res); $i++){
                ?>

              <tr>
                <td><?php echo $sl_no; ?></td>
                <td style="text-align: left;" ><?php echo $res[$i]["name"]; ?></td>
                <td style="text-align: right;"><?php echo $res[$i]["acc_limit"]; ?></td>
                <td style="text-align: right;"><?php echo $res[$i]["balance"]; ?></td>
                <td style="text-align: right;">
                  <?php echo $res[$i]["diffrence"]; ?>
                </td>
              </tr>
              <?php
              $sl_no++;
              }
            ?>              
              
              
            </tbody>
          </table>

        </div>
      </div>
</div>

    </div>
  </div>

</div>


<div class="container" style="background:#e2e2e2;border:1px solid #cecaca;border-radius:5px;height:70px;margin-bottom:20px">
    <div class="row">
        <div class="col-md-6">
               <div>

      <div >
        <label><input type="radio" name="colorRadio" value="date"onclick="show1(); " checked >As On date</label>
        <label><input type="radio" name="colorRadio" value="on date" onclick="show2();"> Between dates</label>

    </div>

    <div id="div1">
        <label>Date:</label>

 <input type="date" name="bday" onchange="f1(this)"   class="fa fa-calendar g" style="text-align:center;"  max="<?php echo date('Y-m-d'); ?>">

    </div>
        <div id="div2" style="display: none">
            From:<input type="date"  name="bday" id="start" class="fa fa-calendar g" style="text-align:center;"  max="<?php echo date('Y-m-d'); ?>" >
            To: <input type="date" name="bday" id="end" onchange="f2(this)" class="fa fa-calendar g" style="text-align:center;" max="<?php echo date('Y-m-d'); ?>" >
        </div>



                        </div>

        </div>
    </div>
</div>

<!--        first row ends -->


         <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                    <h3 class="panel-title">
                        <span class="fa fa-bookmark"></span> Purchases</h3>

                </div>
                    </div>
                <div class="panel-body">
                    <div class="row ">
                        <div class="col-xs-6 col-md-6">
                          <a href="#" class="btn btn-danger btn-lg" role="button"><span class="fa fa-list-ol"></span> <br/>Total No of Purchases</a>

                          <a href="#" class="btn btn-primary btn-lg" role="button"><span class="fa fa-list-alt"></span> <br/>Total Purchase Cash</a>
                        </div>

                        <?php
                            $purchaseQuery = "select SUM(amount_paid) as sum,COUNT(id) as count from hk_purchases where DATE(bill_date)=CURDATE()";
                        $purchasExe = mysqli_query($conn,$purchaseQuery);
                        while($purchaseRow = mysqli_fetch_array($purchasExe)){
                            $purchsesum =  $purchaseRow["sum"];
                            $purchasecount = $purchaseRow["count"];
                        }

                        ?>
                        <div class="col-xs-6 col-md-6">
                          <a id="pcount"><span class="pscount" id="purchasecount"><?php echo $purchasecount; ?></span></a>
                          <a id="pcount1"><span class="pscount1" id="purchaseamount"><?php echo $purchsesum; ?></span></a>
                        </div>
                    </div>
                    <a href="#" class="btn btn-success btn-lg btn-block" role="button" data-toggle="modal" data-target="#myModal"><span class="fa fa-info-circle"></span> View Details</a>

                    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<!--          <h4 class="modal-title">Purchases</h4>-->
        </div>
        <div class="modal-body">

    <div class="sppanel sppanel-primary panel-success">
      <div class="sppanel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3 class="panel-title"> <span class="fa fa-list-alt"> </span> Purchases </h3>
          </div>
        </div>
      </div>

      <div class="sppanel-body">

        <div class="row firstcont">

            <?php



$purchaseModalQuery = "SELECT SUM(HKPP.final_quantity) as quantity,HKP.name,HKP.type,HKP.quantity_type
                        FROM `hk_purchases` as HKPU
                        left join hk_purchased_products as HKPP on HKPP.purchase_id =HKPU.id
                        left JOIN hk_products AS HKP on HKPP.product_id = HKP.id
                        where DATE(bill_date) = CURDATE() GROUP BY product_id";
            $purchaseModalExe = mysqli_query($conn,$purchaseModalQuery);
    $i = 0;
            while($purchaseModalRow = mysqli_fetch_array($purchaseModalExe)){

                $name[$i] = $purchaseModalRow["name"];
                $type[$i] = $purchaseModalRow["type"];
                $Qauntity[$i] = $purchaseModalRow["quantity"];
                $qunatity_type[$i] = $purchaseModalRow["quantity_type"];
$i++;
            ?>
<?php } ?>
<!--
  <div class="box effect1">
      <div class="row">
          <div class="col-md-8">
    <h3><?php echo $purchaseModalRow["name"]; ?></h3>
              </div>
      <div class="col-md-4 ">
                        <div class="circle-tile1">
                            <a href="#">
                                <div class="circle-tile1-heading red">
                                    <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile1-content red">
                                <div class="circle-tile1-description text-faded">
                                Quantity
                                </div>
                                <div class="circle-tile1-number text-faded">
                                    <?php echo $purchaseModalRow["quantity"]." ".$purchaseModalRow["quantity_type"]; ?>
                                    <span id="sparklineC"></span>
                                </div>
                            </div>
                        </div>
                    </div>
          </div>
  </div>
-->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm dataTable no-footer" id="purchaseTable">
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>

                    </tr>
                    <tr>
                        <td id="PU_11"><?php echo $name[0]." ".$type[0]; ?></td>
                        <td id="PU_12"><?php echo $Qauntity[0]." ".$qunatity_type[0]; ?></td>
                    </tr>
                    <tr>
                        <td id="PU_21"><?php echo $name[1]." ".$type[1]; ?></td>
                        <td id="PU_22"><?php echo $Qauntity[1]." ".$qunatity_type[1]; ?></td>
                    </tr>
                    <tr>
                        <td id="PU_31"><?php echo $name[2]." ".$type[2]; ?></td>
                        <td id="PU_32"><?php echo $Qauntity[2]." ".$qunatity_type[2]; ?></td>
                    </tr>
                    <tr>
                        <td id="PU_41"><?php echo $name[3]." ".$type[3]; ?></td>
                        <td id="PU_42"><?php echo $Qauntity[3]." ".$qunatity_type[3]; ?></td>
                    </tr>
                    <tr>
                        <td id="PU_51"><?php echo $name[4]." ".$type[4]; ?></td>
                        <td id="PU_52"><?php echo $Qauntity[4]." ".$qunatity_type[4]; ?></td>
                    </tr>
                    <tr>
                        <td id="PU_61"><?php echo $name[5]." ".$type[5]; ?></td>
                        <td id="PU_62"><?php echo $Qauntity[5]." ".$qunatity_type[5]; ?></td>
                    </tr>


                </table>
            </div>






        </div>

        </div>
    </div>


        </div>

      </div>

    </div>
  </div>



                </div>
            </div>
        </div>


         <div class="col-md-6">
            <div class="panel-primary pw" >
                <div class="panel-heading">
                     <div class="row">
                    <h3 class="panel-title">
                        <span class="fa fa-bookmark"></span> Purchase Return</h3>





                    </div></div>
                <div class="panel-body">
                    <div class="row ">
                        <div class="col-xs-6 col-md-6">
                          <a href="#" class="btn btn-danger btn-lg" role="button"><span class="fa fa-list-ol"></span> <br/>Total No of Purchase Return</a>
                          <a href="#" class="btn btn-warning btn-lg" role="button" style="background-color:#007bff;border-color:#007bff;color:white;"><span class="fa fa-list-alt"></span> <br/>Total Purchase Return Cash</a>

                        </div>
                        <div class="col-xs-6 col-md-6">
                            <?php
                            $purchaseReturnQ = "SELECT sum(return_amount) as returnamount,COUNT(id) as count from hk_purchases_return where DATE(date)=CURDATE()";
                            $purchaseReturnExe = mysqli_query($conn,$purchaseReturnQ);
                            while($purchaseReturnRow = mysqli_fetch_array($purchaseReturnExe)){
                                $purchaseReturnAmount = $purchaseReturnRow["returnamount"];
                                $purchaseReturnCount = $purchaseReturnRow["count"];
                            }
                            ?>


                         <div id="pcount2"><span class="pscount" id="purchasereturnCount"><?php echo $purchaseReturnCount; ?></span></div>
                          <div id="pcount3"><span class="pscount" id="purchasereturnAmount"><?php echo $purchaseReturnAmount; ?></span></div>
                        </div>
                    </div>
                     <a href="#" class="btn btn-success btn-lg btn-block" role="button" data-toggle="modal" data-target="#myModal1"><span class="fa fa-info-circle"></span> View Details</a>

                    <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<!--          <h4 class="modal-title">Purchases</h4>-->
        </div>
        <div class="modal-body">

    <div class="sppanel sppanel-primary panel-success">
      <div class="sppanel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3 class="panel-title"> <span class="fa fa-list-alt"> </span> Purchase Return </h3>
          </div>
        </div>
      </div>

      <div class="sppanel-body">

        <div class="row firstcont">


            <?php

            $purchaseReturnQ = "SELECT HKP.name,HKP.type,SUM(HKPP.quantity) as returnquantity,HKP.quantity_type
                                from hk_purchases_return as HKPUR
                                left join `hk_purchase_return_products` as HKPP on HKPP.purchase_return_id = HKPUR.id
                                left JOIN hk_products AS HKP on HKP.id = HKPP.product_id
                                WHERE DATE(HKPUR.date)= CURDATE() GROUP BY HKP.id";
            $purchaseReturnExe = mysqli_query($conn,$purchaseReturnQ);
    $j=0;
            while($purchaseReturnRow = mysqli_fetch_array($purchaseReturnExe)){

                $PRname[$j] = $purchaseReturnRow ["name"];
                $PRtype[$j] = $purchaseReturnRow ["type"];
                $PRQauntity[$j] = $purchaseReturnRow ["returnquantity"];
                $PRqunatity_type[$j] = $purchaseReturnRow ["quantity_type"];
$j++;

            ?>

<!--
  <div class="box effect1">
      <div class="row">
          <div class="col-md-8">
    <h3><?php echo $purchaseReturnRow["name"]." ".$purchaseReturnRow["type"]; ?></h3>
              </div>
      <div class="col-md-4 ">
                        <div class="circle-tile1">
                            <a href="#">
                                <div class="circle-tile1-heading red">
                                    <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile1-content red">
                                <div class="circle-tile1-description text-faded">
                                Quantity
                                </div>
                                <div class="circle-tile1-number text-faded">
                                    <?php echo $purchaseReturnRow["returnquantity"]." ".$purchaseReturnRow["quantity_type"]; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <a href="#" class="circle-tile1-footer"><i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
          </div>
  </div>
-->
            <?php } ?>
 <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm dataTable no-footer" id="purchaseReturnTable">
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>

                    </tr>
                    <tr>
                        <td id="PUR_11"><?php echo $PRname[0]." ".$PRtype[0]; ?></td>
                        <td id="PUR_12"><?php echo $PRQauntity[0]." ".$PRqunatity_type[0]; ?></td>
                    </tr>
                    <tr>
                        <td id="PUR_21"><?php echo $PRname[1]." ".$PRtype[1]; ?></td>
                        <td id="PUR_22"><?php echo $PRQauntity[1]." ".$PRqunatity_type[1]; ?></td>
                    </tr>
                    <tr>
                        <td id="PUR_31"><?php echo $PRname[2]." ".$PRtype[2]; ?></td>
                        <td id="PUR_32"><?php echo $PRQauntity[2]." ".$PRqunatity_type[2]; ?></td>
                    </tr>
                    <tr>
                        <td id="PUR_41"><?php echo $PRname[3]." ".$PRtype[3]; ?></td>
                        <td id="PUR_42"><?php echo $PRQauntity[3]." ".$PRqunatity_type[3]; ?></td>
                    </tr>
                    <tr>
                        <td id="PUR_51"><?php echo $PRname[4]." ".$PRtype[4]; ?></td>
                        <td id="PUR_52"><?php echo $PRQauntity[4]." ".$PRqunatity_type[4]; ?></td>
                    </tr>
                    <tr>
                        <td id="PUR_61"><?php echo $PRname[5]." ".$PRtype[5]; ?></td>
                        <td id="PUR_62"><?php echo $PRQauntity[5]." ".$PRqunatity_type[5]; ?></td>
                    </tr>


                </table>
            </div>


        </div>

        </div>
    </div>


        </div>

      </div>

    </div>
  </div>
                </div>
            </div>
        </div>
        </div>


        <div class="row">
         <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                     <div class="row">
                    <h3 class="panel-title">
                        <span class="fa fa-bookmark"></span> Sales </h3>



                    </div></div>
                <div class="panel-body">
                    <div class="row ">
                        <div class="col-xs-6 col-md-6">
                          <a href="#" class="btn btn-danger btn-lg" role="button" style="background-color: #39b3d7;border-color:  #39b3d7;"><span class="fa fa-list-ol"></span> <br/>Total No of Sales</a>
                          <a href="#" class="btn btn-primary btn-lg" role="button" style="color:white;background-color:#ffc107;
    border-color: #ffc107;"><span class="fa fa-list-alt"></span> <br/>Total Sales Cash</a>
                        </div>


                    <?php
                            $salesQuery = "select SUM(total_amount_received) as sum,COUNT(id) as count from hk_sales where DATE(bill_date)=CURDATE();";
                        $salesExe = mysqli_query($conn,$salesQuery);
                        while($salesRow = mysqli_fetch_array($salesExe)){
                            $salessum =  $salesRow["sum"];
                            $salescount = $salesRow["count"];
                        }

                        ?>
                       <div class="col-xs-6 col-md-6">
                          <div id="pcount" style="background-color:#39b3d7"><span class="pscount" id="salescount"><?php echo $salescount; ?></span></div>
                          <div id="pcount4" style="background-color: #ffc107;"><span class="pscount" id="salesamount"><?php echo $salessum; ?></span></div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-success btn-lg btn-block" role="button" data-toggle="modal" data-target="#myModal2"><span class="fa fa-info-circle"></span> View Details</a>
                     <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<!--          <h4 class="modal-title">Purchases</h4>-->
        </div>
        <div class="modal-body">
<div class="sppanel sppanel-primary panel-success">
      <div class="sppanel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3 class="panel-title"> <span class="fa fa-list-alt"> </span> Sales </h3>
          </div>
        </div>
      </div>

      <div class="sppanel-body">

        <div class="row firstcont">

            <?php
          $salesModalQ = "SELECT HKP.name,HKP.type,HKSP.quantity,HKP.quantity_type
                          from hk_sales as HKS
                          left join hk_sales_products as HKSP on HKSP.sales_id = HKS.id
                          left join hk_products AS HKP on HKP.id = HKSP.product_id
                          WHERE DATE(HKS.bill_date) = CURDATE()";

          $salesmodalExe = mysqli_query($conn,$salesModalQ);

    $k =0;
          while($salesModalRow = mysqli_fetch_array($salesmodalExe)){

                $salesName[$k] = $salesModalRow["name"];
                $salesType[$k] = $salesModalRow["type"];
                $salesQuantity[$k] = $salesModalRow["quantity"];
                $salesQtyType[$k] = $salesModalRow["quantity_type"];

          ?>
<!--
  <div class="box effect1">
      <div class="row">
          <div class="col-md-8">
    <h3><?php echo $salesModalRow["name"]." ".$salesModalRow["type"]; ?></h3>
              </div>
      <div class="col-md-4 ">
                        <div class="circle-tile1">
                            <a href="#">
                                <div class="circle-tile1-heading red">
                                    <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile1-content red">
                                <div class="circle-tile1-description text-faded">
                                Quantity
                                </div>
                                <div class="circle-tile1-number text-faded">
                                    <?php echo $salesModalRow["quantity"]." ".$salesModalRow["quantity_type"]; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <a href="#" class="circle-tile1-footer"><i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
          </div>
  </div>
-->
            <?php } ?>


            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm dataTable no-footer" id="salesTable">
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>

                    </tr>
                    <tr>
                        <td id="SA_11"><?php echo $salesName[0]." ".$salesType[0]; ?></td>
                        <td id="SA_12"><?php echo $salesQuantity[0]." ".$salesQtyType[0]; ?></td>
                    </tr>
                    <tr>
                        <td id="SA_21"><?php echo $salesName[1]." ".$salesType[1]; ?></td>
                        <td id="SA_22"><?php echo $salesQuantity[1]." ".$salesQtyType[1]; ?></td>
                    </tr>
                    <tr>
                        <td id="SA_31"><?php echo $salesName[2]." ".$salesType[2]; ?></td>
                        <td id="SA_32"><?php echo $salesQuantity[2]." ".$salesQtyType[2]; ?></td>
                    </tr>
                    <tr>
                        <td id="SA_41"><?php echo $salesName[3]." ".$salesType[3]; ?></td>
                        <td id="SA_42"><?php echo $salesQuantity[3]." ".$salesQtyType[3]; ?></td>
                    </tr>
                    <tr>
                        <td id="SA_51"><?php echo $salesName[4]." ".$salesType[4]; ?></td>
                        <td id="SA_52"><?php echo $salesQuantity[4]." ".$salesQtyType[4]; ?></td>
                    </tr>
                    <tr>
                        <td id="SA_61"><?php echo $salesName[5]." ".$salesType[5]; ?></td>
                        <td id="SA_62"><?php echo $salesQuantity[5]." ".$salesQtyType[5]; ?></td>
                    </tr>


                </table>
            </div>


        </div>

        </div>
    </div>


        </div>

      </div>

    </div>
  </div>
                </div>
            </div>
        </div>


         <div class="col-md-6">
            <div class="panel-primary pw">
                <div class="panel-heading">
                    <div class="row">
                    <h3 class="panel-title">
                        <span class="fa fa-bookmark"></span> Sales Return</h3>

                </div></div>
                <div class="panel-body">
                    <div class="row ">
                        <div class="col-xs-6 col-md-6">
                          <a href="#" class="btn btn-danger btn-lg" role="button" style="background-color: #39b3d7;border-color:  #39b3d7;"><span class="fa fa-list-ol"></span> <br/>Total No of Sales Return</a>
                          <a href="#" class="btn btn-warning btn-lg" role="button" style="color:white"><span class="fa fa-list-alt"></span> <br/>Total Sales Return Cash</a>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <?php
                                $salesReturnQuery ="SELECT sum(amount_paid) as returnamount,COUNT(id) as count from hk_sales_return where DATE(date)=CURDATE()";
                            $salesReturnExe = mysqli_query($conn,$salesReturnQuery);
                            while($salesReturnRow = mysqli_fetch_array($salesReturnExe)){
                                $salesReturnAmount = $salesReturnRow["returnamount"];
                                $salesReturnCount = $salesReturnRow["count"];
                            }
                            ?>
                         <div id="pcount2" style="background-color:#39b3d7"><span class="pscount" id="salesreturnCount">
                             <?php echo $salesReturnCount; ?></span></div>
                          <div id="pcount3" style="background-color: #ffc107;"><span class="pscount" id="salesreturnamount">
                            <?php echo $salesReturnAmount; ?>  </span></div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-success btn-lg btn-block" role="button" data-toggle="modal" data-target="#myModal3"><span class="fa fa-info-circle"></span> View Details</a>
                     <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<!--          <h4 class="modal-title">Purchases</h4>-->
        </div>
        <div class="modal-body">

    <div class="sppanel sppanel-primary panel-success">
      <div class="sppanel-heading">
        <div class="row">
          <div class="col-md-6">
            <h3 class="panel-title"> <span class="fa fa-list-alt"> </span> Sales Return </h3>
          </div>
        </div>
      </div>

      <div class="sppanel-body">

        <div class="row firstcont">


            <?php
            $salesReturnQuery = "SELECT HKP.name,HKP.type,HKSRP.quantity,HKP.quantity_type
                                FROM hk_sales_return AS HKSR
                                LEFT JOIN hk_sales_return_products AS HKSRP on HKSRP.sales_return_id = HKSR.id
                                LEFT JOIN hk_products as HKP on HKSRP.product_id = HKP.id
                                WHERE DATE(HKSR.date)=CURDATE()";
            $salesReturnExe = mysqli_query($conn,$salesReturnQuery);
            $l = 0;
            while($salesReturnRow = mysqli_fetch_array($salesReturnExe)){

                $salesRetName[$l] = $salesReturnRow["name"];
                $salesRetType[$l] = $salesReturnRow["type"];
                $salesRetQuantity[$l] = $salesReturnRow["quantity"];
                $salesRetQtyTpe[$l] = $salesReturnRow["quantity_type"];

                $l++;
            ?>
<!--
  <div class="box effect1">
      <div class="row">
          <div class="col-md-8">
    <h3><?php
        echo $salesReturnRow["name"]." ".$salesReturnRow["type"];
        ?></h3>
              </div>
      <div class="col-md-4 ">
                        <div class="circle-tile1">
                            <a href="#">
                                <div class="circle-tile1-heading red">
                                    <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                                </div>
                            </a>
                            <div class="circle-tile1-content red">
                                <div class="circle-tile1-description text-faded">
                                Quantity
                                </div>
                                <div class="circle-tile1-number text-faded">
                                    <?php echo $salesReturnRow["quantity"]." ".$salesReturnRow["quantity_type"]; ?>
                                    <span id="sparklineC"></span>
                                </div>
                                <a href="#" class="circle-tile1-footer"><i class="fa fa-chevron-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
          </div>
  </div>
-->
<?php
        }    ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm dataTable no-footer" id="salesReturnTable">
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>

                    </tr>
                    <tr>
                        <td id="SRP_11"><?php echo $salesRetName[0]." ".$salesRetType[0]; ?></td>
                        <td id="SRP_12"><?php echo $salesRetQuantity[0]." ".$salesRetQtyTpe[0]; ?></td>
                    </tr>
                    <tr>
                        <td id="SRP_21"><?php echo $salesRetName[1]." ".$salesRetType[1]; ?></td>
                        <td id="SRP_22"><?php echo $salesRetQuantity[1]." ".$salesRetQtyTpe[1]; ?></td>
                    </tr>
                    <tr>
                        <td id="SRP_31"><?php echo $salesRetName[2]." ".$salesRetType[2]; ?></td>
                        <td id="SRP_32"><?php echo $salesRetQuantity[2]." ".$salesRetQtyTpe[2]; ?></td>
                    </tr>
                    <tr>
                        <td id="SRP_41"><?php echo $salesRetName[3]." ".$salesRetType[3]; ?></td>
                        <td id="SRP_42"><?php echo $salesRetQuantity[3]." ".$salesRetQtyTpe[3]; ?></td>
                    </tr>
                    <tr>
                        <td id="SRP_51"><?php echo $salesRetName[4]." ".$salesRetType[4]; ?></td>
                        <td id="SRP_52"><?php echo $salesRetQuantity[4]." ".$salesRetQtyTpe[4]; ?></td>
                    </tr>
                    <tr>
                        <td id="SRP_61"><?php echo $salesRetName[5]." ".$salesRetType[5]; ?></td>
                        <td id="SRP_62"><?php echo $salesRetQuantity[5]." ".$salesRetQtyTpe[5]; ?></td>
                    </tr>


                </table>
            </div>







        </div>

        </div>
    </div>


        </div>

      </div>

    </div>
  </div>
    </div>
            </div>
             </div>
        </div>
  </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
        <small>MAHAT INNOVATIONS</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <script src="script/dashboard_purchase_details.js"></script>
      <script>
      $('.pscount').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});


      </script>
      <script>
      $('.pscount1').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
      </script>
      <script>
      $('.scount').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
      </script>
   <script>
  $(document).ready(function(){
      $(".form-control").popover({title: "Search Here", placement: "top"});
     })

</script>


    <script>
    function show1(){
  document.getElementById('div1').style.display ='block';
    document.getElementById('div2').style.display = 'none';
}
function show2(){
     document.getElementById('div1').style.display ='none';
  document.getElementById('div2').style.display = 'block';
}
    function show3(){
  document.getElementById('div3').style.display ='block';
    document.getElementById('div4').style.display = 'none';
}
function show4(){
     document.getElementById('div3').style.display ='none';
  document.getElementById('div4').style.display = 'block';
}
   function show5(){
  document.getElementById('div5').style.display ='block';
    document.getElementById('div6').style.display = 'none';
}
function show6(){
     document.getElementById('div5').style.display ='none';
  document.getElementById('div6').style.display = 'block';
}
 function show7(){
  document.getElementById('div7').style.display ='block';
    document.getElementById('div8').style.display = 'none';
}
function show8(){
     document.getElementById('div7').style.display ='none';
  document.getElementById('div8').style.display = 'block';
}



</script>


<?php
      if($_SESSION['role']=='STAFF'){
          echo "<script> function staff(){
            $('.staff').html('0000');
          }
          staff();
        </script>";
      }elseif($_SESSION['role']=='MEMBER'){
          echo "<script>
                    function member(){
                    $('.staff').html('1000')
                    }
            member();
                </script>";
      }


      ?>
    </div>
<script>


</script>


</body>

</html>
<?php } ?>
