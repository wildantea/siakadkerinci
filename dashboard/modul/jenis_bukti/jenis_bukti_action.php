<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "jenis_bukti" => $_POST["jenis_bukti"],
  );
  
  
  
   
    $in = $db->insert("tb_data_pendaftaran_jenis_bukti",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    $id = $_GET["id"];
    //check if ide is used 
    $check_id = $db->fetchCustomSingle("select group_concat(bukti) as bukti from tb_data_pendaftaran_jenis_pengaturan where bukti like '%$id%'");
    if ($check_id) {
      $explode = explode(",",$check_id->bukti);
      if (in_array($id, $explode)) {
        action_response("Maaf Jenis Bukti Sudah digunakan di salah satu pengaturan pendaftaran");
      } else {
        $db->delete("tb_data_pendaftaran_jenis_bukti","id_jenis_bukti",$_GET["id"]);
      }
    } else {
      $db->delete("tb_data_pendaftaran_jenis_bukti","id_jenis_bukti",$_GET["id"]);
    }
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_pendaftaran_jenis_bukti","id_jenis_bukti",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "jenis_bukti" => $_POST["jenis_bukti"],
   );
   
   
   

    
    
    $up = $db->update("tb_data_pendaftaran_jenis_bukti",$data,"id_jenis_bukti",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>