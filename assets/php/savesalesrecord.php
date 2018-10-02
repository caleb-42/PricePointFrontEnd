<?php 

function __autoload($class_name){
    include "includes/" . $class_name . ".php";
}


/*$cust = DbHandler::select_cmd(['table' => 'customers']);
*/
$salesdata = json_decode(stripcslashes($_GET["c"]));

require_once "myphp-backup-master/myphp-backup.php";

add_to_sale($salesdata);

function add_to_sale($data){
    global $salesdata;
    foreach($data as $d){

        $insert_sales = DbHandler::insert_cmd([
            'table' => 'sales',
            'col' => ["invoiceno", "customer_name", "product","quantity","unitprice","totalprice","totalamt","paidamt","outbal","pricetype","saleref","salesdate","paymethod","expirydate"],
            'val' => $d
        ]);

        $stk = DbHandler::select_cmd([
            'table' => 'stock',
            'qcol' => ['productname','expirydate'],
            'qval' => [$d[2], $d[13]],
            'cond' => ["="],
            'conj' => ["AND"],
        ]);

       // print_r($stk[3]);

        calc_stock($stk, $d);
    }

    update_customer();

    backup($salesdata[0][1]);
}

function calc_stock($stk, $d){
    if(!empty($stk[3])){
        $stkbought = intval($stk[3][0]["stockbought"]);
        $stkid = intval($stk[3][0]["id"]);
        $stksold = intval($stk[3][0]["stocksold"]);
        
        $stksold = $stksold + intval($d[3]);
        
        $stkremain = $stkbought - $stksold;
        
        echo $d[13];
        
        $stqk = DbHandler::update_cmd([
            'table' => 'stock',
            'col' => ['stocksold', 'stockremain'],
            'val' => [$stksold, $stkremain],
            'qcol' => ['productname','expirydate'],
            'qval' => [$d[2], $d[13]],
            'cond' => ["="],
            'conj' => ["AND"],
        ]);
        
        $closestdate = find_closest_expiry_date($d[2]);

        $selr = DbHandler::select_cmd([
            "table" => "stock",
            "cond" => ["="],
            "qcol" => ["productname"],
            "qval" => [$d[2]]
        ]);
        
        $ttot = 0;
        foreach($selr[3] as $s){
            $ttot += intval($s["stockremain"]);
        }
        
        $user = DbHandler::update_cmd([
            "table" => "products",
            "col" => ["stock","expiry_date"],
            "val" => [$ttot, $closestdate],
            "cond" => ["="],
            "qcol" => ["product_name"],
            "qval" => [$d[2]]
        ]);
        echo $ttot;
        print_r($stqk);
    }
}

function update_customer(){
    global $salesdata;
    $usd =  DbHandler::select_cmd([
        'table' => 'customers',
        'qcol' => ["customer_name"],
        'qval' => [$salesdata[0][1]],
        'cond' => ["="]
    ]);

    $out = intval($usd[3][0]["outstanding_balance"]);
    $out =  intval($salesdata[0][8]) + $out;
    
    if(!empty($usd[3])){
        $visit = intval($usd[3][0]["visit_count"]);
        $visit ++;

        $rt = DbHandler::update_cmd([
            'table' => 'customers',
            'col' => ["account_created_on","last_visit", "visit_count","outstanding_balance"],
            'val' => [$salesdata[0][11],$salesdata[0][11],$visit,$out],
            'qcol' =>["customer_name"],
            'qval' =>[$salesdata[0][1]],
            'cond' =>["="]
        ]);
        
        input_in_customer_invoice("customer", $out);
    }else{
        input_in_customer_invoice("visitor",0);
    }
}


function input_in_customer_invoice($cust_type, $out){
    global $salesdata;
    DbHandler::insert_cmd([
        'table' => 'customerinvoice',
        'col' => ["customer", "invno","date","totalamt","totalpaid","outbalance","category","salesref","paymeth"],
        'val' => [$salesdata[0][1],$salesdata[0][0],$salesdata[0][11],$salesdata[0][6],$salesdata[0][7],$out,$cust_type,$salesdata[0][10],$salesdata[0][12]]
    ]);
}

