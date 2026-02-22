<?php
switch ($_POST['jenis_print']) {
	case 'kelas_single':
		include "cetak_presensi_kelas_single.php";
		break;
	case 'kelas_massal':
		include "cetak_presensi_kelas_massal.php";
		break;
	case 'uts_single':
		include "cetak_presensi_uts_single.php";
		break;
	case 'uts_massal':
		include "cetak_presensi_uts_massal.php";
		break;
	case 'uas_single':
		include "cetak_presensi_uas_single.php";
		break;
	case 'uas_massal':
		include "cetak_presensi_uas_massal.php";
		break;
	case 'bap':
		include "cetak_bap_single.php";
		break;
	case 'bap_massal':
		include "cetak_bap_massal.php";
		break;
	case 'download_kelas':
		include "../download_kelas.php";
		break;
	case 'download_jadwal':
		include "download_jadwal.php";
		break;
}
?>
