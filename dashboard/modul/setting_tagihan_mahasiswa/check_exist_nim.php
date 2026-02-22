<?php
session_start();
include "../../inc/config.php";
session_check_json();
$check = $db->check_exist('mahasiswa',array('nim' => $_POST['nim']));
if ($check) {
    echo "true";
} else {
    echo "false";
}
?>