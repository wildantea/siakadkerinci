<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "pembimbing_1" => $_POST["pembimbing_1"],
      "pembimbing_2" => $_POST["pembimbing_2"],
  );
  
  
  
   
    $in = $db->insert("tugas_akhir",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("tugas_akhir","id_ta",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tugas_akhir","id_ta",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "pembimbing_1" => $_POST["pembimbing_1"],
      "pembimbing_2" => $_POST["pembimbing_2"],
   );
   
   
   

    
    
    $up = $db->update("tugas_akhir",$data,"id_ta",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>