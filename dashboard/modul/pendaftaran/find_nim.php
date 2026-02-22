<?php
session_start();
include "../../inc/config.php";
session_check_json();
$term = $_GET['term'];
$data = array();

$jur_kode = aksesProdi('view_simple_mhs_data.jur_kode');


// buat database dan table provinsi
$query = $db2->query("select * from view_simple_mhs_data WHERE (nim LIKE '%$term%' or nama LIKE '%$term%') $jur_kode LIMIT 5");
foreach ($query as $dt) {
	      $data[] = array(
	 	                 'jurusan' => $dt->jurusan,
	 	                 'nama'    => $dt->nama,
	 	                 'kode_jur' => $dt->jur_kode,
	 	                 'label'   => $dt->nim.' '.$dt->nama.' ('.$dt->jurusan.')',
	 	                 'value'   => $dt->nim
	 	                 );
}
echo json_encode($data);
?>