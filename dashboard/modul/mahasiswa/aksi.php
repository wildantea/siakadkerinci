<?php
session_start();
include "../../assets/plugins/html2pdf/html2pdf.class.php";
include "../../inc/config.php";
session_check_end();
if ($_POST['jenis']=='download') {
	include "download_data.php";
} elseif ($_POST['jenis']=='cetak') {
	include "cetak_akun.php";
}
?>