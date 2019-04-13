debugger;
var rIndex1,
                table2 = document.getElementById("salestable");
            
            // check the empty input
            function checkEmptyInput1()
            {
                var isEmpty1 = false,
                    expense_id = $("#expenses option:selected").val(),
                   expense_name = $("#expenses option:selected").html(),
                    expense_value = document.getElementById("expensevalue1").value;
                
                
                   
            }

function reset(){
           $("#expenses").val(0);
            $("#expensevalue1").val(0);
         
         
        }  
            // add Row
            function addHtmlTableRow1()
            {
                // get the table by id
                // create a new row and cells
                // get value from input text
                // set the values into row cell's
                if(!checkEmptyInput1()){
                var newRow1 = table2.insertRow(table2.length),
                    cell1 = newRow1.insertCell(0),
                    cell2 = newRow1.insertCell(1),
                    cell3 = newRow1 .insertCell(2),
                     expense_id = $("#expenses option:selected").val(),
                   expense_name = $("#expenses option:selected").html(),
                    expense_value = document.getElementById("expensevalue1").value;

                        cell1.innerHTML = expense_id;
                        cell2.innerHTML =  expense_name;
                        cell3.innerHTML =  expense_value;
                       
                selectedRowToInput1();
                    reset();
                 salescolum();     
                     
            }
            }
           
            // display selected row data into input text
            function selectedRowToInput1()
            {
                
                for(var i = 1; i < table2.rows.length; i++)
                {
                    table2.rows[i].onclick = function()
                    {
                      // get the seected row index
                     
                      rIndex1 = this.rowIndex;
                        $('#expenses').val(this.cells[0].innerHTML);
                         $('#expensevalue1').val(this.cells[2].innerHTML);
                    };
                }        
            }

            selectedRowToInput1();
                 


           function editHtmlTbleSelectedRow1()
           {
               var 
          expense_id = $("#expenses option:selected").val(),
                   expense_name = $("#expenses option:selected").html(),
                    expense_value = document.getElementById("expensevalue1").value;
               if(!checkEmptyInput1()){
                   table2.rows[rIndex1].cells[0].innerHTML = expense_id;
                table2.rows[rIndex1].cells[1].innerHTML =  expense_name ;
                table2.rows[rIndex1].cells[2].innerHTML =  expense_value;     
             }
           }
         
            function removeSelectedRow1()
            {
                table2.deleteRow(rIndex1);      
                 $('#expenses').val(this.cells[0]? this.cells[0].innerHTML:'');
                 $('#expenses option:selected').val( this.cells[1].innerHTML);
                 $('#expensevalue1').val(this.cells[2].innerHTML);
            
                      
            }
  
         
        
              
                  $(function(){
                    $("#scash,#scredit").change(function(){
                        if($("#scash").is(":checked")){
                                   $("#scash1").removeAttr('hidden');
                                   $("#scash1").show(); 


                                    $("#stotalreceivable").prop('required',true);
                                    $("#stotalreceived").prop('required',true);
                                    $("#sbalanceamt").prop('required',true);



                                    $("#scash").css({"background-color":"black","color":"white"});
                                    $("#scredit").css({"background-color":"dimgray"});
                             }
                        else if($("#scredit").is(":checked")){
                            $("#scash1").hide(); 
                            $("#scredit").css({"background-color":"black","color":"white"});
                            $("#scash").css({"background-color":"dimgray"});

                             $("#stotalreceivable").prop('required',false);
                            $("#stotalreceived").prop('required',false);
                            $("#sbalanceamt").prop('required',false);
                        }
                    });
                });
                
                $(function(){
                    $("#scashm,#schequem").change(function(){
                    $("#schequenumber").val("").attr("readonly",true);
                    if($("#schequem").is(":checked")){
                        $("#schequenumber").removeAttr("readonly");
                        $("#schequenumber").prop('required',true);
                        $("#schequenumber").focus();   
                     }else if($("#scashm").is(":checked")){
                         $("#schequenumber").attr("readonly",true);
                         $("#schequenumber").prop('required',false);
                         
                     }
    });
});
    
            
            