<?php
include "inc/config.php";

$check = getStatusBriva($_POST['no_pendaftaran']);


//dump(get_report_briva('20250617','20250617'));


echo json_encode($check);