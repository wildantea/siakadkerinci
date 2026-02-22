<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
  $data = array(
      "id_jadwal_pendaftaran" => $_POST["periode_bulan_jadwal"],
      "id_ruang_seminar" => $_POST["id_ruang_jadwal"],
      "tanggal_seminar" => $_POST['tanggal_jadwal']
  );
  
    $in = $db->insert("tb_jadwal_seminar",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    $db->delete("tb_jadwal_seminar","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_pendaftaran","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
  $data = array(
      "id_jadwal_pendaftaran" => $_POST["periode_bulan_jadwal"],
      "id_ruang_seminar" => $_POST["id_ruang_jadwal"],
      "tanggal_seminar" => $_POST['tanggal_jadwal']
  );
   
   

    
    
    $up = $db->update("tb_jadwal_seminar",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>