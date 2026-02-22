<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "nip" => $_POST["nip"],
      "pertanyaan" => $_POST["pertanyaan"],
      "jawaban" => $_POST["jawaban"],
      "tgl_tanya" => $_POST["tgl_tanya"],
      "tgl_jawab" => $_POST["tgl_jawab"],
      "id_semester" => $_POST["id_semester"],
      "kategori_konsultasi" => $_POST["kategori_konsultasi"],
  );
  
  
  
   
    $in = $db->insert("bimbingan_dosen_pa",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("bimbingan_dosen_pa","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("bimbingan_dosen_pa","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "nip" => $_POST["nip"],
      "pertanyaan" => $_POST["pertanyaan"],
      "jawaban" => $_POST["jawaban"],
      "tgl_tanya" => $_POST["tgl_tanya"],
      "tgl_jawab" => $_POST["tgl_jawab"],
      "id_semester" => $_POST["id_semester"],
      "kategori_konsultasi" => $_POST["kategori_konsultasi"],
   );
   
   
   

    
    
    $up = $db->update("bimbingan_dosen_pa",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>