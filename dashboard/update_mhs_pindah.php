<?php

include "inc/config.php";
$q = $db->query("select nim_lama,nim_baru from mhs_pindah where jenis_pindah='internal' ");
foreach ($q as $k) {
	  $db->query("update sys_users set username='$k->nim_baru' where username='$k->nim_lama' "); 
}

?>