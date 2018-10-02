<?php 

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}

    $stk = DbHandler::select_cmd(['table' => 'stock',
                                  'qcol' => ['productname'],
                                  'qval' => [$_GET["c"]],
                                  'cond' => ["="]]); 
    echo json_encode($stk[3]);
?>