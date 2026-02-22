<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_jurusan" => $_POST["kode_jurusan"],
  );
  
  
  
   
    $in = $db->insert("tb_data_pendaftaran",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_data_pendaftaran","id_pendaftaran",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_pendaftaran","id_pendaftaran",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "kode_jurusan" => $_POST["kode_jurusan"],
   );
   
   
   

    
    
    $up = $db->update("tb_data_pendaftaran",$data,"id_pendaftaran",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>