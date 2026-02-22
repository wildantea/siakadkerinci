<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "change_status":

    $data=array(
      'status_ta'   => $_POST['stat'],
      'updated_at'  => date('Y-m-d'),
      'last_update' => $_SESSION['id_user']
    );

    $up = $db->update('tugas_akhir',$data,'id_ta',$_POST['id']);

    if ($up=true) {
      echo "good";
    } else {
      echo "bad";
    }
  break;
  case "delete":

    $db->delete("tugas_akhir","id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tugas_akhir","id",$id);
         }
    }
    break;

  case "up":

   $data = array(
      "nim" => $_POST["nim"],
      "kode_fak" => $_POST["kode_fak"],
      "kode_jurusan" => $_POST["kode_jurusan"],
      "id_jenis_keluar" => $_POST["id_jenis_keluar"],
      "tanggal_keluar" => $_POST["tanggal_keluar"],
      "sk_yudisium" => $_POST["sk_yudisium"],
      "tgl_sk_yudisium" => $_POST["tgl_sk_yudisium"],
      "no_seri_ijasah" => $_POST["no_seri_ijasah"],
      "jalur_skripsi" => $_POST["jalur_skripsi"],
      "judul_ta" => $_POST["judul_skripsi"],
      "bulan_awal_bimbingan" => $_POST["bulan_awal_bimbingan"],
      "bulan_akhir_bimbingan" => $_POST["bulan_akhir_bimbingan"],
      "pembimbing_1" => $_POST["pembimbing_1"],
      "pembimbing_2" => $_POST["pembimbing_2"],
      'updated_at'  => date('Y-m-d'),
      'last_update' => $_SESSION['id_user']
   );

   $up = $db->update("tugas_akhir",$data,"id_ta",$_POST["id"]);

   if ($up=true) {
      echo "good";
   } else {
      return false;
   }
    break;

  case "up_sk":
    $data = array(
      "id_jenis_keluar" => $_POST["id_jenis_keluar"],
      "tanggal_keluar"  => $_POST["tanggal_keluar"],
      "sk_yudisium"     => $_POST["sk_yudisium"],
      "tgl_sk_yudisium" => $_POST["tgl_sk_yudisium"],
      "no_seri_ijasah"  => $_POST["no_seri_ijasah"],
      "jalur_skripsi"   => $_POST["jalur_skripsi"],
      "bulan_awal_bimbingan"  => $_POST["bulan_awal_bimbingan"],
      "bulan_akhir_bimbingan" => $_POST["bulan_akhir_bimbingan"],
      'updated_at'  => date('Y-m-d'),
      'last_update' => $_SESSION['id_user']
    );

    $up_sk = $db->update("tugas_akhir",$data,"id_ta",$_POST["id"]);

    if ($up_sk=true) {
      echo "good";
    } else{
      return false;
    }
  break;
  default:
    # code...
    break;
}

?>
