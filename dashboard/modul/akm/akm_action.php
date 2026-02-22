<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "stat_pd" => $_POST["stat_pd"],
  );
  
  
  
   
    $in = $db->insert("mahasiswa",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("mahasiswa","mhs_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "stat_pd" => $_POST["stat_pd"],
   );
   
   
   

    
    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);
    
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