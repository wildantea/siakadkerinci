<?php
//print_r($_POST);exit();
if ($_POST['jenis_print']=='kelas') {
	include "cetak_presensi_kelas.php";
} elseif ($_POST['jenis_print']=='uts') {
	include "cetak_presensi_uts.php";
} elseif ($_POST['jenis_print']=='uas') {
	include "cetak_presensi_uas.php";
} 
elseif ($_POST['jenis_print']=='jadwal') {
	include "cetak_jadwal.php";
} 
?>