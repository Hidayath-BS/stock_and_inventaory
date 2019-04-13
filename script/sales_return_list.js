

function f1(object){
//    alert(object.value);
    var id = object.value;
    		var dataString = 'empid='+ id;
		$.ajax({
			url: 'ajax_sales_returned_products.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(employeeData) {
			   if(employeeData) {




                   console.log(employeeData);
                   console.log(employeeData[0]);

                    row =1;

                    $("#productDetails tbody").html("");


                        for(i = 0;i<=employeeData.length;i++){

                          $("#productDetails tbody").append(`
                            <tr class="custtd">
                                <td>`+employeeData[i].name+` `+employeeData[i].type+`</td>
                                <td>`+employeeData[i].quantity+`</td>
                                <td>`+employeeData[i].rate+`</td>
                                <td>`+employeeData[i].amount+`</td>


                           </tr>
                            `);
                        //      $("#row_"+row+1).text(employeeData[i].name+" "+employeeData[i].type);
                        // $("#row_"+row+2).text(employeeData[i].quantity);
                        // $("#row_"+row+3).text(employeeData[i].rate);
                        // $("#row_"+row+4).text(employeeData[i].amount);
                        //
                        //     row++;
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

		url: 'sales_return_module/ajax_data/sales_return_list_data.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(salesRetrun) {
				console.log(salesRetrun);

				if(salesRetrun){
					var sl_no = 1;

					$("#table_body").html("");

          var sl_no =1;
					for(i = 0; i<salesRetrun.length;i++){
						salesRetrun[i] = JSON.parse(salesRetrun[i]);
						console.log(salesRetrun[i]);




						var mydate = new Date(salesRetrun[i].date);
						var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

   					var str =(curr_date+ "-" + curr_month + "-" + curr_year );

						$("#table_body").append(`

              <tr class="custtd">
                <td scope="row">`+sl_no+`</td>
                <td>`+str+`</td>
                <td>`+salesRetrun[i].sales_return_bill_number+` </td>
                <td>`+salesRetrun[i].first_name+` `+salesRetrun[i].last_name+`</td>
                <td>`+salesRetrun[i].sales_bill_number+`</td>



                <td><p data-placement="top" data-toggle="tooltip" title="Product">
                      <button data-target="#productModal" data-toggle="modal" onclick="f1(this)" class="btn btn-sm btn-primary" value="`+salesRetrun[i].id+`"><span class="fa fa-product-hunt"></span></button>
                  </td>

                <td>`+salesRetrun[i].amount_paid+`</td>
                     <td>`+salesRetrun[i].transaction_type+`</td>


                     <td>
                         <p data-placement="top" data-toggle="tooltip" title="Bank"> <button data-target="#myModal" data-toggle="modal" class="btn btn-sm btn-primary" onclick="bankModalValue('`+salesRetrun[i].cheque_number+`','`+salesRetrun[i].transaction_id+`')"><span class="fa fa-university"></span></button>
                       </td>
                       <td>
                           <form method="post" action="sales_return_bill.php">
                               <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" class="btn btn-primary btn-sm member" value="`+salesRetrun[i].id+`"><span class="fa fa-print"></span>
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
