<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
    $data = array(
        "id_jns_keluar" => $_POST["id_jns_keluar"],
        "ket_keluar"    => $_POST["ket_keluar"],
        "created_at"    => date("Y-m-d"),
        "last_update"   => $_SESSION['id_user']
    );
  
    $in = $db->insert("jenis_keluar",$data);   
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
  break;
  case "delete":
    
    $db->delete("jenis_keluar","id_jns_keluar",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jenis_keluar","id_jns_keluar",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "id_jns_keluar" => $_POST['id_jns_keluar'],
      "ket_keluar"  => $_POST["ket_keluar"],
      "updated_at"  => date("Y-m-d"),
      "last_update" => $_SESSION['id_user']
   );

    $up = $db->update("jenis_keluar",$data,"id_jns_keluar",$_POST["id"]);
    
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