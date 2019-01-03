<?php

include_once 'calc.inc.php';

function Response($success, $message)
{
    header('Content-type: application/json');

    return json_encode(array('success' => $success, 'message' => $message));
}

if(($_POST['estimated'] >= 100 && $_POST['estimated'] <= 100000)
    && ($_POST['tax'] >= 0 && $_POST['tax'] <= 100)
    && ($_POST['installment'] >= 1 && $_POST['installment'] <= 12)){

    $num1 = $_POST['estimated'];
    $num2 = $_POST['tax'];
    $num3 = $_POST['installment'];
    $friday = '';
    $time_zone = $_POST['time_zone'];


    if(date('L') == 'friday'){
        $date = date("H");

        if($time_zone > 0){
            $shift = '30';
        }
        else{
            $shift = '-30';
        }

        if(abs($time_zone) >= 60){
            $shift = $time_zone;
        }
        $time = time() + $shift*60;
        $date = date("H", $time);

        if($date > 14 && $date < 21) $friday = 'friday';
    }
    $calculator = new Calculation($num1, $num2, $num3, $friday);

    if(is_array($calculator->calculate())){
        echo Response(true, $calculator->calculate());
    }
    else{
        echo Response(false, "An error occurred, please try later");
    }
}
else{
    echo Response(false, "Please, fill all fields correctly");
}
