<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "priode" => $_POST["priode"],
      "nama_periode" => $_POST["nama_periode"],
      "nip" => $_POST["nip"],
      "nip2" => $_POST["nip2"],
  );
  
  
  
   
    $in = $db->insert("v_dpl",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("v_dpl","",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("v_dpl","",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "priode" => $_POST["priode"],
      "nama_periode" => $_POST["nama_periode"],
      "nip" => $_POST["nip"],
      "nip2" => $_POST["nip2"],
   );
   
   
   

    
    
    $up = $db->update("v_dpl",$data,"",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>