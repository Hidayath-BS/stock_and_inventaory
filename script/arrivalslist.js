function datepick(){
	var date = $("#date").val();

	var dataString = 'date='+date;

    
	$.ajax({
            url: 'arrivals_module/ajax_data/arrivals_data_list.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
            cache: false,
            success: function(arrivals){
                console.log(arrivals);

                if(arrivals){
                    var sl_no = 1;

                    $("#table_body").html("");
                    
                    for(i = 0; i<arrivals.length;i++){
						arrivals[i] = JSON.parse(arrivals[i]);
						// console.log(cash[i]);
						var mydate = new Date(arrivals[i].date);
						var curr_date = mydate.getDate();
                        var curr_month = mydate.getMonth() + 1; //Months are zero based
                        var curr_year = mydate.getFullYear();

                        var bgclass = "";
                        var display = "";

                        if(arrivals[i].payment_status == 0){
                            bgclass = "red";
                            display = "display";
                        }else{
                            if(arrivals[i].payment_status == 1){
                                bgclass = "yellow";
                                display = "display";
                            }else{
                                bgclass = "green";
                                display = "displayNone";
                            }
                        }

   					    var str =(curr_date+ "-" + curr_month + "-" + curr_year );

						$("#table_body").append(`

                            <tr class="custtd `+bgclass+`">
                                <td scope="row">`+sl_no+`</td>
                                <td>`+str+`</td>
                                <td>`+arrivals[i].weigh_bill_number+`</td>
                                <td>`+arrivals[i].first_name+` `+arrivals[i].last_name+` </td>
                                <td>`+arrivals[i].rate+`</td>
                                <td>`+arrivals[i].quantity+`</td>
                                <td>`+arrivals[i].advance+`</td>
                                <td>
                                <form action="arrivalsDueClear.php" method="GET" class="`+display+`">
                                    <button class="btn btn-primary" name="arrival" value="`+arrivals[i].id+`" >PAY </button>
                                </form>
                                </td>
                            </tr>`);

sl_no++;
					}
                }
            }
    });


}