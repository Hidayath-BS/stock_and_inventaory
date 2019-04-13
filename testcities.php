<!DOCTYPE html>
<html>
<head>
    <title>PHP - How to make dependent dropdown list using jquery Ajax?</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
</head>
<body>


<div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">Select State and get bellow Related City</div>
      <div class="panel-body">
            <div class="form-group">
                <label for="title">Select State:</label>
                <select name="state" class="form-control">
                    <option value="">--- Select State ---</option>



                      <?php
                       require('dbconnect.php');
                       $u_state_q = " SELECT * FROM `hk_states`";
                       $exe = mysqli_query($conn,$u_state_q);
                       while($row = mysqli_fetch_array($exe)){
                       ?>
                       <option value="<?php echo $row['id']; ?>"><?php echo $row['state_name']; ?></option>
                       <?php
                       }
                       ?>

           </select>


                
            </div>


            <div class="form-group">
                <label for="title">Select City:</label>
                <select name="city" class="form-control" style="width:350px">
                </select>
            </div>
          
          <div class="form-group">
                <label for="title">Select City:</label>
                <input name="advances" id="liveData" class="form-control" style="width:350px">
                
            </div>

            live data:
<!--            <span id='liveData'></span>-->
      </div>
    </div>
</div>


<script>
$( "select[name='state']" ).change(function () {
    var stateID = $(this).val();


    if(stateID) {


        $.ajax({
            url: "ajaxpro.php",
            dataType: 'Json',
            data: {'id':stateID},
            success: function(data) {
              console.log(data);
                $('select[name="city"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });


    }else{
        $('select[name="city"]').empty();
    }
});

//$(document).ready(function(){
function recursiveCall(){
$.ajax({
    url: "ajaxpro0.php",
    dataType: 'Json',
    success: function(data) {
      $('#liveData').val(data.val);
//      html(data.val);
    }
});
}
setInterval(function(){
  recursiveCall();
}, 2000)
//}
// Call it recursively on every 5 seconds to get live data. I have created
// a table a_live_data_test please do drop this anfter analyzing thead
// logic... :)
  recursiveCall();

// recursiveCall();
/*var myVar = window.setInterval(function(){
  recursiveCall();
}, 5000);*/
</script>


</body>
</html>
