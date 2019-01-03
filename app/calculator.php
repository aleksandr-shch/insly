<?php

include_once 'calc.inc.php';

/**
 * @param $success
 * @param $message
 * @return false|string
 */
function response($message, $success = true)
{
    header('Content-type: application/json');

    return json_encode(array('success' => $success, 'message' => $message));
}

/**
 * @param $message
 * @return false|string
 */
function successResponse($message){
    return response($message, true);
}

/**
 * @param $message
 * @return false|string
 */
function errorResponse($message){
    return response($message, false);
}

/**
 * @param $time_zone
 * @return string
 */
function getFriday($time_zone){
    $friday = '';

    if(date('L') == 'friday'){

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

    return $friday;
}

if(($_POST['estimated'] >= 100 && $_POST['estimated'] <= 100000)
    && ($_POST['tax'] >= 0 && $_POST['tax'] <= 100)
    && ($_POST['installment'] >= 1 && $_POST['installment'] <= 12)){

    $num1 = $_POST['estimated'];
    $num2 = $_POST['tax'];
    $num3 = $_POST['installment'];
    $time_zone = $_POST['time_zone'];

    $calculator = new Calculation($num1, $num2, $num3, getFriday($time_zone));

    if(is_array($calculator->calculate())){
        echo successResponse($calculator->calculate());
    }
    else{
        echo errorResponse("An error occurred, please try later");
    }
}
else{
    echo errorResponse("Please, fill all fields correctly");
}
