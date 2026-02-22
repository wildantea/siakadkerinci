<?php
include "inc/config.php";

$tgl_awal = $_POST['awal'];
$tgl_akhir = $_POST['akhir'];


echo json_encode(get_report_briva($tgl_awal,$tgl_akhir));




