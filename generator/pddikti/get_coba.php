<?php
header("Access-Control-Allow-Origin: *");
include "config.php";

$json_response = array();
$param = array();

$total_kurikulum = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM kurikulum 
 inner JOIN jurusan ON kurikulum.kode_jur=jurusan.kode_jur where 1=1");

print_r($total_kurikulum);