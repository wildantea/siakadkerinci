<?php
session_start();
include "../../inc/config.php";
session_check_json();
$jurusan_kode = $_REQUEST['kode_jur'];
if (isset($_REQUEST['q'])) {
$nama_mk = $_REQUEST['q'];
	if (strlen($nama_mk)>1) {
		$matakuliah = $db2->query("select id_matkul,nama_mk,kode_mk,nama_kurikulum from matkul inner join kurikulum using(kur_id) where kode_jur='$jurusan_kode' and (nama_mk like '%$nama_mk%' OR kode_mk like '%$nama_mk%') ORDER BY tahun_mulai_berlaku desc");
	} else {
		$matakuliah = $db2->query("select id_matkul,nama_mk,kode_mk,nama_kurikulum from matkul inner join kurikulum using(kur_id) where kode_jur='$jurusan_kode'  ORDER BY tahun_mulai_berlaku desc limit 5");
	}
		$results['results'] = array();
		foreach ($matakuliah as $matkul) {
			$array_push = array(
				'id' => $matkul->id_matkul,
				'text' => $matkul->nama_kurikulum.' - '.$matkul->kode_mk.' '.$matkul->nama_mk
			);
			$results['results'][] = $array_push;
		}
		echo json_encode($results);
	
} else {
	$matakuliah = $db2->query("select id_matkul,nama_mk,kode_mk,nama_kurikulum from matkul inner join kurikulum using(kur_id) where kode_jur='$jurusan_kode'  ORDER BY tahun_mulai_berlaku desc limit 5");
	$results['results'] = array();
	foreach ($matakuliah as $matkul) {
		$array_push = array(
			'id' => $matkul->id_matkul,
			'text' => $matkul->nama_kurikulum.' - '.$matkul->kode_mk.' '.$matkul->nama_mk
		);
		$results['results'][] = $array_push;
	}
	echo json_encode($results);
}


?>