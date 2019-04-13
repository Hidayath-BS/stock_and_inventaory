<?php
session_start();
require("logout.php");

if($_SESSION['username']==""){
    header("Location: loginn.php");
}
else{
  require('dbconnect.php');
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
    <link href="css/cash.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    ?>
  <div class="content-wrapper ">
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
          <h6>Cash Book List</h6>

          <button class="cashadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="cashaddbutton"><a href="add_cash.php" class="staff" style="color: white;"> <i class="fa fa-plus"> Add Cash</i></a></button>

          </div>


        <div class="card-body">

          <div class="container">
<form  action="cash_book_print.php" method="post">
            <div class="row">



              <div class="col-md-3">
                <input type="date" id="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" onchange="data()" name="ondate" class="form-control">

              </div>

              <div class="col-md-3 offset-3">
                <button class="printcash" type="submit"><a>Print Today's Transaction </a></button>
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
            <table class="table table-bordered table-hover table-sm" id="cashTable"  width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl.No</th>
                  <th>Date</th>
                  <th>Particulars</th>
                  <th>Dr</th>
                  <th>Cr</th>
                  <th>Balance</th>

                 </tr>
              </thead>
              <tbody id="table_body">
                <?php


                             $openBalQ = "SELECT SUM(cr) as credit,SUM(dr) as debit from hk_cash_book WHERE date < '".date("Y-m-d")."' AND active=1";

                             $openBalExe = mysqli_query($conn,$openBalQ);

                             if(mysqli_num_rows($openBalExe)==0){
                                $openBal = 0;
                             }else{
                               while($openBalRow = mysqli_fetch_array($openBalExe)){
                                 $open_Cr = $openBalRow["credit"];
                                 $open_Dr = $openBalRow["debit"];
                               }

                               $openBal = $open_Dr-$open_Cr;
                             }





                             $orderlistq ="SELECT * FROM `hk_cash_book` WHERE date = '".date("Y-m-d")."' AND active = 1";
                             $exe = mysqli_query($conn,$orderlistq);
                                 $x=0;//To show Serial numbers irrespective of Order ID
                                 $cashBook = array();

                                 $cashBook[0]['cr'] = 0;
                                 $cashBook[0]['dr'] = 0;
                                 $cashBook[0]['bal'] = $openBal;


                             while($row = mysqli_fetch_array($exe)){
                               $y = $x+1;
                               $date = strtotime($row['date']);
                               $cashBook[$y]['date'] = date("d-m-Y",$date);
                               $cashBook[$y]['particulars'] = $row['particulars'];
                               $cashBook[$y]['cr'] = $row['cr'];
                               $cashBook[$y]['dr'] = $row['dr'];
                               $cashBook[$y]['bal'] = $cashBook[$x]['bal'] +($cashBook[$y]['dr']-$cashBook[$y]['cr']);
                             $x++;
                           }



                           for($i =1;$i< sizeof($cashBook); $i++){
                          ?>

                          <tr class="custtd">
                            <td><?php echo $i; ?></td>
                            <td><?php echo $cashBook[$i]['date']; ?></td>
                            <td><?php echo $cashBook[$i]['particulars']; ?></td>
                            <td><?php echo $cashBook[$i]['dr']; ?></td>
                            <td><?php echo $cashBook[$i]['cr']; ?></td>
                            <td><?php echo $cashBook[$i]['bal']; ?></td>
                          </tr>

                          <?php
                           }

                           ?>





            </tbody>
            </table>
          </div>

        </div>

<!--
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">DETAILS</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="table-responsive">
              <table class="table table-bordered suppliertr" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr >
                    <td class="suppliertd">Bill Date :</td>
                      <td id="name"></td>
                    </tr>
                  <tr >
                    <td class="suppliertd">Bill Number :</td>
                      <td id="name"></td>
                    </tr>
                  <tr >
                    <td class="suppliertd">Person Name :</td>
                      <td id="name"></td>
                    </tr>

                    <tr>
                        <td class="suppliertd">Type of Transaction :</td>
                        <td id="accnum"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Amount paid :</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Due</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Cheque No</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Transaction Id:</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Commission %</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Commission Amount</td>
                        <td id="bankname"></td>
                    </tr>
                    <tr>
                        <td class="suppliertd">Expenses</td>
                        <td id="bankname">
                          <table>
                            <tr>
                              <th>Name</th>
                              <th>Amounts</th>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                            </tr>

                          </table>


                        </td>
                    </tr>

                </thead>
                <tbody>

                </tbody>
              </table>
            </div>

              </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

            </div>
          </div>
        </div>
      </div>
-->

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
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <!-- Scripts for modal-->
    <script src="js/purchasereturn_list.js"></script>
    <script>
function myFunction() {
   location.reload();
}
</script>

<?php
      if($_SESSION['role']=='STAFF'){
          echo "<script> function staff(){
            $('.staff').attr('disabled','disabled');
            $('.member').attr('disabled','disabled');
             $('.member').removeAttr('href');
             $('.staff').removeAttr('href');
          }
          staff();
        </script>";
      }elseif($_SESSION['role']=='MEMBER'){
          echo "<script>
                    function member(){
                    $('.staff').attr('disabled','disabled');
                    }
            member();
                </script>";
      }


      ?>

<script type="text/javascript" src="script/cash_book_list.js">

</script>


<script type="text/javascript">

$(document).ready(function(){
$("#myInput").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $("#cashTable tbody tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

});
</script>

  </div>
</body>

</html>
<?php } ?>
