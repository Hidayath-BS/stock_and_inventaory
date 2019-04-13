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
    <link href="css/user.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php require('header.php');
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
          <h6>User List</h6>
            <button class="useradd" onclick="myFunction()"><i class="fa fa-refresh"></i></button>
           <button class="useraddbutton staffdisplay"><a href="add_user.php" class="staff" style="color: white;"> <i class="fa fa-plus"> Add User</i></a></button>
          </div>
        <div class="card-body">
          <div class="table-responsive">

            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">

              <thead>

                <tr class="custtd heading">
                  <th>Sl No</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Email Id</th>
                  <th>Role</th>
                  <th>Mobile Number</th>
                  <th class="custtd staffdisplay">Edit</th>
                  <th class="staffdisplay">Delete</th>
                 </tr>
              </thead>

              <tbody>
                 <?php
                 require('dbconnect.php');
                 $custlistq ="SELECT HKU.*,HKR.role FROM `hk_users` AS HKU LEFT JOIN `hk_roles` AS HKR ON HKU.role_id = HKR.id  WHERE HKU.users_active=1;";
                 $exe = mysqli_query($conn,$custlistq);
                 $i=1;
                 while($row = mysqli_fetch_array($exe))
                  {
              ?>
                <tr class="custtd data_row" >
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                  <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['role']; ?></td>
                  <td><?php echo $row['mobile_number']; ?></td>
                  <td class="staffdisplay">
                    <form method="post" action="users_module/user_edit.php">
                        <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm staff" value="<?php echo $row['id']; ?>"><span class="fa fa-pencil"></span>
                          </button></p>
                                        </form>
                                    </td>
                                    <td class="staffdisplay">

                                        <form method="post" action="users_module/user_delete_handler.php">
                           <p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm staff"   onclick="return test();" name="delete" value="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#deleteModal" ><span class="fa fa-trash" ></span></button></p>

                                        </form>
                                    </td>
                </tr>
                 <?php
                }
                ?>
              </tbody>
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

            </table>

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
    <script>
                  function test()
                  {
                    var oky  =  confirm("Are you sure want to delete?");
                    if(oky==true)
                    {
                      alert("Record deleted!...");
                      return true;
                    }
                    else
                    {
                      return false;
                    }
                    return true;
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
      $('.staffdisplay').css('display','none');
    }
    staff();
  </script>";
}elseif($_SESSION['role']=='MEMBER'){
    echo "<script>
              function member(){
              $('.staff').attr('disabled','disabled');
              $('.staffdisplay').css('display','none');
              $('.data_row').css('height','22px');
              $('.heading').css('height','30px');
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
