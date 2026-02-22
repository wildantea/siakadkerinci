<?php
include "../../inc/config.php";
switch ($_POST['act']) {
	case 'add':
		if ($_POST['sender']=='mhs') {
			include "konsultasi_add_mhs.php";
		} else {
			include "konsultasi_add_dosen.php";
		}
		break;
	case 'edit':
		if ($_POST['sender']=='mhs') {
			include "konsultasi_edit_mhs.php";
		} else {
			include "konsultasi_edit_dosen.php";
		}
		break;
}
