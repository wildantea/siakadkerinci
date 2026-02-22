<?php
include 'config.php';

$semester= $_GET['semester'];
$nip = $_GET['nip'];
$res = array();
$q = $db->query("select m.nama_mk,m.bobot_minimal_lulus as sks,m.kode_mk,k.kls_nama as nama_kelas,
j.nama_jur as nama_jurusan,
(select count(id_dosen) from dosen_kelas where id_kelas=dk.id_kelas)  as jumlah_dosen,
(select count(id_krs_detail) from krs_detail where id_kelas=dk.id_kelas
and disetujui='1' ) as jml_mhs,j.id_jenjang as jenjang
from dosen_kelas dk join kelas k on k.kelas_id=dk.id_kelas
join matkul m on m.id_matkul=k.id_matkul 
join kurikulum ku on ku.kur_id=m.kur_id
join jurusan j on j.kode_jur=ku.kode_jur
where dk.id_dosen='$nip' and k.sem_id='$semester'");
foreach ($q as $k) {
	if ($k->jenjang=='30') {
		$k->jenjang = '1';
	}elseif ($k->jenjang='2') {
		$k->jenjang=='13';
	}elseif ($k->jenjang='40') {
		$k->jenjang=='3';
	}
	$res['data'][] = $k;
}
echo json_encode($res);
?> 