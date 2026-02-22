<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "no_briva" => $_POST["no_briva"],
      "nama" => $_POST["nama"],
      "jumlah" => $_POST["jumlah"],
      "tgl_bayar" => $_POST["tgl_bayar"],
      "teller_id" => $_POST["teller_id"],
      "id_keu_tagihan_mhs" => $_POST["id_keu_tagihan_mhs"],
      "norek" => $_POST["norek"],
  );
  
  
  
   
    $in = $db->insert("transaksi_briva",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("transaksi_briva","id_transaksi",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("transaksi_briva","id_transaksi",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "no_briva" => $_POST["no_briva"],
      "nama" => $_POST["nama"],
      "jumlah" => $_POST["jumlah"],
      "tgl_bayar" => $_POST["tgl_bayar"],
      "teller_id" => $_POST["teller_id"],
      "id_keu_tagihan_mhs" => $_POST["id_keu_tagihan_mhs"],
      "norek" => $_POST["norek"],
   );
   
   
   

    
    
    $up = $db->update("transaksi_briva",$data,"id_transaksi",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>