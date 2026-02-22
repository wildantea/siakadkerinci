<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "pekerjaan" => $_POST["pekerjaan"],
  );
  
  
  
   
    $in = $db->insert("pekerjaan",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("pekerjaan","id_pekerjaan",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("pekerjaan","id_pekerjaan",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "pekerjaan" => $_POST["pekerjaan"],
   );
   
   
   

    
    
    $up = $db->update("pekerjaan",$data,"id_pekerjaan",$_POST["id"]);
    
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