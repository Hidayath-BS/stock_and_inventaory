function pRtable(){
    var length1 = $("#purchaseReturnTable").length;
    for(i =1;i<=length1;i++){
        $("#PUR_"+i+1).html(" ");
        $("#PUR_"+i+2).html(" ");
    }
}




function rPtable(){
    var len = $("#purchaseTable").length;
    for(i =1;i<=len;i++){
        $("#PU_"+i+1).text(" ");
        $("#PU_"+i+2).text(" ");
    }
} 


//sales table refresh

function salestable(){
    var salength = $("#salesTable").length;
    for(j = 1;j<=salength;j++){
        $("#SA_"+j+1).text(" ");
        $("#SA_"+j+2).text(" ");
    }
}


//sales return refresh

function salesreturntable(){
    var srlength = $("#salesReturnTable").length;
    for(k = 1;k<=srlength;k++){
        $("#SRP_"+k+1).text(" ");
        $("#SRP_"+k+2).text(" ");
    }
}



function f1(object){
//            alert(object.value);

    var id = object.value;
    
    

    var dataString = 'empid='+id;
//        alert(id);

		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(purchaseData) {
			   if(purchaseData) {






                   console.log(purchaseData);
                   console.log(purchaseData[0]);

                    row =1;


                            rPtable();


                         for(i = 0;i<=purchaseData.length;i++){
                         $("#PU_"+row+1).html(purchaseData[i].name + " "+ purchaseData[i].type );
                         $("#PU_"+row+2).text(purchaseData[i].quantity+" "+purchaseData[i].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_product_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(productDetails) {
			   if(productDetails) {






                   console.log(productDetails);
                   console.log(productDetails[0]);

                   $("#purchasecount").html(productDetails[0].counter);
                   
                   var total = productDetails[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#purchaseamount").html(total);


				} else {

				}
			}
		});
    
    //purchase return count
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_return_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(prCount) {
			   if(prCount) {






                   console.log(prCount);
                   console.log(prCount[0]);

                   $("#purchasereturnCount").html(prCount[0].counter);
                   
                   var total = prCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#purchasereturnAmount").html(total);


				} else {

				}
			}
		});
    
    
    //purchase return products
    
    
    	$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_return_products_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(purchaseReturnData) {
			   if(purchaseReturnData) {



             

                   console.log(purchaseReturnData);
                   console.log(purchaseReturnData[0]);

                    row =1;


                          pRtable();

                         for(j = 0;j<=purchaseReturnData.length;j++){
                    $("#PUR_"+row+1).html(purchaseReturnData[j].name + " " + purchaseReturnData[j].type );
                         $("#PUR_"+row+2).text(purchaseReturnData[j].quantity+" "+purchaseReturnData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    //sales counter module
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_counter_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesCount) {
			   if(salesCount) {






                   console.log(salesCount);
                   console.log(salesCount[0]);

                   $("#salescount").html(salesCount[0].counter);
                   
                   var total = salesCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#salesamount").html(total);


				} else {

				}
			}
		});
    
    //SALES PROUCT MODULE
    
    
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_products_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesData) {
			   if(salesData) {



             

                   console.log(salesData);
                   console.log(salesData[0]);

                    row =1;


                          salestable();

                         for(j = 0;j<=salesData.length;j++){
                    $("#SA_"+row+1).html(salesData[j].name + " " + salesData[j].type );
                         $("#SA_"+row+2).text(salesData[j].quantity+" "+salesData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    //sales return module
    
    
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_return_counter_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesReturnCount) {
			   if(salesReturnCount) {






                   console.log(salesReturnCount);
                   console.log(salesReturnCount[0]);

                   $("#salesreturnCount").html(salesReturnCount[0].counter);
                   
                   var total = salesReturnCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#salesreturnamount").html(total);


				} else {

				}
			}
		});
    
    
    //sales Return product details
    
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_return_products_details.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesReturnData) {
			   if(salesReturnData) {



             

                   console.log(salesReturnData);
                   console.log(salesReturnData[0]);

                    row =1;


                          salesreturntable();

                         for(j = 0;j<=salesReturnData.length;j++){
                    $("#SRP_"+row+1).html(salesReturnData[j].name + " " + salesReturnData[j].type );
                         $("#SRP_"+row+2).text(salesReturnData[j].quantity+" "+salesReturnData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    

}


//function with 2 ajax inputs


function f2(object){
//     alert($("#start").val());
//    
//    alert(object.value);
//   
    
    
    var id = object.value;
    var start = $("#start").val();
    

    var dataString = 'empid='+id+'& empid2='+start;

    
// purchase count   
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_product_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(productDetails) {
			   if(productDetails) {

                   console.log(productDetails);
                   console.log(productDetails[0]);

                   $("#purchasecount").html(productDetails[0].counter);
                   
                   var total = productDetails[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#purchaseamount").html(total);


				} else {

				}
			}
		});
    
    
    
    //purchase products
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(purchaseData) {
			   if(purchaseData) {

                   console.log(purchaseData);
                   console.log(purchaseData[0]);

                    row =1;


                            rPtable();


                         for(i = 0;i<=purchaseData.length;i++){
                         $("#PU_"+row+1).html(purchaseData[i].name + " "+ purchaseData[i].type );
                         $("#PU_"+row+2).text(purchaseData[i].quantity+" "+purchaseData[i].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    //purchase return 2 input
    
    	$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_return_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(prCount) {
			   if(prCount) {

                   console.log(prCount);
                   console.log(prCount[0]);

                   $("#purchasereturnCount").html(prCount[0].counter);
                   
                   var total = prCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#purchasereturnAmount").html(total);


				} else {

				}
			}
		});
    
    //purchase return prodcts
    
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_purchase_return_products_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(purchaseReturnData) {
			   if(purchaseReturnData) {



             

                   console.log(purchaseReturnData);
                   console.log(purchaseReturnData[0]);

                    row =1;


                          pRtable();

                         for(j = 0;j<=purchaseReturnData.length;j++){
                    $("#PUR_"+row+1).html(purchaseReturnData[j].name + " " + purchaseReturnData[j].type );
                         $("#PUR_"+row+2).text(purchaseReturnData[j].quantity+" "+purchaseReturnData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    
    
    //sales counter 2 dates
    
    
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_counter_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesCount) {
			   if(salesCount) {






                   console.log(salesCount);
                   console.log(salesCount[0]);

                   $("#salescount").html(salesCount[0].counter);
                   
                   var total = salesCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#salesamount").html(total);


				} else {

				}
			}
		});
    
    
    //sales product details 2 date
    
    
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_products_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesData) {
			   if(salesData) {



             

                   console.log(salesData);
                   console.log(salesData[0]);

                    row =1;


                          salestable();

                         for(j = 0;j<=salesData.length;j++){
                    $("#SA_"+row+1).html(salesData[j].name + " " + salesData[j].type );
                         $("#SA_"+row+2).text(salesData[j].quantity+" "+salesData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    //sales return counter 2 dates
    
    		$.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_return_counter_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesReturnCount) {
			   if(salesReturnCount) {






                   console.log(salesReturnCount);
                   console.log(salesReturnCount[0]);

                   $("#salesreturnCount").html(salesReturnCount[0].counter);
                   
                   var total = salesReturnCount[0].total;
                   if(total == null){
                       total = 0;
                   }
                   
                   $("#salesreturnamount").html(total);


				} else {

				}
			}
		});
    
        
    $.ajax({
			url: './dashboard_ajax_files/ajax_dashboard_sales_return_products_details2.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(salesReturnData) {
			   if(salesReturnData) {



             

                   console.log(salesReturnData);
                   console.log(salesReturnData[0]);

                    row =1;


                          salesreturntable();

                         for(j = 0;j<=salesReturnData.length;j++){
                    $("#SRP_"+row+1).html(salesReturnData[j].name + " " + salesReturnData[j].type );
                         $("#SRP_"+row+2).text(salesReturnData[j].quantity+" "+salesReturnData[j].quantity_type);
                        
                             row++;
                         }
                        



				} else {

				}
			}
		});
    
    
    
}


