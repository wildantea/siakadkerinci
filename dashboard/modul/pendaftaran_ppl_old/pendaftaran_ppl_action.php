<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "kode_fak" => $_POST["kode_fak"],
      "kode_jur" => $_POST["kode_jur"],
  );
  
  
  
   
    $in = $db->insert("ppl",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("ppl","id_kkn",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("ppl","id_kkn",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "kode_fak" => $_POST["kode_fak"],
      "kode_jur" => $_POST["kode_jur"],
   );
   
   
   

    
    
    $up = $db->update("ppl",$data,"id_kkn",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>