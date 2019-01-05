<?php

if(($_POST['estimated'] >= 100 && $_POST['estimated'] <= 100000)
    && ($_POST['tax'] >= 0 && $_POST['tax'] <= 100)
    && ($_POST['installment'] >= 1 && $_POST['installment'] <= 12)){

    //POST
    $num1 = $_POST['estimated'];
    $num2 = $_POST['tax'];
    $num3 = $_POST['installment'];
    $time_zone = $_POST['time_zone'];

    require_once './app/CarInsuranceCalculator.php';

    //Calculate
    $calc = new CarInsuranceCalculator();

    $calc->setCarCost($num1);
    $calc->setPolicyPercentage(getBase($time_zone));
    $calc->setCommissionPercentage(17);
    $calc->setTaxPercentage($num2);
    $calc->setInstallmentsCount($num3);

    $result = $calc->calculate();

    if($result){
        echo successResponse($result );
    }
    else{
        echo errorResponse("An error occurred, please try later");
    }
}
else{
    echo errorResponse("Please, fill all fields correctly");
}

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
function getBase($time_zone){
    $base = 11;

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

        if($date > 14 && $date < 21) $base = 13;
    }

    return $base;
}
