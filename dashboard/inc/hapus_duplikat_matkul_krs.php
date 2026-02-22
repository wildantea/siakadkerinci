<?php
include 'config.php';
// $q = $db->query("select kode_jurusan,jurusan from matkul_old group by kode_jurusan");
// foreach ($q as $k) {
// 	$data = array('kode_jur' => $k->kode_jurusan ,
// 	'sem_id' => '20101',
// 	'nama_kurikulum' => $k->jurusan." simak lama" );
// 	$db->insert("kurikulum",$data); 
// }
$time_start = microtime(true); 



echo "<pre>";
$q = $db->query("SELECT mhs_nim,sem_id FROM `akm` where sks_diambil>24    ");      
foreach ($q as $k) {  
	 
	$qq = $db->query("SELECT k.id_krs_detail FROM `krs_detail` k join matkul m on m.id_matkul=k.kode_mk WHERE k.`nim` = '$k->mhs_nim' AND k.`id_semester` = '$k->sem_id' group by m.nama_mk having count(m.nama_mk)>1");  
	foreach ($qq as $kk) { 
	//print_r($k);   
		//if ($kk->id_krs_detail!=$k->id_krs_detail) {  
			//print_r($kk);
			$db->query("delete from krs_detail where id_krs_detail='$kk->id_krs_detail' ");  
		//}
	}    

	//$db->query("update matkul set kur_id='$k->kur_id' where id_matkul='$k->id_simak_baru' ");   
} 

$time_end = microtime(true);  

//dividing with 60 will give the execution time in minutes otherwise seconds
$execution_time = ($time_end - $time_start)/60;
echo "waktu eksekusi $execution_time"; 
?>