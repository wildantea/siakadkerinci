<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_jur" => $_POST["kode_jur"],
      "id_status" => $_POST["id_status"],
  );
  
  
  
   
    $in = $db->insert("dosen",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("dosen","id_dosen",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("dosen","id_dosen",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "kode_jur" => $_POST["kode_jur"],
      "id_status" => $_POST["id_status"],
   );
   
   
   

    
    
    $up = $db->update("dosen",$data,"id_dosen",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>