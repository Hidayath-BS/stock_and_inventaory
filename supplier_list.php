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
    <link href="css/supplier_list.css" rel="stylesheet">
</head>

<body class=" fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.php');
    require('dbconnect.php');
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
          <h6>Supplier List</h6>
            <button class="supadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="suppaddbutton"><a href="add_supplier.php" style="color: white;"> <i class="fa fa-plus"> Add Supplier</i></a></button>
          </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                  <th>Sl No</th>
<!--                  <th>Supplier Code :</th>-->
                  <th>Supplier Name </th>
                  <th>Supplier Type</th>
                  <th>Phone Numbers</th>
                  <th> Email Id </th>

                    <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                    <th>Bank Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                 </tr>
              </thead>


<!--
              <tfoot>
                <th>Customer Code</th>
                  <th>Customer Name</th>
                  <th>Customer Type</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Phone Number</th>
                  <th>Alternative No</th>
              </tfoot>
-->
              <tbody>
                  <?php
                  $query = "SELECT HKP.*,HKPT.person_role_type,HKCTY.city_name, HKSTS.state_name,HKBN.bank_name
                  FROM `hk_persons` AS HKP
                  LEFT JOIN `hk_person_role_type` AS HKPT ON HKP.person_role_type_id = HKPT.id
                  LEFT JOIN `hk_cities` AS HKCTY ON HKP.city_id = HKCTY.id
                  LEFT JOIN `hk_states` AS HKSTS ON HKP.state_id = HKSTS.id
                  LEFT JOIN `hk_bank_name` AS HKBN ON HKP.bank_id = HKBN.id
                  WHERE HKP.person_active=1 AND HKP.person_type_id = 1 ORDER BY `first_name`";
                  $exe = mysqli_query($conn,$query);
                  $x=0;
                  while($row = mysqli_fetch_array($exe)){



                  ?>
                <tr style="font-size: 14px;">
                  <td><?php echo ++$x; ?></td>
<!--                  <td><?php echo $row['id'];?></td>-->
                  <td><?php echo $row['first_name']." ".$row["last_name"]; ?></td>
                  <td><?php echo $row['person_role_type']; ?></td>
                  <td><?php echo $row['mobile_number']." ". $row['landline_number']; ?></td>
                  <td><?php echo $row['email']; ?></td>

                  <td><?php echo $row['address_line_1']." ".$row['address_line_2']; ?></td>
                  <td><?php echo $row['city_name']." ".$row['pincode']; ?></td>
                  <td><?php echo $row['state_name']; ?></td>

                  <td>
                      <p data-placement="top" data-toggle="tooltip" title="Bank">    <button style="width: 29px;padding-left: 6px;" data-target="#myModal" data-toggle="modal" class="btn btn-primary btn-sm" onclick="bankModalValue('<?php echo $row['first_name']; ?>','<?php echo $row['ac_holders_name']; ?>','<?php echo $row['bank_ac_number']; ?>','<?php echo $row['bank_name']; ?>','<?php echo $row['branch']; ?>','<?php echo $row['ifsc_code']; ?>')"><span class="fa fa-university"></span></button></p>
                    </td>
                <td>
                    <form method="post" action="supplier_module/supplier_edit.php">
                         <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm" value="<?php echo $row['id']; ?>"><span class="fa fa-pencil"></span>
                          </button></p>

                    </form>
                </td>
                <td>

                    <p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm staff"   onclick="deleteModalvalue(<?php echo $row["id"];?>,'<?php echo $row["first_name"]; ?>')" name="delete"  data-toggle="modal" data-target="#deleteModal" ><span class="fa fa-trash"></span></button></p>
                </td>
                    <?php
                    }
                    ?>

                </tr>



              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">BANK ACOUNT DETAILS</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times</span>
          </button>
        </div>
        <div class="modal-body">
           <div class="table-responsive">
          <table class="table table-bordered suppliertr" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr >
                <td class="suppliertd">Supplier Name :</td>
                  <td id="name"></td>
                </tr>
                <tr>
                <td class="suppliertd">A/C Holders Name :</td>
                  <td id="acname"></td>
               </tr>
                <tr>
                    <td class="suppliertd">Bank A/C No :</td>
                    <td id="accnum"></td>
                </tr>
                <tr>
                    <td class="suppliertd">Bank Name :</td>
                    <td id="bankname"></td>
                </tr>
                <tr>
                    <td class="suppliertd">Branch Name :</td>
                    <td id="branch"></td>
                </tr>
                <tr>
                    <td class="suppliertd">IFSC :</td>
                    <td id="ifsc"></td>
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
<!--
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
-->


<!-- delete modal   -->

      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Do you want to delete?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times</span>
            </button>
          </div>
          <div class="modal-body" id="deleteModalName">Please confirm..</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="flushValues()">Cancel</button>
<!--            <a class="btn btn-primary" href="login.html">Logout</a>-->
               <form method="post" action="supplier_module/supplier_delete_handler.php">
                        <button class="btn btn-default" type="submit" name="delete" style="margin-bottom: -14px" id="deleteModalButton" value="">Delete</button>
                                        </form>
          </div>
        </div>
      </div>
    </div>
<!-- delete modal   -->


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
<!--    <script src="js/supplier_list.js"></script>-->

<script>
function bankModalValue(name,accname,accnum,bankname,branch,ifsc) {
//          alert(""+name+" "+accname+" "+accnum+" +branch+" "+ifsc);
    $("#name").html(name);
    $("#acname").html(accname);
    $("#accnum").html(accnum);
    $("#bankname").html(bankname);
    $("#branch").html(branch);
    $("#ifsc").html(ifsc);
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
}
</script>

<?php
if($_SESSION['role']=='STAFF'){
    echo "<script> function staff(){
      $('.staff').attr('disabled','disabled');
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


  </div>


  <?php
  if(isset($_SESSION['message'])){
  $msg = $_SESSION['message'];
  ?>

  <script type="text/javascript">
  alert("<?php  echo $msg; ?>");
  </script>

  <?php
  unset($_SESSION['message']);
  }

   ?>

</body>

</html>
<?php } ?>
