<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "ip_min" => $_POST["ip_min"],
      "ip_mak" => $_POST["ip_mak"],
      "sks_mak" => $_POST["sks_mak"],
  );
  
  
  
   
    $in = $db->insert("jatah_sks",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("jatah_sks","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jatah_sks","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "ip_min" => $_POST["ip_min"],
      "ip_mak" => $_POST["ip_mak"],
      "sks_mak" => $_POST["sks_mak"],
   );
   
   
   

    
    
    $up = $db->update("jatah_sks",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>