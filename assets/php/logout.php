<?php
ob_start();
require_once "./includes/start_session.php";
require_once "./includes/functions.php";

$_SESSION = array();

if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), ' ', time()-42000, '/');
}

session_destroy();

custom_redirect_to("../../signIn.php");
    ob_end_flush();
?>
