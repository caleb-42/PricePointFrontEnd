<?php

class DbHandler{
    const HOST = "localhost";
    /*const DB = "webplayg_mgxdb";
    const USER = "webplayg_root";
    const PASS = "webplay";*/
    const DB = "pricepoint";
    const USER = "root";
    const PASS = "ewere";
    public static $con;

    
    static function makeConnection($data){
        if(!DbHandler::$con){
            if(array_key_exists('db', $data)){
                $db = $data['db'];
                DbHandler::$con =  new mysqli(DbHandler::HOST, DbHandler::USER, DbHandler::PASS, $db);
            }else{
                DbHandler::$con = new mysqli(DbHandler::HOST, DbHandler::USER, DbHandler::PASS, DbHandler::DB);
            }
            if(!DbHandler::$con) {
                $assoc = array('0' => 'output', '1' => 'error', '2' => 'connection unsuccessful');
                return $assoc;
                exit();
            }
        }
        return DbHandler::$con;
    }

    static function checkkeys($key, $data){
       
        switch ($key){
            case "table":
                if(array_key_exists('table', $data)){
                    return $data;
                }else{
                    return false;
                }
            break;
            case "col":
                if(array_key_exists('col', $data) && is_array($data['col'])){
                    
                    return $data;
                    
                }else{
                    return false;
                }
            break;
            case "val":
                if(array_key_exists('val', $data) && is_array($data['val'])){
                    
                    if(count($data['col']) == 1 && count($data['val']) > 1){
                        for($i = 1; $i < count($data['val']); $i++){
                            $data['col'][$i] = $data['col'][0];
                        }
                    }
                    return $data;
                    
                }else{
                    return false;
                }
            break;
            case "qcol":
                if(array_key_exists('qcol', $data) && array_key_exists('qval', $data) && 
                   is_array($data['qcol']) && is_array($data['qval'])){
                    
                    if(count($data['qcol']) == 1 && count($data['qval']) > 1){
                        for($i = 1; $i < count($data['qval']); $i++){
                            $data['qcol'][$i] = $data['qcol'][0];
                        }
                    }
                    if(count($data['qcol']) ==  count($data['qval'])){
                        return $data;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            break;
            case "conj":
                if(array_key_exists('conj', $data) && is_array($data['conj'])){
                    if(count($data['conj']) == 1 && count($data['qval']) > 2){
                        for($i = 1; $i < count($data['qval'])-1; $i++){
                            $data['conj'][$i] = $data['conj'][0];
                        }
                    }
                    return $data;
                }else{
                    return false;
                }
            break;
            case "cond":
                if(array_key_exists('cond', $data) && is_array($data['cond'])){
                    if(count($data['cond']) == 1 && count($data['qval']) > 1){
                        for($i = 1; $i < count($data['qval']); $i++){
                            $data['cond'][$i] = $data['cond'][0];
                        }
                    }
                    return $data;
                }else{
                    return false;
                }
                
            break;
        }
    }

    /*if(mysqli_num_rows($result) > 0){*/
    static function select_cmd($data = array()){
        if(is_array($data)){
            $con = DbHandler::makeConnection($data);

            
            $adjdata = array();
            $query = "SELECT";
            $adjdata = DbHandler::checkkeys('table', $data);
            if($adjdata){ //table keyword was used
                $mdata = DbHandler::checkkeys('col', $data);
                if($mdata){//col keyword was used
                    $adjdata = $mdata;
                        for($col = 0; $col < count($data['col']); $col++ ){
                            if($col == 0){
                                $query .= " {$data['col'][$col]}";
                            }else{
                                $query .= ", {$data['col'][$col]}";
                            }
                            
                        }//fill column names into query
                }else{//col keyword was not used
                        $query .= " *";
                }
                $query .= " FROM {$data['table']} ";
            }else{//table keyword does not exist
                
            }
            
            $mdata = DbHandler::checkkeys("qcol", $data);
            if($mdata){//where columns exist
                $adjdata = $mdata;
                $adjdata = DbHandler::checkkeys("cond", $adjdata);
                if($adjdata){//where conditions exist
                    $query.= "WHERE ";
                    $wcol = $adjdata["qcol"];
                    $wval = [];
                    foreach($adjdata["qval"] as $val){
                        $wval[] = DbHandler::custom_mysql_prep($con, $val);
                    }

                    $wcon = $adjdata["cond"];
                
                    $query.= "{$wcol[0]} {$wcon[0]} '{$wval[0]}' ";
                    
                    $mdata = DbHandler::checkkeys("conj", $adjdata);
                    $wcoj = $mdata["conj"];
                    if($wcoj){//if where conjunction adjust where conjuction array
                        for($col = 1; $col < count($wcol); $col++){
                            $query.= "{$wcoj[$col - 1]} {$wcol[$col]} {$wcon[$col]} '{$wval[$col]}' ";
                           
                        }
                        $adjdata = $mdata;
                    }
                }
            }else{//no where columns or conditions
                        
            }

            //echo $query;
            if($adjdata){
                $assoc = array('0' => 'output', '1' => 'success', '2' => 'values have been inserted');
            return DbHandler::runQuery($con,$query, $adjdata['table'], $assoc);
            }
            /*$assoc = array('0' => 'error', '1' => $query);
            return $assoc;*/
                        //DbHandler::getArrayCol($con,$data['table']);
                        //return DbHandler::runQuery($con,$query, $data['table']);
            
            
        }else{

        }
    }

    static function update_cmd($data = array()){
        if(is_array($data)){
            
            $con = DbHandler::makeConnection($data);

            if(array_key_exists('table', $data)){
                $query = "UPDATE {$data['table']} SET ";
            }
            if(array_key_exists('col', $data) && array_key_exists('val', $data) && array_key_exists('qcol', $data) && array_key_exists('qval', $data)){

                if(array_key_exists('conj', $data)){
                    if(count($data['conj']) == 1 && count($data['qval']) > 1){
                        for($i = 1; $i < count($data['qval'])-1; $i++){
                            $data['conj'][$i] = $data['conj'][0];
                        }
                    }
                }
                if(is_array($data['col']) && is_array($data['val']) && count($data['col']) ==  count($data['val']) &&is_array($data['qcol']) && is_array($data['qval']) && count($data['qcol']) ==  count($data['qval'])){
                    /*$where_arr = $data['where'];*/
                    $columns = $data['col'];
                    
                    $values = [];
                    foreach($data['val'] as $val){
                        $values[] = DbHandler::custom_mysql_prep($con, $val);
                    }

                    $wherval = [];
                    foreach($data['qval'] as $val){
                        $wherval[] = DbHandler::custom_mysql_prep($con, $val);
                    }

                    $whercol= $data['qcol'];
                    if(array_key_exists('conj', $data)){
                        $conj = $data['conj'];
                    }

                    $query.= $columns[0] . ' = ' . "'". $values[0] . "'";
                    for($col = 1; $col < count($columns); $col++){
                        $query.= ", " . $columns[$col] . ' = ' . "'". $values[$col] . "'";
                    }

                    $query .= " WHERE (";
                    $query.= $whercol[0] . ' = ' . "'". $wherval[0] . "'";
                    if(array_key_exists('conj', $data)){
                        for($col = 1; $col < count($whercol); $col++){
                            $query.= " " . $conj[$col-1] . " " . $whercol[$col] . ' = ' . "'". $wherval[$col] . "'";
                        }
                    }
                    $query .= ");";
                        //echo $query;
                    $assoc = array('0' => 'output', '1' => 'success', '2' => 'values have been inserted');
                    return DbHandler::runInputQuery($con,$query,$assoc);
                    }else{

                    }
            }else{
                //return DbHandler::runQuery($con,$query);
            }
        }
    }


    static function insert_cmd($data = array()){
        if(is_array($data)){
            
            $con = DbHandler::makeConnection($data);

            if(array_key_exists('table', $data)){
                $query = "INSERT INTO {$data['table']} ";
            }else{
                $assoc = array('0' => 'output', '1' => 'error', '2' => 'no table has been entered');
                return $assoc;
            }
            if(array_key_exists('col', $data) && array_key_exists('val', $data)){

                $value = [];
                foreach($data['val'] as $val){
                    $value[] = DbHandler::custom_mysql_prep($con, $val);
                }

                if(is_array($data['col']) && is_array($data['val']) && count($data['col']) ==  count($data['val'])){
                    /*$where_arr = $data['where'];*/
                    $data['col'];

                    if(count($data['col']) > 1){
                        $query.= "(". implode(",", $data['col']) . ") VALUES ('" . implode("', '", $value) . "');";
                    }else{
                        $query.= "(". $data['col'][0] . ") VALUES ('" . $value[0] . "');";
                    }
                    //$assoc = array('0' => 'output', '1' => $query);
                    $assoc = array('0' => 'output', '1' => 'success', '2' => 'values have been inserted');
                    return DbHandler::runInputQuery($con,$query,$assoc);
                    //return $assoc;
                }else if(count($data['col']) == 1 && is_array($data['col'])  && $data['col'][0] == 'default' && is_array($data['val'])){
                    $query.= " VALUES ('" . implode("', '", $value) . "');";
                    //$assoc = array('0' => 'output', '1' => $query);
                    $assoc = array('0' => 'output', '1' => 'success', '2' => 'values have been inserted');
                    return DbHandler::runInputQuery($con,$query,$assoc);
                    //return $assoc;
                }else{
                    $assoc = array('0' => 'output', '1' => 'error', '2' => 'col or val parameters either have wrong datatype or values ');
                    return $assoc;
                }

            }else{
                $assoc = array('0' => 'output', '1' => 'error', '2' => 'database col  parameter is missing or no values have been entered');
                return $assoc;
            }
        }
    }

    
    static function delete_cmd($data = array()){
        if(is_array($data)){

            $adjdata = array();
            $con = DbHandler::makeConnection($data);

            if(DbHandler::checkkeys('table', $data)){ //table keyword was used
                $query = "DELETE FROM {$data['table']} ";

                $adjdata = DbHandler::checkkeys('qcol', $data);
                if($adjdata){//col keyword was used$adjdata = DbHandler::checkkeys('qcol', $data);
                    $adjdata = DbHandler::checkkeys('cond', $adjdata);
                    if($adjdata){//col keyword was used
                    
                    $wcol = $adjdata['qcol'];
                    $wval = [];

                    
                    foreach($adjdata['qval'] as $val){
                        $wval[] = DbHandler::custom_mysql_prep($con, $val);
                    }

                    $wcon = $adjdata['cond'];
                        
                        $query .= "WHERE {$wcol[0]} {$wcon[0]} '{$wval[0]}'";

                        $mdata = DbHandler::checkkeys("conj", $adjdata);
                        if($mdata){//if where value adjustments return true
                        
                            $wcoj = $mdata['conj'];
                        
                            for($num = 1; $num < count($wcol); $num++){
                                $query.= " {$wcoj[$num - 1]} {$wcol[$num]} {$wcon[$num]} '{$wval[0]}'";
                            }
                            $adjdata = $mdata;
                        }
                        //echo $query;
                        return DbHandler::runQuery($con ,$query, $adjdata);
                    }
                }
            }
        }
    }

    static function runQuery($con, $query, $table, $msg){
        //$query =  DbHandler::custom_mysql_prep($con,$query);
        //echo $query;
        $result_set = mysqli_query($con, $query);
        //return $result_set;
        if($result_set){
            $data = DbHandler::getArray($result_set,$con,$table);
            //print_r($data);
            $assoc = array('0' => 'output', '1' => 'success',  '2' => 'values have been selected', '3' => $data);
            return $assoc;
        }else{
            $assoc = array('0' => 'output', '1' => 'error', '2' => 'sql operation was not carried out');
            return $assoc;
        }
    }
    static function runInputQuery($con, $query, $msg){
        //echo $query;
        $result_set = mysqli_query($con, $query);
        if($result_set){
            return $msg;
        }else{
            $assoc = array('0' => 'output', '1' => 'error', '2' => 'query failed within connection');
            return $assoc;
        }
    }

    static function getArray($result_set, $con, $table){
        $data = array();
        if(mysqli_num_rows($result_set) > 0){
            while($row = mysqli_fetch_array($result_set)){
                array_push($data, DbHandler::getArrayCol($con, $table, $row));
            }
            //print_r($data);
        }
        return $data;
    }
    static function getArrayCol($con,$table,$row){
        $result_set = mysqli_query($con, "SHOW COLUMNS FROM {$table}");
        $data = array();
        //echo "here";
        while($col = mysqli_fetch_array($result_set)){
            //echo($row[0]);
            $data[$col[0]] = $row[$col[0]];
        }
        return $data;
    }

    static function custom_mysql_prep($con, $value ) {
        $magic_quotes_active = get_magic_quotes_gpc();
        $new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
        if( $new_enough_php ) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if( $magic_quotes_active ) { $value = stripslashes( $value ); }
            $value = mysqli_real_escape_string($con,  $value );
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if( !$magic_quotes_active ) { $value = addslashes( $value ); }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;
    }
}

?>
