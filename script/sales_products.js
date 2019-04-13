



function f1(object){
//    alert(object.value);
    
    var id = object.value;
    
    	var dataString = 'empid='+ id;    
		$.ajax({
			url: 'ajax_sales_products.php',
			dataType: "json",
			data: dataString,  
			cache: false,
			success: function(products) {
			   if(products) {
                   
                
                   
                   
                   
                   
                   $("#table_body_modal").html("");
                   
                    row =1;
                   
                   
                   
                   
                        for(i = 0;i<=products.length;i++){

                            $("#table_body_modal").append(`

                    <tr>
                      <td>`+products[i].name+` `+products[i].type+`</td>
                      <td>`+products[i].quantity+` `+products[i].quantity_type+`</td>
                      <td>`+products[i].rate+`</td>
                      <td>`+products[i].amount+`</td>

                    </tr>


                                `);

                            
                        }
                       
                        
                    
                   
				} else {

				}   	
			} 
		});
    
    
    
    
    
}