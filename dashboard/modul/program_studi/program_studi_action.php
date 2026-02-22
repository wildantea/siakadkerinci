<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_jur" => $_POST["kode_dikti"],
      "kode_dikti" => $_POST["kode_dikti"],
      "id_jenjang" => $_POST["id_jenjang"],
      "fak_kode" => $_POST["fak_kode"],
      "status" => $_POST["status"],
      "nama_jur" => $_POST["nama_jur"],
      "nama_jur_asing" => $_POST["nama_jur_asing"],
      "sks_lulus" => $_POST["sks_lulus"],
      "web" => $_POST["web"],
      "email" => $_POST["email"],
      "telp" => $_POST["telp"],
      "tgl_berdiri" => $_POST["tgl_berdiri"],
      "no_sk_dikti" => $_POST["no_sk_dikti"],
      "tgl_sk_dikti" => $_POST["tgl_sk_dikti"],
      "ketua_jurusan" => $_POST["ketua_jurusan"],
  );
  
  
  
   
    $in = $db->insert("jurusan",$data);
    echo $db->getErrorMessage();
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jurusan","kode_jur",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jurusan","kode_jur",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kode_jur" => $_POST["kode_dikti"],
      "kode_dikti" => $_POST["kode_dikti"],
      "id_jenjang" => $_POST["id_jenjang"],
      "fak_kode" => $_POST["fak_kode"],
      "status" => $_POST["status"],
      "nama_jur" => $_POST["nama_jur"],
      "nama_jur_asing" => $_POST["nama_jur_asing"],
      "sks_lulus" => $_POST["sks_lulus"],
      "web" => $_POST["web"],
      "email" => $_POST["email"],
      "telp" => $_POST["telp"],
      "tgl_berdiri" => $_POST["tgl_berdiri"],
      "no_sk_dikti" => $_POST["no_sk_dikti"],
      "tgl_sk_dikti" => $_POST["tgl_sk_dikti"],
      "ketua_jurusan" => $_POST["ketua_jurusan"],
   );
   
   
   

    
    
    $up = $db->update("jurusan",$data,"kode_jur",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>