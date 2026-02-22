<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "penghasilan" => $_POST["penghasilan"],
      "batas_bawah" => $_POST["batas_bawah"],
      "batas_atas" => $_POST["batas_atas"],
  );
  
  
  
   
    $in = $db->insert("penghasilan",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("penghasilan","id_penghasilan",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("penghasilan","id_penghasilan",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "penghasilan" => $_POST["penghasilan"],
      "batas_bawah" => $_POST["batas_bawah"],
      "batas_atas" => $_POST["batas_atas"],
   );
   
   
   

    
    
    $up = $db->update("penghasilan",$data,"id_penghasilan",$_POST["id"]);
    
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