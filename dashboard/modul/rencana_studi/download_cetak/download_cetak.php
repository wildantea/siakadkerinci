<?php
switch ($_POST['download_cetak']) {
	case 'download_krs':
		include "download_krs.php";
		break;
	case 'download_krs_detail':
		include "download_krs_detail.php";
		break;
	case 'cetak_rekap':
		include "cetak_rekap.php";
		break;
}
?>
