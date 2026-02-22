<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_jur" => $_POST["kode_jur"],
      "sem_id" => $_POST["sem_id"],
      "nama_kurikulum" => $_POST["nama_kurikulum"],
      //"tahun_mulai_berlaku" => $_POST["tahun_mulai_berlaku"],
      "no_sk_rektor" => $_POST["no_sk_rektor"],
      "tgl_sk_rektor" => $_POST["tgl_sk_rektor"],
      //"tgl_disetujui" => $_POST["tgl_disetujui"],
      //"yang_menyetujui" => $_POST["yang_menyetujui"],
      //"no_sk_dikti" => $_POST["no_sk_dikti"],
      //"tgl_sk_dikti" => $_POST["tgl_sk_dikti"],
      //"masa_studi_ideal" => $_POST["masa_studi_ideal"],
      //"masa_studi_max" => $_POST["masa_studi_max"],
      //"gelar_dipakai" => $_POST["gelar_dipakai"],
      "ket" => $_POST["ket"],
      "jml_sks_wajib" => $_POST["jml_sks_wajib"],
      "jml_sks_pilihan" => $_POST["jml_sks_pilihan"],
      "total_sks" => $_POST["jml_sks_wajib"]+$_POST["jml_sks_pilihan"],
  );
  
  
  
   
    $in = $db->insert("kurikulum",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("kurikulum","kur_id",$_GET["id"]);
        if ($db->getErrorMessage()!="") {
          action_response("Tidak Bisa dihapus karena sudah ada Matakuliah");
        } else {
          action_response($db->getErrorMessage());
        }
        
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("kurikulum","kur_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kode_jur" => $_POST["kode_jur"],
      "sem_id" => $_POST["sem_id"],
      "nama_kurikulum" => $_POST["nama_kurikulum"],
      //"tahun_mulai_berlaku" => $_POST["tahun_mulai_berlaku"],
      "no_sk_rektor" => $_POST["no_sk_rektor"],
      "tgl_sk_rektor" => $_POST["tgl_sk_rektor"],
      //"tgl_disetujui" => $_POST["tgl_disetujui"],
      //"yang_menyetujui" => $_POST["yang_menyetujui"],
      //"no_sk_dikti" => $_POST["no_sk_dikti"],
      //"tgl_sk_dikti" => $_POST["tgl_sk_dikti"],
      //"masa_studi_ideal" => $_POST["masa_studi_ideal"],
      //"masa_studi_max" => $_POST["masa_studi_max"],
      //"gelar_dipakai" => $_POST["gelar_dipakai"],
      "ket" => $_POST["ket"],
      "jml_sks_wajib" => $_POST["jml_sks_wajib"],
      "jml_sks_pilihan" => $_POST["jml_sks_pilihan"],
      "total_sks" => $_POST["jml_sks_wajib"]+$_POST["jml_sks_pilihan"],
   );
   
   
   
    $up = $db->update("kurikulum",$data,"kur_id",$_POST["id"]);
    

    break;
  default:
    # code...
    break;
}

?>