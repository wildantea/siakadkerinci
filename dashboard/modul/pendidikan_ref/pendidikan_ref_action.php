<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jenjang" => $_POST["jenjang"],
  );
  
  
  
   
    $in = $db->insert("jenjang_pendidikan",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jenjang_pendidikan","id_jenjang",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jenjang_pendidikan","id_jenjang",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "jenjang" => $_POST["jenjang"],
   );
   
   
   

    
    
    $up = $db->update("jenjang_pendidikan",$data,"id_jenjang",$_POST["id"]);
    
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