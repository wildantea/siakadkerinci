<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "id_krs_detail" => $_POST["id_krs_detail"],
      "sks" => $_POST["sks"],
      "bobot" => $_POST["bobot"],
      "nilai_huruf" => $_POST["nilai_huruf"],
  );
  
  
  
   
    $in = $db->insert("krs_detail",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("krs_detail","id_krs_detail",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("krs_detail","id_krs_detail",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "id_krs_detail" => $_POST["id_krs_detail"],
      "sks" => $_POST["sks"],
      "bobot" => $_POST["bobot"],
      "nilai_huruf" => $_POST["nilai_huruf"],
   );
   
   
   

    
    
    $up = $db->update("krs_detail",$data,"id_krs_detail",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
    case "cari_mhs":
    # code...
    break;
  default:
    # code...
    break;
}

?>