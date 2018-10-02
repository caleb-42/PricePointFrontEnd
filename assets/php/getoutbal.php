<?php

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}
$a = $_GET["c"];
$usd =  DbHandler::select_cmd([
    'table' => 'customers',
    'qcol' => ["customer_name"],
    'qval' => [$a],
    'cond' => ["="]
]);
if(!empty($usd[3])){
   echo $usd[3][0]["outstanding_balance"];
}else{
    echo "0";
}

?>