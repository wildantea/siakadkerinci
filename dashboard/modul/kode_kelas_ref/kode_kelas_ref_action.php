<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_paralel" => $_POST["kode_paralel"],
      "nm_paralel" => $_POST["nm_paralel"],
  );
  
  
  
   
    $in = $db->insert("paralel_kelas_ref",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("paralel_kelas_ref","kode_paralel",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("paralel_kelas_ref","kode_paralel",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kode_paralel" => $_POST["kode_paralel"],
      "nm_paralel" => $_POST["nm_paralel"],
   );
   
   
   

    
    
    $up = $db->update("paralel_kelas_ref",$data,"kode_paralel",$_POST["id"]);
    
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