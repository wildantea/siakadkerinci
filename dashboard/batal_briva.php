<?php
include "inc/config.php";
if ($_POST['password']=='brisecret2025') {
$delete = deleteBriva($_POST['no_pendaftaran']);  
	echo json_encode($delete);
} else {
	echo json_encode(array('status' => 'error'));
}