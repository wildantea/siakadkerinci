<?php
session_start();
include "../../inc/config.php";
session_check_json();
$term = $_GET['term'];
$data = array();
// buat database dan table provinsi
$query = $db2->query("select mhs_id,mahasiswa.nim,mahasiswa.nama,view_prodi_jenjang.jurusan as nama_jurusan
from mahasiswa 
inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur
WHERE nim LIKE '%$term%' or nama LIKE '%$term%' LIMIT 5");
foreach ($query as $dt) {
	      $data[] = array(
	 	                 'jurusan' => $dt->nama_jurusan,
	 	                 'nama'    => $dt->nama,
	 	                 'label'   => $dt->nim.' '.$dt->nama.' ('.$dt->nama_jurusan.')',
	 	                 'value'   => $dt->nim
	 	                 );
}
echo json_encode($data);
?>