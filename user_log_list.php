<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
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
  <link href="css/purchaseentrylist.css" rel="stylesheet">

  <style media="screen">
    .staffdisplay{
      display: none;
    }
  </style>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
   <?php

    require('header.php');

    ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
<!--
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Tables</li>
      </ol>
-->
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <h6>User Log List</h6>
            <button class="puradd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>

          </div>
        <div class="card-body">

          <div class="container">
            <form class="" action="purchase_account_print.php" method="post">


            <div class="row">

              <div class="col-md-3">
                <input type="date" id="date" name="ondate" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="datepick()" class="form-control">

              </div>

              <div class="col-md-3">
                <div class="form-group form-disp">

                  <input type="text" id="myInput" placeholder="Search" class="form-control">
                  <i class="fa fa-search search-icon"></i>
                </div>

              </div>

            </div>
</form>
          </div>

<hr>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="purchaseTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                  <th>Sl No</th>
                  <th>User Name </th>
                  <th>Login Time</th>
                  <th>LogOut Time</th>

                 </tr>
              </thead>



              <tbody id="table_body">
<?php

                  require('dbconnect.php');
                    $slNo=1;
                    $currdate = date('Y-m-d');
    $userLogQuery = "SELECT * FROM `hk_user_log` where login_time BETWEEN '".$currdate." 00:00:00' AND '".date('Y-m-d',strtotime($currdate.' + 1 day'))." 00:00:00'";
              $userExe = mysqli_query($conn,$userLogQuery);
              while($userRow = mysqli_fetch_array($userExe)){
                // $date = strtotime($purchaseRow["bill_date"]);
                // $date = date("d-m-Y",$date);
    ?>
                <tr style="font-size: 14px;">
                  <td><?php echo $slNo;  ?></td>
                  <td><?php echo $userRow["user_name"];  ?></td>

                  <td><?php echo $userRow["login_time"];?></td>
                  <td><?php echo $userRow["logout_time"]; ?></td>


                </tr>

    <?php
                  $slNo++;
                  }
                  ?>
                  <!--
                <tr>
                  <td>Garrett Winters</td>
                  <td>Accountant</td>
                  <td>Tokyo</td>
                  <td>63</td>
                  <td>2011/07/25</td>
                  <td>61</td>
                  <td>2011/04/25</td>

                </tr>
-->



              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
<!--
               <div id="myModal" class="smodal">

  <div class="smodal-content">
    <div class="smodal-header">
      <span class="sclose">&times;</span>
      <h2>Quantity Details</h2>
    </div>
    <div class="smodal-body">
      <div class="table-responsive">
            <table class="table table-bordered suppliertr" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr >
                  <td class="suppliertd">Supplier Name :</td>
                    <td></td>
                  </tr>
                  <tr>
                  <td class="suppliertd">Empty Weight :</td>
                    <td></td>
                 </tr>
                  <tr>
                      <td class="suppliertd">Loaded Weight :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Net Weight :</td>
                      <td></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">Shrink :</td>
                      <td></td>
                  </tr>
                   <tr>
                      <td class="suppliertd">Final Quantity :</td>
                      <td></td>
                  </tr>
                   <tr>
                      <td class="suppliertd">Unit Price :</td>
                      <td></td>
                  </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
    </div>

  </div>
</div>
-->

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







      <script>
        function  transmodal(payable,paid,cheque,transid,paidTo){
            $("#payable").text(payable);
            $("#paid").text(paid);
            $("#cheque").text(cheque);
            $("#transid").text(transid);
            $("#paidto").text(paidTo);
        }

      </script>













      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Do you want to delete?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="deleteModalName">Please confirm..</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="flushValues()">Cancel</button>
<!--            <a class="btn btn-primary" href="login.html">Logout</a>-->
               <form method="post" action="purchase_entry_module/purchase_entry_delete_handler1.php">
                        <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="">DELETE</button>
                </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <!-- Scripts for modal-->
    <script src="js/supplier_list.js"></script>

<!--   script for ajax purchased products   -->
<script type="text/javascript" src="script/userLog.js"></script>


      <script>
      function bankModalValue(empty,loaded,net,shrink,final,unitprice) {
//          alert(""+name+" "+accname+" "+accnum+" "+branch+" "+ifsc);
          $("#name").html(empty+" TONNE");
          $("#acname").html(loaded+" TONNE");
          $("#accnum").html(net+" Kg");
          $("#branch").html(shrink+" Kg");
          $("#ifsc").html(final+" Kg");
          $("#unitprice").html("Rs "+unitprice);
      }
       function deleteModalvalue(deleteId, name){
         $('#deleteModalButton').val(deleteId);
          $('#deleteModalName').html("Hey!.. "+ name +" will get deleted soon..");
    }

    function flushValues(){
            $('#deleteModalButton').val("");
     }
      </script>
      <script>
  function myFunction() {
      location.reload();
  }</script>

      <script>
          function refreshtable(){
                    for(j = 1;j<5;j++){
                     $("#row_"+j+1).text("");
                     $("#row_"+j+2).text("");
                     $("#row_"+j+3).text("");
                     $("#row_"+j+4).text("");
                     $("#row_"+j+5).text("");
                     $("#row_"+j+6).text("");
                     $("#row_"+j+7).text("");
                   }
          }

      </script>



  <?php
        if($_SESSION['role']=='STAFF'){
            echo "<script> function staff(){
              $('.staff').attr('disabled','disabled');
              $('.member').attr('disabled','disabled');
               $('.member').removeAttr('href');
               $('.staff').removeAttr('href');
               $('.staffdisplay').css('display','none');
            }
            staff();
          </script>";
        }elseif($_SESSION['role']=='MEMBER'){
            echo "<script>
                      function member(){
                      $('.staff').attr('disabled','disabled');
                      $('.staffdisplay').css('display','none');
                      }
              member();
                  </script>";
        }


        ?>




<script type="text/javascript">

$(document).ready(function(){
$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#purchaseTable tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>

  </div>
</body>

</html>
<?php } ?>
