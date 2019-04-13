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
    <link href="css/product.css" rel="stylesheet">
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
            <!-- <a>Products List</a> -->
           <h6>Product List</h6>
             <button class="proadd" onclick="myFunction()"><i class="fa fa-refresh" style="color: white;"></i></button>
            <button class="proaddbutton"><a href="add_product.php" style="color: white;"> <i class="fa fa-plus"> Add Product</i></a></button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr class="custtd">
                  <th>Sl No</th>
<!--                  <th>Product Id</th>-->
                  <th>Product Name</th>
                    <!-- <th>Product Type</th> -->
                    <th>Edit</th>
                    <th>Delete</th>
                 </tr>
              </thead>
<!--
              <tfoot>
                <th>Customer Code</th>
                  <th>Customer Type</th>

              </tfoot>
-->
              <tbody>

                   <?php
                                require('dbconnect.php');
                                $productlistq ="SELECT * FROM `hk_products`
                                WHERE products_active = 1 ";
                                $exe = mysqli_query($conn,$productlistq);
                                $x=0;
                                while($row = mysqli_fetch_array($exe)){



                                ?>

                   <tr class="custtd" >
                                    <td ><?php echo ++$x ?></td>
<!--                                    <td scope="row"><?php echo $row['id']; ?></td>-->
                                    <td><?php echo $row['name']." ".$row['type']." ".$row['quantity_type']; ?></td>
                                    <!-- <td><?php echo $row['type']; ?></td> -->

                                    <td class="custtd">
                                        <form method="post" action="product_module/product_edit.php">
                                           <p data-placement="top" data-toggle="tooltip" title="Edit">
                                               <button type="submit" name="edit" class="btn btn-primary btn-sm member" value="<?php echo $row['id']; ?>"><span class="fa fa-pencil"></span>
                                               </button></p>




                                        </form>
                                    </td>
                                    <td class="custtd">


                                        <p data-placement="top" data-toggle="tooltip" title="Delete">    <button data-target="#deleteModal" data-toggle="modal" class="btn btn-danger btn-sm modalbutton staff" onclick="deleteModalvalue(<?php echo $row["id"];?>,'<?php echo $row["name"]; ?>')"><span class="fa fa-trash"></span></button> </p>
                                    </td>

                                </tr>

                                <?php
                                }
                    if(isset($_POST['delete']))
                    {
                        $del = $_POST['delete'];
                        $delqery = " UPDATE `hk_products` SET `active`=0 WHERE id=$del";
                        if(mysqli_query($conn,$delqery)){
                            echo "<script>myFunction();</script>";
                        }
                        else{
                            echo "<script>alert('Sorry unable to delete');<script>";
                        }
                    }
                                ?>

              </tbody>
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
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div> -->


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
               <form method="post" action="product_module/product_delete_handler.php">
                        <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="" style="margin-bottom:-14px;">Delete</button>
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
       <script>

    function deleteModalvalue(deleteId, name){
         $('#deleteModalButton').val(deleteId);
          $('#deleteModalName').html("Hey!.. "+ name +" will get deleted soon..");
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
            $('.member').attr('disabled','disabled');
             $('.member').removeAttr('href');
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
