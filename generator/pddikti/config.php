<?php
date_default_timezone_set('Asia/Jakarta');
ini_set("display_errors", true);

$host = "localhost";
$port = 3306;
$db_username = "u5116884";
$db_password = "siakad1414krc";
$db_name = "siakad"; 

//main directory
define("DIR_MAIN", "/generator");

//admin directory
define("DIR_ADMIN", "generator/backend");

define('DB_CHARACSET', 'utf8');

define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']."/".DIR_MAIN);

define('DIR_API', 'api');

//languange
$language  = "en";

include "Database.php";

$db=new Database($host, $port, $db_username, $db_password, $db_name);
function handleException($exception)
{
    echo  $exception->getMessage();
}

set_exception_handler('handleException');