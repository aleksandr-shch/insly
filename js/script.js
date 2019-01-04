$(document).ready(function () {

    $("#calculator button").click(function() {

        $(this).attr('disabled', true).text('Please wait...');
        let estimated = $("#input_calc1").val();
        let tax = $("#input_calc2").val();
        let installment = $("#input_calc3").val();
        let message = $("#message");
        let form = $("#calculator");
        let table = $(".table");
        table.find('tr > th').slice(2).remove();
        table.find('tr').each(function(index,value){$(this).find('td').slice(2).remove()});
        message.css('visibility', 'hidden').text('');

        if((parseInt(estimated) >= 100 && parseInt(estimated) <= 100000)
            && (parseInt(tax) >= 0 && parseInt(tax) <= 100)
            && (parseInt(installment) >= 1 && parseInt(installment) <= 12)){

            $.ajax({

                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
console.log(response.message);
                    if(response.success === true){
                        message.css('visibility', 'visible').css('color', 'green').text('Calculated');
                        table.css('visibility','visible');
                        cellsAddText(table, 2, 'first-child', 'Base premium ('+response.message.base+'%)');
                        cellsAddText(table, 4, 'first-child', 'Tax ('+response.message.num2+'%)');
                        cellsAddText(table, 1, 'nth-child(2)', response.message.num1);
                        cellsAddText(table, 2, 'nth-child(2)', response.message.base_total);
                        cellsAddText(table, 3, 'nth-child(2)', response.message.commission_total);
                        cellsAddText(table, 4, 'nth-child(2)', response.message.tax_total);
                        cellsAddText(table, 5, 'nth-child(2)', response.message.total_cost);

                        if(parseInt(response.message.num3) > 1)
                        {
                            let j = 1;

                            for(let i = 1; i <= response.message.num3; i++){

                                let base = '';
                                let commission = '';
                                let tax = '';
                                let total_cost = '';

                                if(i == 1){
                                    base = response.message.base_corrected;
                                    commission = response.message.commission_corrected;
                                    tax = response.message.tax_corrected;
                                    total_cost = response.message.total_cost_corrected;
                                }
                                else{
                                    base = response.message.base_divided;
                                    commission = response.message.commission_divided;
                                    tax = response.message.tax_divided;
                                    total_cost = response.message.total_cost_divided;
                                }

                                let k = i + 1;
                                table.find("th:nth-child("+k+")").after('<th>'+i+' Installment</th>');
                                cellsAddText(table, 1, 'nth-child('+k+')', '&nbsp;', 1);
                                cellsAddText(table, 2, 'nth-child('+k+')', base, 1);
                                cellsAddText(table, 3, 'nth-child('+k+')', commission, 1);
                                cellsAddText(table, 4, 'nth-child('+k+')', tax, 1);
                                cellsAddText(table, 5, 'nth-child('+k+')', total_cost, 1);
                            }
                        }
                    }
                    else{
                        message.css('visibility', 'visible').css('color', 'red').text(response.message);
                    }
                },
                error: function() {
                    message.css('visibility', 'visible').css('color', 'red').text('An error occurred, please try later');
                }
            });

        }
        else{
            message.css('visibility', 'visible').css('color', 'red').text('Please, fill all fields correctly');
        }
        $(this).attr('disabled', false).text('Calculate');
    });

    function cellsAddText(table, row, child, text, after = 0) {

        if(after > 0){
            table.find(".row"+row).find("td:"+child).after("<td> "+text+"</td>");
        }
        else{
            table.find(".row"+row).find("td:"+child).text(text);
        }
    }
});
