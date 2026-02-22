<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode" => $_POST["kode"],
      "nama_rumpun" => $_POST["nama_rumpun"],
  );
  
  
  
   
    $in = $db->insert("data_rumpun_dosen",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("data_rumpun_dosen","kode",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("data_rumpun_dosen","kode",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "kode" => $_POST["kode"],
      "nama_rumpun" => $_POST["nama_rumpun"],
   );
   
   
   

    
    
    $up = $db->update("data_rumpun_dosen",$data,"kode",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>