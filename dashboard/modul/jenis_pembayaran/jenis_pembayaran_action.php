<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  $check = $db->check_exist("keu_jenis_pembayaran",array('kode_pembayaran' => $_POST['kode_pembayaran']));
  if ($check) {
    action_response("Kode Pembayaran ini Sudah digunakan");
  }
  
  $data = array(
      "kode_pembayaran" => $_POST["kode_pembayaran"],
      "nama_pembayaran" => $_POST["nama_pembayaran"],
  );
  
  
  
   
    $in = $db->insert("keu_jenis_pembayaran",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_jenis_pembayaran","kode_pembayaran",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_jenis_pembayaran","kode_pembayaran",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
  $check = $db->check_exist("keu_jenis_pembayaran",array('kode_pembayaran' => $_POST['id']));
  if ($check) {
    action_response("Kode Pembayaran ini Sudah digunakan");
  }
   $data = array(
      "kode_pembayaran" => $_POST["kode_pembayaran"],
      "nama_pembayaran" => $_POST["nama_pembayaran"],
   );
   
   
   

    
    
    $up = $db->update("keu_jenis_pembayaran",$data,"kode_pembayaran",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>