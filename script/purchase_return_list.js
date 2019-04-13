


function f1(object){
//    alert(object.value);
     var id = object.value;
    		var dataString = 'empid='+ id;
		$.ajax({
			url: 'ajax_purchase_returned_products.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(employeeData) {
			   if(employeeData) {






                   console.log(employeeData);
                   console.log(employeeData[0]);

                    row =1;



$("#productModaldata").html("");
                        for(i = 0;i<=employeeData.length;i++){

                          $("#productModaldata").append(`

                    <tr class="custtd">
                        <td >`+employeeData[i].name+` `+employeeData[i].type+`</td>
                        <td >`+employeeData[i].quantity+` `+employeeData[i].quantity_type+`</td>
                        <td >`+employeeData[i].rate+`</td>
                        <td >`+employeeData[i].amount+`</td>


                   </tr>

                            `);





                        }




				} else {
			}
			}
		});



}
function datepick(){
	var date = $("#date").val();

	var dataString = 'date='+date;

	$.ajax({

		url: 'purchase_return_module/ajax_data/purchase_return_list_data.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(purchase) {
				console.log(purchase);

				if(purchase){
					var sl_no = 1;

					$("#table_body").html("");

          var sl_no =1;
					for(i = 0; i<purchase.length;i++){
						// purchase[i] = JSON.parse(purchase[i]);
						// console.log(purchase[i]);




						var mydate = new Date(purchase[i].date);
						var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

   					var str =(curr_date+ "-" + curr_month + "-" + curr_year );

						$("#table_body").append(`

              <tr class="custtd">
                <td scope="row">`+sl_no+`</td>
                <td>`+str+`</td>
                <td>`+purchase[i].purchase_return_bill_number+` </td>
                <td>`+purchase[i].first_name+` `+purchase[i].last_name+`</td>
                <td>`+purchase[i].bill_number+`</td>



                <td><p data-placement="top" data-toggle="tooltip" title="Product">
                      <button data-target="#productModal" data-toggle="modal" onclick="f1(this)" class="btn btn-sm btn-primary" value="`+purchase[i].id+`"><span class="fa fa-product-hunt"></span></button>
                  </td>

                <td>`+purchase[i].amount_recieved+`</td>
                     <td>`+purchase[i].purchase_transaction_type+`</td>


                     <td>
                         <p data-placement="top" data-toggle="tooltip" title="Bank"> <button data-target="#myModal" data-toggle="modal" class="btn btn-sm btn-primary" onclick="bankModalValue('`+purchase[i].cheque_number+`','`+purchase[i].transaction_id+`')"><span class="fa fa-university"></span></button>
                       </td>
                       <td>
                           <form method="post" action="purchase_return_bill.php">
                               <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="`+purchase[i].id+`"><span class="fa fa-print"></span>
                             </button></p>
                           </form>

                       </td>

              </tr>

							`);

sl_no++;
					}


				}




			}
	});
}
