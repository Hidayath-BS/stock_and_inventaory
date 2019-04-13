
function f1(object){
//            alert(object.value);
    
    var id = object.value;
    
    var dataString = 'empid='+id;
//        alert(id);
        
		$.ajax({
			url: 'ajax_purchased_products.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(purchase) {
			   if(purchase) {




                $("#table_body_modal").html("");
                    
                   // console.log(employeeData);
                   // console.log(employeeData[0]);

                    row =1;


                   


                        for(i = 0;i<=purchase.length;i++){

                            $("#table_body_modal").append(`
                                   <tr class="custtd" role="row">
                                      <td>`+purchase[i].name+` `+purchase[i].type+`</td>
                                      <td>`+purchase[i].quantity+` `+purchase[i].quantity_type+`</td>
                                      <td>`+purchase[i].shrink+` `+ purchase[i].quantity_type+`</td>
                                      <td>`+purchase[i].final_quantity+` `+purchase[i].quantity_type+`</td>
                                      <td>`+purchase[i].rate+`</td>
                                      <td>`+purchase[i].amount+`</td>

                                     </tr>

                                `);

                        
                        }




				} else {
//					$("#heading").hide();
//					$("#records").hide();
//					$("#no_records").show();
				}
			}
		});
    
}