<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_mk" => $_POST["kode_mk"],
      "mk" => $_POST["mk"],
      "mk" => $_POST["mk"],
  );
  
  
  
   
    $in = $db->insert("matkul_kukerta",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("matkul_kukerta","",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("matkul_kukerta","",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "kode_mk" => $_POST["kode_mk"],
      "mk" => $_POST["mk"],
      "mk" => $_POST["mk"],
   );
   
   
   

    
    
    $up = $db->update("matkul_kukerta",$data,"",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>