<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":

    $data = array(
        "id_jenis_daftar" => $_POST["id_jenis_daftar"],
        "nm_jns_daftar" => $_POST["nm_jns_daftar"],
        "created_at"    => date("Y-m-d"),
        "last_update"   => $_SESSION["id_user"]
    );
   
    $in = $db->insert("jenis_daftar",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("jenis_daftar","id_jenis_daftar",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jenis_daftar","id_jenis_daftar",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "id_jenis_daftar" => $_POST["id_jenis_daftar"],
      "nm_jns_daftar" => $_POST["nm_jns_daftar"],
      "updated_at"    => date("Y-m-d"),
      "last_update"   => $_SESSION["id_user"]
   );
  
    $up = $db->update("jenis_daftar",$data,"id_jenis_daftar",$_POST["id"]);
    
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