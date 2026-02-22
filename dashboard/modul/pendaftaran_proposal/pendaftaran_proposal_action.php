<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'change':  
    
   $data = array(
      "status_accepted" => $_POST["stat"],
   );
   
    $up = $db->update("tb_data_pendaftaran",$data,"id",$_POST["id"]);

    action_response($db->getErrorMessage());
    break;
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "attr_value" => $_POST["attr_value"],
  );
  
  
  
   
    $in = $db->insert("tb_data_pendaftaran",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_data_pendaftaran","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_pendaftaran","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "attr_value" => $_POST["attr_value"],
   );
   
   
   

    
    
    $up = $db->update("tb_data_pendaftaran",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>