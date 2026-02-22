<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "jumlah" => $_POST["jumlah"],
      "id_tagihan_prodi" => $_POST["id_tagihan_prodi"],
      "periode" => $_POST["periode"],
  );
  
  
  
   
    $in = $db->insert("keu_tagihan_mahasiswa",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_tagihan_mahasiswa","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_tagihan_mahasiswa","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "jumlah" => $_POST["jumlah"],
      "id_tagihan_prodi" => $_POST["id_tagihan_prodi"],
      "periode" => $_POST["periode"],
   );
   
   
   

    
    
    $up = $db->update("keu_tagihan_mahasiswa",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>