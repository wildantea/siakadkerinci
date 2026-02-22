<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "sesi" => $_POST["sesi"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
  );
  
  
  
   
    $in = $db->insert("sesi_waktu",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("sesi_waktu","id_sesi",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("sesi_waktu","id_sesi",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "sesi" => $_POST["sesi"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
   );
   
   
   

    
    
    $up = $db->update("sesi_waktu",$data,"id_sesi",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>