var rIndex1,
    
   
                table1 = document.getElementById("table");
            
            // check the empty input
            function checkEmptyInput1()
            {
                var isEmpty1 = false,
                    expense_id = $("#expense option:selected").val(),
                   expense_name = $("#expense option:selected").html(),
                    expense_value = document.getElementById("expensevalue").value;
                   
            }
            // add Row
function clears(){
           $("#expense").val(0);
            $("#expensevalue").val(0);
           
        }
        

            function addHtmlTableRow1()
            {
                // get the table by id
                // create a new row and cells
                // get value from input text
                // set the values into row cell's
                if(!checkEmptyInput1()){
                var newRow1 = table1.insertRow(table1.length),
                    cell1 = newRow1.insertCell(0),
                    cell2 = newRow1.insertCell(1),
                    cell3 = newRow1 .insertCell(2),
                     expense_id = $("#expense option:selected").val(),
                   expense_name = $("#expense option:selected").html(),
                    expense_value = document.getElementById("expensevalue").value;

                        cell1.innerHTML = expense_id;
                        cell2.innerHTML =  expense_name;
                        cell3.innerHTML =  expense_value;
                   columsi();     
                selectedRowToInput1();
                    clears();
                
            }
            }
           
            // display selected row data into input text
            function selectedRowToInput1()
            {
                
                for(var i = 1; i < table1.rows.length; i++)
                {
                    table1.rows[i].onclick = function()
                    {
                      // get the seected row index
                     
                      rIndex1 = this.rowIndex;
                        $('#expense').val(this.cells[0].innerHTML);
                         $('#expensevalue').val(this.cells[2].innerHTML);
                    };
                }        
            }
            selectedRowToInput1();
                
           function editHtmlTbleSelectedRow1()
           {
               var 
          expense_id = $("#expense option:selected").val(),
                   expense_name = $("#expense option:selected").html(),
                    expense_value = document.getElementById("expensevalue").value;
               if(!checkEmptyInput1()){
                   table1.rows[rIndex1].cells[0].innerHTML = expense_id;
                table1.rows[rIndex1].cells[1].innerHTML =  expense_name ;
                table1.rows[rIndex1].cells[2].innerHTML =  expense_value;     
             }
           }
         
            function removeSelectedRow1()
            {
                table1.deleteRow(rIndex1);      
                 $('#expense').val(this.cells[0]? this.cells[0].innerHTML:'');
                 $('#expense option:selected').val( this.cells[1].innerHTML);
                 $('#expensevalue').val(this.cells[2].innerHTML);
            
                      
            }
        