

$(document).ready(function(){ 

  $("#address").change(function(){
      	var id = $(this).find(":selected").val();
		var dataString = 'empid='+ id;    
  $.ajax({
            url:'php_form_handler/ajax_getQuantity.php',
            dataType: "json",
            data: dataString,
            cache:false,
            success: function(balanceData){
                if(balanceData){
                    $("#unit").val(balanceData.quantity_type);
                    
                }
            }
        });
    })



});