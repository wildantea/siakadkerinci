<?php
session_start();
include "../../inc/config.php";
$nim = $_POST['nim'];
$semester = $_POST['periode'];
//check pembayaran
$bayar = $db->fetch_custom_single("select fungsi_cek_pembayaran_periode(".$semester.",mahasiswa.jur_kode,mahasiswa.nim) as is_bayar from mahasiswa where nim=?",array('nim' => $nim)
);
//check in affirmasi
$cek_affirmasi = $db->check_exist('affirmasi_krs',array('nim' => $nim,'periode' => $semester));

$boleh_krs = true;
if ($bayar->is_bayar=='0') {
	$boleh_krs = false;
	if ($cek_affirmasi==true) {
		$boleh_krs = true;
	}
}
//var_dump($boleh_krs);
if ($boleh_krs==true) {
	action_response('',array('url' => $enc->enc($nim)."&s=".$enc->enc($semester)));
} else {
	if (!isSuperAdmin()) {
		action_response('Mahasiswa ini belum melakukan Pembayaran');
	} else {
		action_response('',array('url' => $enc->enc($nim)."&s=".$enc->enc($semester)));
	}
}