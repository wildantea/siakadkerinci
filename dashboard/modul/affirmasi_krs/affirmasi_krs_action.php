<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "periode" => $_POST["periode"],
      "ket_affirmasi" => $_POST["ket_affirmasi"]
      
  );
  
  
  
   
    $in = $db->insert("affirmasi_krs",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("affirmasi_krs","id_affirmasi",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("affirmasi_krs","id_affirmasi",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "periode" => $_POST["periode"],
      "ket_affirmasi" => $_POST["ket_affirmasi"]
   );
   
   
   

    
    
    $up = $db->update("affirmasi_krs",$data,"id_affirmasi",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>