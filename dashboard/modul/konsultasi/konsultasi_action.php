<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in_mhs":
  $data = array(
      "pertanyaan" => $_POST["pertanyaan"],
      "tanggal_tanya" => date('Y-m-d H:i:s'),
      "is_read_by_mhs" => 'Y',
      'id_semester' => get_sem_aktif(),
      "kategori_konsultasi" => $_POST['kategori_konsultasi'],
      "nim" => $_POST['nim'],
      "nip" => $_POST['nip']
  );
  
   
    $in = $db->insert("bimbingan_dosen_pa",$data);
    
    
    action_response($db->getErrorMessage());
    break;

  case "up_dosen":
  $data = array(
      "jawaban" => $_POST["jawaban"],
       "tanggal_jawab" => date('Y-m-d H:i:s'),
  );
  
   $up = $db->update("bimbingan_dosen_pa",$data,"id",$_POST["id"]);
    
    
    action_response($db->getErrorMessage());
    break;
  case "up_mhs":
  $data = array(
      "pertanyaan" => $_POST["pertanyaan"],
       "tanggal_tanya" => date('Y-m-d H:i:s'),
  );
  
   $up = $db->update("bimbingan_dosen_pa",$data,"id",$_POST["id"]);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("mahasiswa","mhs_id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "no_pendaftaran" => $_POST["no_pendaftaran"],
   );
   
   
   

    
    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>