<?php
session_start();
include "../../inc/config.php";
session_check_json();
$term = $_GET['term'];
$data = array();
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
// buat database dan table provinsi
$query = $db->query("select jur_kode kode_jur, mahasiswa.mhs_id,nim,nama,view_prodi_jenjang.jurusan from mahasiswa inner join view_prodi_jenjang
on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur $akses_prodi and (nim LIKE '%$term%' or nama LIKE '%$term%') LIMIT 5"); 
foreach ($query as $dt) {
	 $data[] = array('jurusan'=>$dt->jurusan,'nama' => $dt->nama,'label'=>$dt->nim.' '.$dt->nama.' ('.$dt->jurusan.')','value'=>$dt->nim);
}
echo json_encode($data);
?>