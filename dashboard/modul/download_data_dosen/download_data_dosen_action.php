<?php
session_start();
include "../../inc/config.php";
//session_check();
switch ($_GET["act"]) {
   case 'delete_download':
  //echo $_POST['uri'];
  if (file_exists($_POST['uri'])) {
    unlink($_POST['uri']);
  }
  if (file_exists($_POST['file_data'])) {
    unlink($_POST['file_data']);
  }
}

?>