function select_object($tb,$qcol=null,$qval=null,$cond = ["="]){
    if(isset($tb,$qcol,$qval,$cond)){
        $users = DbHandler::select_cmd([
            "table" => $tb,
            "qcol" => $qcol,
            "qval" => $qval,
            "cond" => $cond,
            "conj" => ["AND"],
        ]);
    }else{
        $users = DbHandler::select_cmd([
            "table" => $tb
        ]);
    }
    return $users;

}
function update_object($tb,$col,$val,$qcol,$qval){
    //        sleep(2);
    $user = DbHandler::update_cmd([
        "table" => $tb,
        "col" => $col,
        "val" => $val,
        "cond" => ["="],
        "qcol" => $qcol,
        "qval" => $qval,
        "conj" => ["AND"]
    ]);
    return $user;
}
function get_todays_date(){

    date_default_timezone_set('Africa/Lagos');
    $date = new DateTime();
    $d = $date->getTimestamp();
    $tday = date("Y-m-d H:i:s", $d);

    return $tday;

}
function find_closest_date($arr, $todays_date,$dir){
    $interval = array();
    $newdates = array();
    foreach ($arr as $day){
        if($dir == "before"){
            if(strtotime($todays_date) >= strtotime($day)){
                $interval[] = abs(strtotime($todays_date) - strtotime($day));
                $newdates[] = $day;
            }
        }else{
            if(strtotime($todays_date) <= strtotime($day)){
                $interval[] = abs(strtotime($todays_date) - strtotime($day));
                $newdates[] = $day;
            } 
        }


    }
    $clo ="";
    if(empty($interval)){
        $clo = "0000-00-00" ;
    }else{
        asort($interval);
        $closest = key($interval);
        $clo = $newdates[$closest];
    }

    return $clo;

}
function find_closest_expiry_date($pro, $exp = "none"){
    //$expiry_dates = parent::select_object("stockentry",["product","stocktype"],[$pro, "new"]);
    $expiry_dates = select_object("stock",["productname"],[$pro]);
    $expiry_dates  = $expiry_dates[3];
    $con = 0;
    $arr = array();

    foreach($expiry_dates as $date){
        $arr[$con] = intval($date["stockremain"]) > 0 ? $date["expirydate"] : "0000-00-00";
        $con++;
    }
    $exp != "none" ? array_push($arr, $exp) : null;


    $todays_date = get_todays_date();
    $closestdate = find_closest_date($arr, $todays_date,"after");
    return $closestdate;
}

/*function select_object($tb,$qcol=null,$qval=null,$cond = ["="]){
    if(isset($tb,$qcol,$qval,$cond)){
        $users = DbHandler::select_cmd([
            "table" => $tb,
            "qcol" => $qcol,
            "qval" => $qval,
            "cond" => $cond,
            "conj" => ["AND"],
        ]);
    }else{
        $users = DbHandler::select_cmd([
            "table" => $tb
        ]);
    }
    return $users;

}

function delete_object($tb,$qcol,$qval){
    $users = DbHandler::delete_cmd([
        "table" => $tb,
        "qcol" => $qcol,
        "qval" => $qval,
        "cond" => ["="]
    ]);
}

function find_closest_date($arr, $todays_date,$dir){
    $interval = array();
    $newdates = array();
    foreach ($arr as $day){
        if($dir == "before"){
            if(strtotime($todays_date) >= strtotime($day)){
                $interval[] = abs(strtotime($todays_date) - strtotime($day));
                $newdates[] = $day;
            }
        }else{
            if(strtotime($todays_date) <= strtotime($day)){
                $interval[] = abs(strtotime($todays_date) - strtotime($day));
                $newdates[] = $day;
            } 
        }


    }
    $clo ="";
    if(empty($interval)){
        $clo = "0000-00-00" ;
    }else{
        asort($interval);
        $closest = key($interval);
        $clo = $newdates[$closest];
    }

    return $clo;

}
function get_todays_date(){

    date_default_timezone_set('Africa/Lagos');
    $date = new DateTime();
    $d = $date->getTimestamp();
    $tday = date("Y-m-d H:i:s", $d);

    return $tday;

}
function find_closest_expiry_date($pro){
    $expiry_dates = select_object("stockentry",["product","stocktype"],[$pro, "new"]);

    $expiry_dates  = $expiry_dates[3];
    $con = 0;
    $arr = array();

    foreach($expiry_dates as $date){
        $arr[$con]=$date["stockexpiry_date"];
        $con++;
    }

    $todays_date = get_todays_date();
    $closestdate =  find_closest_date($arr, $todays_date,"after");
    return $closestdate;
}

function update_object($tb,$col,$val,$qcol,$qval){
    //        sleep(2);
    $user = DbHandler::update_cmd([
        "table" => $tb,
        "col" => $col,
        "val" => $val,
        "cond" => ["="],
        "qcol" => $qcol,
        "qval" => $qval,
        "conj" => ["AND"]
    ]);
    return $user;
}

function sum_product_stock($pro,$closestdate, $stock_edit = "true"){
    $product_stocks = select_object("stock",["productname"],[$pro]);

    $total_product_stocks = 0;

    foreach($product_stocks[3] as $stock){
        //echo $s["stockremain"];
        $total_product_stocks += intval($stock["stockremain"]);
    }

    $user = $stock_edit ? update_object("products", ["stock", "expiry_date"], [$total_product_stocks, $closestdate], ["product_name"], [$pro]) : update_object("products", ["stock"], [$total_product_stocks], ["product_name"], [$pro]);
}

function delete_stock($arr){
    $qval = $arr[0];
    $product_stocks = select_object("stock",["id"],[$qval]);
    $product_name = $product_stocks[3][0]["productname"];
    $expiry_date = $product_stocks[3][0]["expirydate"];

    $users = delete_object("stock", ["id"], [$qval]);

    $closest_expiry_date = find_closest_expiry_date($product_name);

    $this->sum_product_stock($product_name,$closest_expiry_date);
    update_object("stockentry", ["stocktype"], ["old"], ["product","stockexpiry_date"], [$product_name, $expiry_date]); 
}*/


?>