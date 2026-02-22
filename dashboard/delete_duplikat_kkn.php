<?php
echo "<pre>";
include 'inc/config.php';
$q = $db->query("select kode_mk,count(*) as jml,nim,nilai_huruf ,kode_mk
from krs_detail where id_semester='20231' group by nim,kode_mk
having count(*)>1  limit 1 "); 
foreach ($q as $k) {
	//echo "==================";
	//$limit = ($k->jml-1);  
	$qn = $db->query("select id_krs_detail,nilai_huruf from krs_detail where kode_mk='$k->kode_mk'  and nim='$k->nim' and id_semester='20231'");   
	foreach ($qn as $kn) {
		print_r($kn); 
	 // $db->query("delete from krs_detail where id_krs_detail='$kn->id_krs_detail' ");  
	}
	     
}  
?> 