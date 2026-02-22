<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "sem_id" => $_POST["sem_id"],
      "kls_nama" => $_POST["kls_nama"],
  );
  
  
  
   
    $in = $db->insert("kelas",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("kelas","kelas_id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("kelas","kelas_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "sem_id" => $_POST["sem_id"],
      "kls_nama" => $_POST["kls_nama"],
   );
   
   
   

    
    
    $up = $db->update("kelas",$data,"kelas_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>