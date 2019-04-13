function datepick(){
  var date = $("#date").val();

	var dataString = 'date='+date;

	$.ajax({

		url: 'user_log_module/user_log_list_data.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(log) {
				// console.log(question);

				if(log){
					var sl_no = 1;

					$("#table_body").html("");

					for(i = 0; i<log.length;i++){
						log[i] = JSON.parse(log[i]);
						console.log(log[i]);

						$("#table_body").append(`

              <tr style="font-size: 14px;">
                <td>`+sl_no+`</td>
                <td>`+log[i].user_name+`</td>

                <td>`+log[i].login_time+`</td>
                <td>`+log[i].logout_time+`</td>


              </tr>


							`);

sl_no++;
					}


				}




			}
	});
}
