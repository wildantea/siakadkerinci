<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jns_semester" => $_POST["jns_semester"],
      "nm_singkat" => $_POST["nm_singkat"],
  );
  
  
  
   
    $in = $db->insert("jenis_semester",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jenis_semester","id_jns_semester",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jenis_semester","id_jns_semester",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "jns_semester" => $_POST["jns_semester"],
      "nm_singkat" => $_POST["nm_singkat"],
   );
   
   
   

    
    
    $up = $db->update("jenis_semester",$data,"id_jns_semester",$_POST["id"]);
    
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