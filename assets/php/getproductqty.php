<?php

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}

$a = $_GET["exp"];
$b = $_GET["nam"];
$usd =  DbHandler::select_cmd([
    'table' => 'stock',
    'qcol' => ["expirydate","productname"],
    'qval' => [$a, $b],
    'cond' => ["="],
    'conj' => ["AND"],
]);
if(!empty($usd[3])){
    echo $usd[3][0]["stockremain"];
}else{
    echo "0";
}

?>