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
$q = $db->query("SELECT * FROM `vduplikat`  ORDER BY `c` DESC");   
foreach ($q as $k) {  
	// $db->query("drop view vduplikat_detail");
	// $db->query("create view vduplikat_detail as 
	// 	select `krs_detail`.`id_krs_detail` AS `id_krs_detail`,`krs_detail`.`id_krs` AS `id_krs`,`krs_detail`.`kode_mk` AS `kode_mk`,`krs_detail`.`id_kelas` AS `id_kelas`,`krs_detail`.`nim` AS `nim`,`krs_detail`.`id_semester` AS `id_semester`,`krs_detail`.`disetujui` AS `disetujui`,`krs_detail`.`batal` AS `batal`,`krs_detail`.`sks` AS `sks`,`krs_detail`.`presensi` AS `presensi`,`krs_detail`.`mandiri` AS `mandiri`,`krs_detail`.`terstruktur` AS `terstruktur`,`krs_detail`.`lain_lain` AS `lain_lain`,`krs_detail`.`uts` AS `uts`,`krs_detail`.`uas` AS `uas`,`krs_detail`.`bobot` AS `bobot`,`krs_detail`.`nilai_huruf` AS `nilai_huruf`,`krs_detail`.`nilai_angka` AS `nilai_angka`,`krs_detail`.`tgl_perubahan` AS `tgl_perubahan`,`krs_detail`.`tgl_perubahan_nilai` AS `tgl_perubahan_nilai`,`krs_detail`.`pengubah` AS `pengubah`,`krs_detail`.`pengubah_nilai` AS `pengubah_nilai`,`krs_detail`.`mk_disetarakan` AS `mk_disetarakan`,`krs_detail`.`use_rule` AS `use_rule`,`krs_detail`.`sdh_dinilai` AS `sdh_dinilai`,`krs_detail`.`simak_lama` AS `simak_lama`,`krs_detail`.`date_created` AS `date_created` from `krs_detail` where `krs_detail`.`id_kelas` = '$k->id_kelas' and `krs_detail`.`id_semester` = '$k->id_semester' and `krs_detail`.`nim` = '$k->nim'");

	// $db->query("delete from krs_detail where id_krs_detail in (select id_krs_detail from vduplikat_detail where id_krs_detail!='$k->id_krs_detail')  "); 
	$qq = $db->query("select id_krs_detail from krs_detail where `krs_detail`.`id_kelas` = '$k->id_kelas' and `krs_detail`.`id_semester` = '$k->id_semester' and `krs_detail`.`nim` = '$k->nim' ");
	foreach ($qq as $kk) { 

		if ($kk->id_krs_detail!=$k->id_krs_detail) {  
			//print_r($kk);
			$db->query("delete from krs_detail where id_krs_detail='$kk->id_krs_detail' "); 
		}
	}

	//$db->query("update matkul set kur_id='$k->kur_id' where id_matkul='$k->id_simak_baru' ");   
} 

$time_end = microtime(true);  

//dividing with 60 will give the execution time in minutes otherwise seconds
$execution_time = ($time_end - $time_start)/60;
echo "waktu eksekusi $execution_time"; 
?>