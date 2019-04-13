function datepick(){
	var date = $("#date").val();

	var dataString = 'date='+date;

	$.ajax({
		
		  url: 'sales_entry_module/ajax_data/sales_entry_list_data.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(sales) {
				// console.log(question);

				if(sales){
					var sl_no = 1;

					$("#table_body").html("");

					for(i = 0; i<sales.length;i++){
						sales[i] = JSON.parse(sales[i]);
						console.log(sales[i]);

						var mydate = new Date(sales[i].bill_date);
						var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

						var str =(curr_date+ "-" + curr_month + "-" + curr_year );


            $("#table_body").append(`


                <tr class="custd" role="row">
                  <td>`+sl_no+`</td>
                  <td>`+sales[i].first_name+` `+sales[i].last_name+`</td>

                  <td>`+sales[i].bill_number+`</td>
                  <td>`+str+`</td>
                  <td>`+sales[i].sales_transaction_type+`</td>

                  <td>`+sales[i].driver_phone+`</td>
                    <td>

                      <p data-placement="top" data-toggle="tooltip" title="Bank">   <button data-target="#transactionModal" data-toggle="modal" class="btn btn-primary btn-sm member" onclick="transmodal('`+sales[i].total_amount+`','`+sales[i].total_amount_received+`','`+sales[i].cheque_number+`','`+sales[i].transaction_id+`')"><span class="fa fa-university"></span></button>
                    </td>


          <td>
                      <p data-placement="top" data-toggle="tooltip" title="Product">   <button value="`+sales[i].id+`" onclick="f1(this)" data-target="#myModal" data-toggle="modal" class="btn btn-primary btn-sm"> <span class="fa fa-product-hunt"></span></button></p>
                      <td>
                          <form method="post" action="new_sales_bill.php">
                              <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" name="print" formtarget="_blank" class="btn btn-primary btn-sm member" value="`+sales[i].id+`"><span class="fa fa-print"></span>
                            </button></p>
                          </form>

                      </td>

                      <td>
                        <form method="post" action="new_sales_bill_2.php">
                          <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" formtarget="_blank" name="print" class="btn btn-primary btn-sm member" value="`+sales[i].id+`"><span class="fa fa-print"></span>
                            </button></p>
                        </form>

                      </td>
											<td>
									<form method="post" action="sales_entry_module/sales_entry_edit.php">
											<p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="sale_id" class="btn btn-primary btn-sm member" value="`+sales[i].id+`"><span class="fa fa-pencil"></span>
										</button></p>
									</form>

							</td>


							<td class = "staffdisplay">
									<p data-placement="top" data-toggle="tooltip" title="Delete"><button
										class="btn btn-danger btn-sm staff"  name="delete" data-toggle="modal" data-target="#deleteModal" onclick="deleteModalvalue(`+sales[i].id+`,'`+sales[i].first_name+`')"><span class="fa fa-trash" ></span></button></p>
							</td>

                </tr>

              `);

							sl_no++;
					}


				}




			}
	});
}
