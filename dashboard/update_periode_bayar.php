<?php
include 'inc/config.php';
$q = $db->query("select nim,mulai_smt from mahasiswa where mulai_smt='20211' ");
foreach ($q as $k) {
	//$n = explode('-' , $k->nim);
	//$nim = $n[0].$n[1].$n[2];
	//echo "update mahasiswa set no_pendaftaran='$k->nim', nim=NULL, status='CM' where mhs_id='$k->mhs_id' <br> ";
	$db->query("update keu_tagihan_mahasiswa set periode='$k->mulai_smt' where nim='$k->nim' "); 
} 
?> 