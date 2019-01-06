$(document).ready(function ()
{
    $("#calculator button").click(function()
    {
        let form = $("#calculator");

        if(form.validate().form() === true) {
            $(this).attr('disabled', true).text('Please wait...');
            let estimated = $("#input_calc1").val();
            let tax = $("#input_calc2").val();
            let installment = $("#input_calc3").val();
            let message = $("#message");
            let table = $(".table");
            table.find('tr > th').slice(2).remove();
            table.find('tr').each(function (index, value)
            {
                $(this).find('td').slice(2).remove()
            });
            message.css('visibility', 'hidden').text('');

            $.ajax({

                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function (response)
                {
                    if (response.success === true) {
                        message.css('visibility', 'visible').css('color', 'green').text('Calculated');
                        table.css('visibility', 'visible');
                        cellsAddText(table, 2, 'first-child', 'Base premium (' + response.message.policyPercentage + '%)');
                        cellsAddText(table, 4, 'first-child', 'Tax (' + response.message.taxPercentage + '%)');
                        cellsAddText(table, 1, 'nth-child(2)', response.message.carValue);
                        cellsAddText(table, 2, 'nth-child(2)', response.message.basePremium);
                        cellsAddText(table, 3, 'nth-child(2)', response.message.commission);
                        cellsAddText(table, 4, 'nth-child(2)', response.message.tax);
                        cellsAddText(table, 5, 'nth-child(2)', response.message.total);

                        if (parseInt(response.message.installmentsCount) > 1) {
                            let k = 1;

                            for (let i = 1; i <= response.message.installmentsCount; i++) {
                                k = i + 1;
                                table.find("th:nth-child(" + k + ")").after('<th>' + i + ' Installment</th>');
                                cellsAddText(table, 1, 'nth-child(' + k + ')', '&nbsp;', 1);
                                cellsAddText(table, 2, 'nth-child(' + k + ')', response.message.installments[i - 1].basePremium, 1);
                                cellsAddText(table, 3, 'nth-child(' + k + ')', response.message.installments[i - 1].commission, 1);
                                cellsAddText(table, 4, 'nth-child(' + k + ')', response.message.installments[i - 1].tax, 1);
                                cellsAddText(table, 5, 'nth-child(' + k + ')', response.message.installments[i - 1].total, 1);
                            }
                        }
                    } else {
                        message.css('visibility', 'visible').css('color', 'red').text(response.message);
                    }
                },
                error: function (request, status, error)
                {
                    message.css('visibility', 'visible').css('color', 'red').text('An error occurred, please try later');
                }
            });


            $(this).attr('disabled', false).text('Calculate');
        }
    });

    function cellsAddText(table, row, child, text, after = 0)
    {
        if(after > 0){
            table.find(".row"+row).find("td:"+child).after("<td> "+text+"</td>");
        }
        else{
            table.find(".row"+row).find("td:"+child).text(text);
        }
    }
});
