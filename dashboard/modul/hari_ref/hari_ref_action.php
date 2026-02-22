<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "hari" => $_POST["hari"],
  );
  
  
  
   
    $in = $db->insert("hari_ref",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("hari_ref","hari_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("hari_ref","hari_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "hari" => $_POST["hari"],
   );
   
   
   

    
    
    $up = $db->update("hari_ref",$data,"hari_id",$_POST["id"]);
    
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