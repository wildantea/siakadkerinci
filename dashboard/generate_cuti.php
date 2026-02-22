<?php
include "inc/config.php";
$q = $db->query("SELECT *
FROM `v_mhs_aktif`
WHERE `mulai_smt` >= '20141'
and nim not in (SELECT nim
FROM `v_bayar_mhs`
WHERE `tgl_bayar` LIKE '%2022%' AND `periode` = '20212')
and nim not like '%.%'
and nim in (select nim from keu_tagihan_mahasiswa) order by mulai_smt asc, nim asc");
foreach ($q as $k) {
	$data = array('nim' => $k->nim, 
                  'status_acc' => 'approved',
                  'alasan_cuti' => 'Telat bayar SPP 2021 Genap',
                  'no_surat' => '-',
                  'date_created' => date("Y-m-d H:i:s"),
                  'date_approved' => date("Y-m-d H:i:s"),
                  'is_generate' => '1');
	$db->insert("tb_data_cuti_mahasiswa",$data); 
	$id_cuti = $db->last_insert_id();
	$data2   = array('id_cuti' => $id_cuti , 
                   'periode' => '20212',
                   'is_generate' => '1');
	$db->insert("tb_data_cuti_mahasiswa_periode",$data2); 
	$qq = $db->query("select * from akm where mhs_nim='$k->nim' order by sem_id desc limit 1 "); 
	foreach ($qq as $kk) {
		$data_akm = (array)$kk;
        unset($data_akm['akm_id']);
        $data_akm['sem_id'] = '20212';
        $data_akm['id_stat_mhs'] = 'C';
        $data_akm['ip'] = '0.00';
        $data_akm['jatah_sks'] = '0';
        $data_akm['sks_diambil'] = '0';
        $data_akm['is_generate'] = '1'; 
        $db->insert("akm",$data_akm);     
	}
}
?>