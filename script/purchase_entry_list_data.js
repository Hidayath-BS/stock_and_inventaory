function datepick(){
	var date = $("#date").val();

	var dataString = 'date='+date;

	$.ajax({

		url: 'purchase_entry_module/ajax_data/purchase_entry_list_data.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(purchase) {
				// console.log(question);

				if(purchase){
					var sl_no = 1;

					$("#table_body").html("");

					for(i = 0; i<purchase.length;i++){
						purchase[i] = JSON.parse(purchase[i]);
						console.log(purchase[i]);

						var mydate = new Date(purchase[i].bill_date);
						var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

   					var str =(curr_date+ "-" + curr_month + "-" + curr_year );

						$("#table_body").append(`

							<tr style="font-size: 14px;">
                  <td>`+sl_no+`</td>
                  <td>`+purchase[i].first_name+` `+purchase[i].last_name+`</td>

                  <td>`+purchase[i].bill_number+`</td>
                  <td>`+str+`</td>
                  <td>`+purchase[i].purchase_transaction_type+`</td>

                     <td>`+purchase[i].location+`</td>
                    <td>

                        <p data-placement="top" data-toggle="tooltip" title="Bank"> <button data-target="#transactionModal" data-toggle="modal" class="btn btn-primary btn-sm " onclick="transmodal('`+purchase[i].amount_payable+`','`+purchase[i].amount_paid+`','`+purchase[i].cheque_number+`','`+purchase[i].transaction_id+`','`+purchase[i].paid_to+`')"><span class="fa fa-university"></span></button></p>
                    </td>


				  <td>

                      <p data-placement="top" data-toggle="tooltip" title="Product">  <button value="`+purchase[i].id+`" onclick="f1(this)" data-target="#myModal" data-toggle="modal" class="btn btn-primary btn-sm" ><span class="fa fa-product-hunt"></span></button></p>


                    </td>
                    <td>
                        <form method="post" action="purchase_bill.php">
                            <p data-placement="top" data-toggle="tooltip" title="Print"> <button type="submit" formtarget="_blank" name="print" class="btn btn-primary btn-sm member" value="`+purchase[i].id+`"><span class="fa fa-print"></span>
                          </button></p>
                        </form>

                    </td>
                    <td class="staffdisplay">
                        <form method="post" action="purchase_entry_module/purchase_entry_edit.php">
                            <p data-placement="top" data-toggle="tooltip" title="Edit"> <button type="submit" name="edit" class="btn btn-primary btn-sm member" value="`+purchase[i].id+`"><span class="fa fa-pencil"></span>
                          </button></p>
                        </form>

                    </td>


                    <td class="staffdisplay">
                        <p data-placement="top" data-toggle="tooltip" title="Delete">    <button   class="btn btn-danger btn-sm staff"  name="delete" data-toggle="modal" data-target="#deleteModal" onclick="deleteModalvalue(`+purchase[i].id+`,'`+purchase[i].first_name+`')"><span class="fa fa-trash" ></span></button></p>
                    </td>
                </tr>

							`);

sl_no++;
					}


				}




			}
	});
}
