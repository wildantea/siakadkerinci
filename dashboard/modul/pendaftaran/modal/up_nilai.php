<?php
session_start();
include "../../../inc/config.php";
session_check();

$exp_nilai = explode("#", $_POST['nilai_huruf']);
$data_update_nilai = array(
    'nilai_angka' => $_POST['nilai_angka'],
    'nilai_huruf' => $exp_nilai[0],
    'bobot' => $exp_nilai[1],
    'tgl_perubahan_nilai' => date('Y-m-d H:i:s'),
    'pengubah_nilai' =>  $_SESSION['nama']
);

/*$data_update_nilai = array(
    'nilai_angka' => '',
    'nilai_huruf' => '',
    'bobot' => '',
    'tgl_perubahan_nilai' => '',
    'pengubah_nilai' =>  '',
);
*/
//dump($data_update_nilai);
$db->update('tb_data_pendaftaran',array('has_nilai' => 'N'),'id_pendaftaran',$_POST['id_pendaftaran']);

$db->update('krs_detail',$data_update_nilai,'id_krs_detail',$_POST['id']);
$nim = $db->fetch_single_row("krs_detail","id_krs_detail",$_POST['id']);
update_akm($nim->nim);
action_response($db2->getErrorMessage());