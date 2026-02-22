<?php
session_start();
include "../../inc/config.php";
session_check_json();
$term = $_GET['term'];
$data = array();
// buat database dan table provinsi
$query = $db2->query("select mhs_id,nim,nama,nama_jurusan from view_simple_mhs WHERE nim LIKE '%$term%' or nama LIKE '%$term%' LIMIT 5");
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