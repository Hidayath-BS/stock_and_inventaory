$(document).ready(function(){  
	// code to get all records from table via select box
	$("#employee").change(function() {    
		var id = $(this).find(":selected").val();
		var dataString = 'empid='+ id;    
		$.ajax({
			url: 'ajax_advance.php',
			dataType: "json",
			data: dataString,  
			cache: false,
			success: function(employeeData) {
			   if(employeeData) {
//					$("#heading").show();		  
//					$("#no_records").hide();					
//					$("#emp_name").text(employeeData.employee_name);
//					$("#emp_age").text(employeeData.employee_age);
//					$("#emp_salary").text(employeeData.employee_salary);
//					$("#records").show();
                   $("#advance").val(employeeData.total);
				} else {
//					$("#heading").hide();
//					$("#records").hide();
//					$("#no_records").show();
				}   	
			} 
		});
        
        $.ajax({
            url:'balance_ajax_purchaseentry.php',
            dataType: "json",
            data: dataString,
            cache:false,
            success: function(balanceData){
                if(balanceData){
                 $("#balpayble").val(balanceData.balance_amount);   
                }
                    
            }
        });
        
 	}) 
});
