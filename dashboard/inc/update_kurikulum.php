<?php
include 'config.php';
// $q = $db->query("select kode_jurusan,jurusan from matkul_old group by kode_jurusan");
// foreach ($q as $k) {
// 	$data = array('kode_jur' => $k->kode_jurusan ,
// 	'sem_id' => '20101',
// 	'nama_kurikulum' => $k->jurusan." simak lama" );
// 	$db->insert("kurikulum",$data); 
// }
$q = $db->query("select id_simak_baru, kode_jurusan,
(select kur_id from kurikulum where kode_jur=o.kode_jurusan and 
nama_kurikulum like '%simak lama%' limit 1 ) as kur_id
 from matkul_old o");
foreach ($q as $k) {

	$db->query("update matkul set kur_id='$k->kur_id' where id_matkul='$k->id_simak_baru' "); 
	} 
?>