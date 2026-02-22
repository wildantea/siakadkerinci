<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jabatan" => $_POST["jabatan"],
      "nip" => $_POST["nip"],
  );
  
  
  
   
    $in = $db->insert("pejabat",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("pejabat","id_pejabat",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("pejabat","id_pejabat",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "jabatan" => $_POST["jabatan"],
      "nip" => $_POST["nip"],
   );
   
   
   

    
    
    $up = $db->update("pejabat",$data,"id_pejabat",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>