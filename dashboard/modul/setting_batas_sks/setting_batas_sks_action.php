<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jlm_sks" => $_POST["jlm_sks"],
      "ket_batas" => $_POST["ket_batas"],
  );
  
  
  
   
    $in = $db->insert("batas_sks",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("batas_sks","id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("batas_sks","id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "jlm_sks" => $_POST["jlm_sks"],
      "ket_batas" => $_POST["ket_batas"],
   );
   
   
   

    
    
    $up = $db->update("batas_sks",$data,"id",$_POST["id"]);
    
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