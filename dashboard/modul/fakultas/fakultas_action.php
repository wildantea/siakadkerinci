<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_fak" => $_POST["kode_fak"],
      "nama_resmi" => $_POST["nama_resmi"],
      "nama_singkat" => $_POST["nama_singkat"],
      "nama_asing" => $_POST["nama_asing"],
      "email" => $_POST["email"],
      "web" => $_POST["web"],
      "dekan" => $_POST["dekan"],
  );
  
  
  
   
    $in = $db->insert("fakultas",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("fakultas","kode_fak",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("fakultas","kode_fak",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kode_fak" => $_POST["kode_fak"],
      "nama_resmi" => $_POST["nama_resmi"],
      "nama_singkat" => $_POST["nama_singkat"],
      "nama_asing" => $_POST["nama_asing"],
      "email" => $_POST["email"],
      "web" => $_POST["web"],
      "dekan" => $_POST["dekan"],
   );
   
   
   

    
    
    $up = $db->update("fakultas",$data,"kode_fak",$_POST["id"]);
    
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