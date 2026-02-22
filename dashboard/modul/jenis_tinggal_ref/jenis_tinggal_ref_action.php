<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jenis_tinggal" => $_POST["jenis_tinggal"],
  );
  
  
  
   
    $in = $db->insert("jenis_tinggal",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jenis_tinggal","id_jns_tinggal",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jenis_tinggal","id_jns_tinggal",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "jenis_tinggal" => $_POST["jenis_tinggal"],
   );
   
   
   

    
    
    $up = $db->update("jenis_tinggal",$data,"id_jns_tinggal",$_POST["id"]);
    
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