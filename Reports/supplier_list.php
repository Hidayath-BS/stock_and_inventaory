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

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?php
    require('header.html');
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
          <h6>Supplier Details</h6>
           <button class="addbutton"><a href="supplier_details.php" style="color: white;">Add New Supplier</a></button>
          </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr style="font-size: 14px;">
                  <th>Supplier Code :</th>
                  <th>Supplier Name </th>
                  <th>Supplier Type</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Phone Number</th>
                  <th>Alternative Number</th>
                    <th>Bank Details</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
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
                  $query = "SELECT hks.*, hkst.type FROM `hk_suppliers` AS hks left join `hk_suppliers_type` as hkst on hks.supplier_type_id=hkst.id WHERE active=1";
                  $exe = mysqli_query($conn,$query);
                  while($row = mysqli_fetch_array($exe)){
                      
                
                  
                  ?>
                <tr style="font-size: 14px;">
                  <td><?php echo $row['id'];?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['type']; ?></td>
                  <td><?php echo $row['address']; ?></td>
                  <td><?php echo $row['city']; ?></td>
                  <td><?php echo $row['state']; ?></td>
                  <td><?php echo $row['phone_number']; ?></td>
                  <td><?php echo $row['alternate_number']; ?></td>
                  <td>
                      <button data-target="#myModal" data-toggle="modal" class="modalbutton" onclick="bankModalValue('<?php echo $row['name']; ?>','<?php echo $row['ac_holders_name']; ?>','<?php echo $row['bank_ac_number']; ?>','<?php echo $row['branch']; ?>','<?php echo $row['ifsc_code']; ?>')">Bank Details</button>
                    </td>
                <td>
                    <form method="post" action="php_form_handler/supp_editor.php">
                                            <button type="submit" name="edit" class="btn btn-default" value="<?php echo $row['id']; ?>">
                                            EDIT
                                            </button>
                    </form>
                </td>
                <td>
                    <button data-target="#deleteModal" data-toggle="modal" class="modalbutton" onclick="deleteModalvalue(<?php echo $row["id"];?>,'<?php echo $row["name"]; ?>')">DELETE</button>    
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
              <span aria-hidden="true">×</span>
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
                      <td class="suppliertd">Branch Name :</td>
                      <td id="branch"></td>
                  </tr>
                  <tr>
                      <td class="suppliertd">IFSC</td>
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
      
      
<!-- delete modal   -->
      
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
               <form method="post" action="php_form_handler/supp_delete_handler.php">
                        <button class="btn btn-default" type="submit" name="delete" id="deleteModalButton" value="">DELETE</button>
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
      function bankModalValue(name,accname,accnum,branch,ifsc) {
//          alert(""+name+" "+accname+" "+accnum+" "+branch+" "+ifsc);
          $("#name").html(name);
          $("#acname").html(accname);
          $("#accnum").html(accnum);
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
  </div>
</body>

</html>
