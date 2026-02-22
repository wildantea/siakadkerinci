<?php

include 'inc/config.php';

function get_huruf($nilai)
{
	$res = array();
	if ($nilai>=80) { 
		$res['bobot'] ='4';
		$res['huruf'] = 'A';
	}else if ($nilai>=70 && $nilai<80) {
		$res['bobot'] ='3';
		$res['huruf'] = 'B';
	}else if ($nilai>=60 && $nilai<70) {
		$res['bobot'] ='2'; 
		$res['huruf'] = 'C';
	}else if ($nilai>=55 && $nilai<60) {
		$res['bobot'] ='1'; 
		$res['huruf'] = 'D';
	}else if ($nilai<55) { 
		$res['bobot'] ='0'; 
		$res['huruf'] = 'E';  
	}else
	 {
		$res['bobot'] = NULL;
		$res['huruf'] = NULL; 
	}
	return $res;
}
$awal = $_GET['awal'];
$akhir = $_GET['akhir']; 

$q = $db->query("select id_krs_detail,nilai_angka from krs_detail 
where simak_lama='1'  limit $awal,$akhir  ");    
$no=1;
foreach ($q as $k) {
	$nilai = get_huruf($k->nilai_angka);
	$db->query("update krs_detail set bobot='".$nilai['bobot']."',
	nilai_huruf='".$nilai['huruf']."' where id_krs_detail='$k->id_krs_detail' "); 
	$no++;
} 
echo "$no";
?>