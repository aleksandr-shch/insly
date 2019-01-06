<?php

require_once './app/DataValidator.php';

try {
    $validate = new DataValidator();
}
catch(EmptyDataException $e) {
    echo errorResponse($e->getMessage());
}

if($validate->post){
    require_once './app/CarInsuranceCalculator.php';

    //Calculate
    $calc = new CarInsuranceCalculator();

    $calc->setCarValue($validate->post['value']);
    $calc->setPolicyPercentage(getBase($validate->post['user_time']));
    $calc->setCommissionPercentage(17);
    $calc->setTaxPercentage($validate->post['tax']);
    $calc->setInstallmentsCount($validate->post['installments']);

    $result = $calc->calculate();

    if($result){
        echo successResponse($result);
    }
    else{
        echo errorResponse("An error occurred, please try later");
    }
}

/**
 * @param $success
 * @param $message
 * @return false|string
 */
function response($message, $success = true)
{
    header('Content-Type: application/json');

    return json_encode(array('success' => $success, 'message' => $message));
}

/**
 * @param $message
 * @return false|string
 */
function successResponse($message)
{
    return response($message, true);
}

/**
 * @param $message
 * @return false|string
 */
function errorResponse($message)
{
    return response($message, false);
}

/**
 * @param $time_zone
 * @return int
 */
function getBase($time_zone) : int
{
    $time = time() + (int)$time_zone*60;

    (date("l", $time) == 'Friday' && date("H", $time) > 14 && date("H", $time) < 21) ? $base = 13 : $base = 11;

    return $base;
}
