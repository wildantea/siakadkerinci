<?php

include 'inc/config.php';

$q = $db->query("select a.sem_id, m.nama,m.nim,j.nama_jur, a.jatah_sks,a.sks_diambil from akm a
join mahasiswa m on m.nim=a.mhs_nim
join jurusan j on j.kode_jur=m.jur_kode
where a.sks_diambil>a.jatah_sks and a.sem_id='20211'  ");
$no=1;
foreach ($q as $k) {

	$db->query("delete from krs_detail where nim='$k->nim' and id_semester='$k->sem_id' ");
	update_akm($k->nim);  
	$no++;
} 
echo "$no";
 
?> 