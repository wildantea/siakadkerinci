<?php

include 'inc/config.php';
$awal = $_GET['awal'];
$akhir = $_GET['akhir'];
$q = $db->query("select nim from mahasiswa  order by nim asc limit $awal,$akhir"); 
$no=1;
foreach ($q as $k) {

	update_akm($k->nim);  
	$no++; 
} 
//update_akm('1810402112');  
echo "$no"; 
   
?>