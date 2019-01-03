<?php

$name = str_split('Aleksandr');
$res = '';

foreach($name as $letter){
    $res .= sprintf("%08d ", decbin(ord($letter)));
}

echo  $res;
