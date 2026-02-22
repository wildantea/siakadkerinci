<?php
session_start();
include "../../inc/config.php";
session_check_json();
$kode_jurusan=$_POST['kode_jurusan'];
$bobot=$_POST['nilai'];
$berlaku_angkatan=$_POST['angkatan'];
$where_berlaku = "";
if ($berlaku_angkatan>=20202) {
      $where_berlaku = "and berlaku_angkatan='".$berlaku_angkatan."'"; 
} else{
      $where_berlaku = "and berlaku_angkatan is null"; 
}
//echo "select * from skala_nilai where kode_jurusan=? $where_berlaku";
$skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku and $bobot >= bobot_nilai_min and $bobot <= bobot_nilai_maks",array('kode_jurusan' => $kode_jurusan));
//echo "select * from skala_nilai where kode_jurusan=? $where_berlaku"; 
       if ($skala_nilai->rowCount()>0) {
            foreach ($skala_nilai as $skala) {
                echo $skala->nilai_huruf;
            }
       } else {
       echo "Skala Nilai Belum dibuat,Hubungi Admin";
       }

?>