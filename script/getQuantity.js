$(document).ready(function(){  
	// code to get all records from table via select box
	$("#employee").change(function() {    
		var id = $(this).find(":selected").val();
		var dataString = 'empid='+ id;    
  $.ajax({
            url:'ajax_getQuantity.php',
            dataType: "json",
            data: dataString,
            cache:false,
            success: function(balanceData){
                if(balanceData){
                $("#quantityType").html(balanceData.quantity_type);  
                }
                    
            }
        });
        
   
        
 	})

});
