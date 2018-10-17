<?php 

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}

switch($_GET["c"]){
    case "every":
        $cust = DbHandler::select_cmd(['table' => 'customers']);
        echo json_encode($cust[3]);
        break;
    case "only":
        $nam = "%". $_GET["nam"] ."%";
            $cust = DbHandler::select_cmd([
                'table' => 'customers',
                'qcol' => ['customer_name'],
                'qval' => [$nam],
                'cond' => ["like"],
            ]);
        echo json_encode($cust[3]);
        break;
    case "store":
        $cust = DbHandler::select_cmd(['table' => 'customers', 'qcol' =>['customer_name'], 'qval' =>[$_GET["nam"]], 'cond' => ["="]]);
        //print_r($cust);
        if(empty($cust[3])){
            $cust = DbHandler::insert_cmd([
                'table' => 'customers',
                'col' => ["customer_name","address","customer_phone"],
                'val' => [$_GET["nam"], $_GET["add"],$_GET["phn"]]
            ]);
            $cust = DbHandler::select_cmd(['table' => 'customers', 'qcol' =>['customer_name'], 'qcol' =>[$_GET["nam"]], 'cond' => ["="]]);
            echo $cust[3][0]["id"];
        }else{
            echo "have already";
        }
        //echo json_encode($cust);
        break;
        break;
    default:
        $cust = DbHandler::select_cmd(['table' => 'customers', 'qcol' =>['customer_name'], 'qval' =>[$_GET["c"]], 'cond' => ["="]]);
        //print_r($cust);
        if(empty($cust[3])){
            $cust = DbHandler::insert_cmd([
                'table' => 'customers',
                'col' => ["customer_name"],
                'val' => [$_GET["c"]]
            ]);
            $cust = DbHandler::select_cmd(['table' => 'customers', 'qcol' =>['customer_name'], 'qval' =>[$_GET["c"]], 'cond' => ["="]]);
            echo $cust[3][0]["id"];
        }else{
            echo "have already";
        }
    break;
}





?>