<?php
include 'inc/config.php';
$q = $db->query("SELECT mhs_nim,ipk_last,total_sks FROM `ipk_20222`");
foreach ($q as $k) {
	print_r($k);
	$db->query("update akm set ipk='$k->ipk_last',total_sks='$k->total_sks' where mhs_nim='$k->mhs_nim' and sem_id='20222' ");
}
   
?> 