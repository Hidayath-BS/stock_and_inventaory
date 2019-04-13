
function datepick(){
	var date = $("#date").val();

	var dataString = 'date='+date;

	$.ajax({

		url: 'cash_module/ajax_data/cash_book_data_list.php',
			dataType: "json",
			data: dataString,
      		type: 'POST',
			cache: false,
			success: function(cash) {
				console.log(cash);

				if(cash){
					var sl_no = 1;

					$("#table_body").html("");

          var sl_no =1;
					for(i = 0; i<cash.length;i++){
						cash[i] = JSON.parse(cash[i]);
						// console.log(cash[i]);




						var mydate = new Date(cash[i].date);
						var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

   					var str =(curr_date+ "-" + curr_month + "-" + curr_year );

						$("#table_body").append(`

              <tr class="custtd">
                <td scope="row">`+sl_no+`</td>
                <td>`+str+`</td>
                <td>`+cash[i].particulars+` </td>
                <td>`+cash[i].dr+`</td>
                <td>`+cash[i].cr+`</td>




                <td>`+cash[i].balance+`</td>





              </tr>

							`);

sl_no++;
					}


				}




			}
	});
}




// get dr and cr here and return it to datepick2 function then do nesseary calc in datepick2
function getTrans(){

	var date = $("#date").val();

	var dataString = 'date='+date;

	var cashData = [];

	$.ajax({

		url: 'cash_module/ajax_data/cash_book_data_list.php',
			dataType: "json",
			data: dataString,
					type: 'POST',
			cache: false,
			success: function(cash) {

				if(cash){
					var sl_no = 1;

					var sl_no =1;


					for(i = 0; i<cash.length;i++){

						cash[i] = JSON.parse(cash[i]);
						cashData.push(cash[i]);


								// sl_no++;
					}
					// console.log(cashData);


				}




			}
	});
return cashData;

}

function openBal(){
	var date = $("#date").val();

	var dataString = 'date='+date;

	var openBal = [];

	$.ajax({

		url: 'cash_module/ajax_data/opening_bal_data.php',
			dataType: "json",
			data: dataString,
			type: 'POST',

			cache: false,
			success: function(cash) {

				if(cash){
					cash[0] = JSON.parse(cash[0]);
					openBal.push(cash[0]);

					// console.log(openBal);


				}

			}
	});


return openBal;

}

// // cashbook logic 2
// function datepick2(){
//
// 	var openBalData = [];
// 	openBalData.push(openBal());
// 	console.log(openBalData);
//
//
// 	//
// 	var openCr = openBalData[0][0].credits;
// 	var openDr = openBalData[0][0].debits;
//
//
// // debugger;
//
// console.log(openCr);
//   var v = +openCr;
// 	console.log(v);
// 	console.log(parseFloat(openDr));
//
//
// var opening_bal = parseFloat(openDr-openCr);
//
// 	var data = [];
//  	data.push(getTrans());
//
//
// 	// console.log(opening_bal);
// 	console.log(data);
// }
//
//

function data(){
	var openbal,openCr,openDr =[];

	let date = $("#date").val();

	let dataString = 'date='+date;

	let openBal = [];

	$.ajax({

		url: 'cash_module/ajax_data/opening_bal_data.php',
			dataType: "json",
			data: dataString,
			type: 'POST',

			cache: false,
			success: function(cash) {

				if(cash){
					cash[0] = JSON.parse(cash[0]);
					openBal.push(cash[0]);

					openbal  = openBal;

var cashData = [];

$.ajax({

	url: 'cash_module/ajax_data/cash_book_data_list.php',
		dataType: "json",
		data: dataString,
				type: 'POST',
		cache: false,
		success: function(cash) {

			if(cash){

				var sl_no =1;


				for(i = 0; i<cash.length;i++){

					cash[i] = JSON.parse(cash[i]);
					cashData.push(cash[i]);


							// sl_no++;
				}
				// console.log(cashData);


			}







var transactions = cashData;

// console.log(transactions[0].particulars);

// bertin


 test(openbal,transactions);

}
});



 				}

 			}
 	});


}

function test(a,b){


	$("#table_body").html("");
	// console.log("openBal :",a);
	// console.log("trans : ",b);

// var json = JSON.parse(b);
var arr = [];

arr = b;

var count = Object.keys(b).length;
var count2 = Object.keys(a).length;
// console.log(count);
// console.log(count2);

var opencr = a[0].credits;
var opendr = a[0].debits;

var openBal = parseFloat(opendr - opencr);
// console.log("OpenCr :",opencr);
// console.log("OpenDr :",opendr);
// console.log(openBal);

var balance = openBal;

var sl_no = 1;
for(var i = 0;i<count;i++){

	var dr = arr[i].dr;
	var cr = arr[i].cr;
	balance = parseFloat(balance)+parseFloat(dr-cr);
	var mydate = new Date(arr[i].date);
	var curr_date = mydate.getDate();
var curr_month = mydate.getMonth() + 1; //Months are zero based
var curr_year = mydate.getFullYear();

var str1 =(curr_date+ "-" + curr_month + "-" + curr_year );


	// console.log("Serial Number:",sl_no,"date : ",str1,"particulars",arr[i].particulars,"credits:",arr[i].cr," debits:",arr[i].dr,"balance : ",balance);
	$("#table_body").append(`

		<tr class="custtd">
			<td scope="row">`+sl_no+`</td>
			<td>`+str1+`</td>
			<td>`+arr[i].particulars+` </td>
			<td>`+arr[i].dr+`</td>
			<td>`+arr[i].cr+`</td>




			<td>`+balance+`</td>





		</tr>

		`);

sl_no++;
}




	// console.log(b.length);
}
