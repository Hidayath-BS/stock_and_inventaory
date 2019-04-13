function f2(object){
//alert(object.value);
var id = object.value;

var dataString = 'empid='+ id;

        $.ajax({
            url:'balance_ajax_purchaseentry.php',
            dataType: "json",
            data: dataString,
            cache:false,
            success: function(balanceData){
                if(balanceData){
                  console.log(balanceData);
                  var bal = parseFloat(balanceData.balance * -1);
                 $("#balrecvable").val(bal);
                }

            }
        });



}
