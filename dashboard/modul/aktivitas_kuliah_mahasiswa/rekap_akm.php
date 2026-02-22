<?php
session_start();
include "../../inc/config.php";
 
$json_response = array();
$nim = "";

if ($_POST['nim_rekap']!='all') {
	//rekap per nim mahasiswa
	if ($_POST['pilih_semester']=='all') {
		//rekap all semester
		include "rekap_akm_single_all_semester.php";

	} else {
		include "rekap_akm_semester.php";

	}
} else {
	include "rekap_akm_semester.php";
}


array_push($json_response, $jumlah);
echo json_encode($json_response);
?>