<?php
session_start();
include "../../inc/config.php";
session_check_json();
$kode_jurusan = $_POST['kode_jurusan'];
$bobot = str_replace(",", ".", $_POST['nilai']);
$berlaku_angkatan = $_POST['angkatan'];
$where_berlaku = "";
if ($berlaku_angkatan >= 20202) {
      $where_berlaku = "and berlaku_angkatan='" . $berlaku_angkatan . "'";
} else {
      $where_berlaku = "and (berlaku_angkatan is null or berlaku_angkatan='')";
}
//echo "select * from skala_nilai where kode_jurusan=? $where_berlaku";
$skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku", array('kode_jurusan' => $kode_jurusan));
if (strlen($bobot) > 0) {
      //echo "select * from skala_nilai where kode_jurusan=? $where_berlaku"; 
      if ($skala_nilai->rowCount() > 0) {
            foreach ($skala_nilai as $skala) {
                  $min = $skala->bobot_nilai_min;
                  $max = $skala->bobot_nilai_maks;
                  if ($bobot >= $min && $bobot <= $max) {
                        echo "<option value='" . $skala->nilai_huruf . "#" . $skala->nilai_indeks . "' selected>" . $skala->nilai_huruf . " (" . $skala->nilai_indeks . ")" . "</option>";
                  }/* else {
                      echo "<option value='".$skala->nilai_huruf."#".$skala->nilai_indeks."'>".$skala->nilai_huruf." (".$skala->nilai_indeks.")"."</option>";
                }*/

            }
      } else {
            echo "<option value='' selected>Skala Nilai Belum dibuat,Hubungi Admin</option>";
      }
} else {
      echo "<option value='' selected></option>";
}


?>