$(document).ready(function(){  
	// code to get all records from table via select box
	$("select[name='order']").blur(function() {    
		var id = $(this).find(":selected").val();
		var dataString = 'empid='+ id;    
		$.ajax({
			url: 'sales_entry_ajax_php/ajax_sales_2.php',
			dataType: "json",
			data: dataString,  
			cache: false,
			success: function(employeeData) {

			   if(employeeData) {
			   		$("#quantity").val(employeeData.quantity);

                   $("#product_type").val(employeeData.type);
				}    	
			} 
		});
        
        // $.ajax({
        //     url:'balance_ajax_purchaseentry.php',
        //     dataType: "json",
        //     data: dataString,
        //     cache:false,
        //     success: function(balanceData){
        //         if(balanceData){
        //          $("#balpayble").val(balanceData.balance_amount)   
        //         }
                    
        //     }
        // });
        
 	}) 
});
