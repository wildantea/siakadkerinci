<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "bobot" => $_POST["bobot"],
      "nilai_huruf" => $_POST["nilai_huruf"],
      "batas_bawah" => $_POST["batas_bawah"],
      "batas_atas" => $_POST["batas_atas"],
      "prodi_id" => $_POST["prodi_id"],
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
  );
  
  
  
   
    $in = $db->insert("nilai_ref",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("nilai_ref","nilai_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("nilai_ref","nilai_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "bobot" => $_POST["bobot"],
      "nilai_huruf" => $_POST["nilai_huruf"],
      "batas_bawah" => $_POST["batas_bawah"],
      "batas_atas" => $_POST["batas_atas"],
      "prodi_id" => $_POST["prodi_id"],
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
   );
   
   
   

    
    
    $up = $db->update("nilai_ref",$data,"nilai_id",$_POST["id"]);
    
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