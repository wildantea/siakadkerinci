<?php
session_start();
include "../../inc/config.php";
session_check_json();
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.* from view_simple_mhs 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_data']));
$check_jadwal = $db2->fetchCustomSingle("select id_jadwal_ujian from tb_data_pendaftaran_jadwal_ujian where id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_data']));

$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "kode_jur in(0)";
}

if ($check_jadwal==false) {
	include "jadwal_sidang_add.php";
} else {
	include "jadwal_sidang_edit.php";
}