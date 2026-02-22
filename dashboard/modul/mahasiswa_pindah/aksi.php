<?php
session_start();
include "../../inc/config.php";
session_check_end();
if ($_POST['jenis']=='download') {
	include "download_nilai_transfer.php";
} elseif ($_POST['jenis']=='cetak') {
	include "cetak_akun.php";
}
?>