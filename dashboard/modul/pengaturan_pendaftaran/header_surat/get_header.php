<?php
session_start();
include "../../../inc/config.php";
session_check_json();
$get_header = $db2->fetchSingleRow("tb_data_pendaftaran_header_surat","id_header",$_POST['id']);
echo $get_header->header_surat;