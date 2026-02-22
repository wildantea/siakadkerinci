<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "id_agama" => $_POST["id_agama"],
      "nm_agama" => $_POST["nm_agama"],
  );
  
  
  
   
    $in = $db->insert("agama",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("agama","id_agama",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("agama","id_agama",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "id_agama" => $_POST["id_agama"],
      "nm_agama" => $_POST["nm_agama"],
   );
   
   
   

    
    
    $up = $db->update("agama",$data,"id_agama",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>