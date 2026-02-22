<?php
if ($_POST['jenis_print']=='kelas') {
	include "cetak_presensi_kelas_single.php";
} elseif ($_POST['jenis_print']=='uts') {
	include "cetak_presensi_uts_single.php";
} elseif ($_POST['jenis_print']=='uas') {
	include "cetak_presensi_uas_single.php";
}
?>