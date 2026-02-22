<?php
include 'inc/config.php';
// $no_briva = $_GET['no_briva'];
// $status = $_GET['status']; 
 //$res = updateStatusBayar($no_briva,$status); 
// $res2 = getStatusBriva($no_briva);
 $q = $db->query("SELECT count(id) as jml,id_keu_tagihan_mhs 
FROM `keu_bayar_mahasiswa` WHERE created_by='API BRIVA'
group by id_keu_tagihan_mhs having count(id)>1 ");   
 foreach ($q as $k) {
 	 $limit = $k->jml - 1;
 	 $qq = $db->query("select id from keu_bayar_mahasiswa where id_keu_tagihan_mhs='$k->id_keu_tagihan_mhs' limit $limit ");
 	 foreach ($qq as $kk) {
 	 	//echo "delete from keu_bayar_mahasiswa where id='$kk->id' <br>";
 	 	 $db->query("delete from keu_bayar_mahasiswa where id='$kk->id' "); 
 	 }
 }

?> 