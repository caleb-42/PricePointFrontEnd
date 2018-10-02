<?php 

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}

if($_GET["c"] == "get"){
    $inv = DbHandler::select_cmd(['table' => 'invoice',
                                  'qcol' => ['id'],
                                  'qval' => [1],
                                  'cond' => ["="]]); 
    echo json_encode($inv[3]);
}else{
    DbHandler::update_cmd(['table' => 'invoice',
                           'col' => ["inv"],
                           'val' => [$_GET["c"]],
                           'cond' => ["="],
                           'qcol' => ['id'],
                           'qval' => [1]]);
}
?>