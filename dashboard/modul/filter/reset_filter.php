<?php
session_start();
include "../../inc/config.php";
$modul_name = $_POST['modul_name'];
//$modul_name = 'filter_matkul';
resetFilter($modul_name);
//action_response($db->getErrorMessage());