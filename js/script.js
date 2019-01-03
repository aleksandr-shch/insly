$(document).ready(function () {

    $("#calculator button").click(function() {

        $(this).attr('disabled', true).text('Please wait...');
        let estimated = $("#input_calc1").val();
        let tax = $("#input_calc2").val();
        let installment = $("#input_calc3").val();
        let message = $("#message");
        let form = $("#calculator");
        let table = $(".table");
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

                    if(response.success === true){
                        message.css('visibility', 'visible').css('color', 'green').text('Calculated');
                        table.css('visibility','visible');
                        table.find(".row2").find("td:first-child").text('Base premium ('+response.message.base_premium+'%)');
                        table.find(".row4").find("td:first-child").text('Tax ('+response.message.tax_rate+'%)');
                        table.find(".row1").find("td:nth-child(2)").text(response.message.value);
                        table.find(".row2").find("td:nth-child(2)").text(response.message[0].base);
                        table.find(".row3").find("td:nth-child(2)").text(response.message[0].commission);
                        table.find(".row4").find("td:nth-child(2)").text(response.message[0].tax);
                        table.find(".row5").find("td:nth-child(2)").text(response.message[0].total);

                        if(parseInt(response.message.installments) > 1)
                        {
                            let j = 1;

                            for(let i = 1; i <= response.message.installments; i++){

                                if(i == 1){
                                    j = 2;
                                }
                                else{
                                    j = 1;
                                }
                                table.find("th:nth-child("+(i+1)+")").after('<th>'+i+' Installment</th>');
                                table.find(".row1").find("td:nth-child("+(i+1)+")").after('<td>&nbsp;</td>');
                                table.find(".row2").find("td:nth-child("+(i+1)+")").after('<td>'+response.message[j].base+'</td>');
                                table.find(".row3").find("td:nth-child("+(i+1)+")").after('<td>'+response.message[j].commission+'</td>');
                                table.find(".row4").find("td:nth-child("+(i+1)+")").after('<td>'+response.message[j].tax+'</td>');
                                table.find(".row5").find("td:nth-child("+(i+1)+")").after('<td>'+response.message[j].total+'</td>');
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
});
