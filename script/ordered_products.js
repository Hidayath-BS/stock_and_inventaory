




function orderdproducts(object){
//    alert(object.value);
    var id = object.value;
    	var dataString = 'empid='+ id;    
		$.ajax({
			url: 'ajax_ordered_products.php',
			dataType: "json",
			data: dataString,  
			cache: false,
			success: function(employeeData) {
			   if(employeeData) {
                   
              
                   
                   
                   
                   console.log(employeeData);
                   console.log(employeeData[0]);
                   
                    row =1;
                   
                   
                   
                   
                        for(i = 0;i<=employeeData.length;i++){
                             $("#row_"+row+1).text(employeeData[i].name+" "+employeeData[i].type);
                        $("#row_"+row+2).text(employeeData[i].quantity+" "+employeeData[i].quantity_type);
                            
                                $("#prorow_"+row+1).text(employeeData[i].name+" "+employeeData[i].type);
                        $("#prorow_"+row+2).text(employeeData[i].quantity+" "+employeeData[i].quantity_type);
                            
                         $("#drow_"+row+1).text(employeeData[i].name+" "+employeeData[i].type);
                        $("#drow_"+row+2).text(employeeData[i].quantity+" "+employeeData[i].quantity_type);
                            
                      
                            row++;
                        }
                       
                        
                    
                   
				} else {

				}   	
			} 
		});
        
    
    
}

