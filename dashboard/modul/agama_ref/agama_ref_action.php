<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nm_agama" => $_POST["nm_agama"],
  );
  
  
  
   
    $in = $db->insert("agama",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("agama","id_agama",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("agama","id_agama",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nm_agama" => $_POST["nm_agama"],
   );
   
   
   

    
    
    $up = $db->update("agama",$data,"id_agama",$_POST["id"]);
    
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