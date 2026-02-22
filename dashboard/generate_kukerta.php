<?php

include 'inc/config.php';

$q = $db->query("select nim,id_priode from kkn where id_priode='8'");
$no=1;
echo "<pre>";
foreach ($q as $k) {
	$qq = $db->query("select d.id_semester,d.nim, id_krs_detail,m.nama_mk from krs_detail d join matkul m on m.id_matkul=d.kode_mk  where d.nim='$k->nim' 
 and (m.nama_mk like '%kuker%' or m.nama_mk like '%kkn%' or m.nama_mk like '%Kuliah Ker%') and d.id_semester='20212'  ");
	foreach ($qq as $kk) {
		$db->query("update krs_detail set id_semester='20221' where id_krs_detail='$kk->id_krs_detail' "); 
		//print_r($kk); 
	}
	$no++;
}
//echo "$no";
 
?>