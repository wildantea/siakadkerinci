<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":

  $lower = strtolower($_POST['nama_directory']);
    
  $nama_directory = str_replace(" ", "_", strtolower($lower));
  $nama_directory = str_replace("/", "_", strtolower($nama_directory));
  
  
  $data = array(
      "nama_jenis_pendaftaran" => $_POST["nama_jenis_pendaftaran"],
      "nama_directory" => $nama_directory,
  );
  
    if (!is_dir("../../../upload/pendaftaran/".$nama_directory)) {
        mkdir("../../../upload/pendaftaran/".$nama_directory);
    }
  
   
    $in = $db2->insert("tb_data_pendaftaran_jenis",$data);
    
    
    action_response($db2->getErrorMessage());
    break;
  case "delete":
    $db2->delete("tb_data_pendaftaran_jenis","id_jenis_pendaftaran",$_GET["id"]);
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_pendaftaran_jenis","id_jenis_pendaftaran",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":
      $lower = strtolower($_POST['nama_directory']);
    
  $nama_directory = str_replace(" ", "_", strtolower($lower));
  $nama_directory = str_replace("/", "_", strtolower($nama_directory));
  
  
  $data = array(
      "nama_jenis_pendaftaran" => $_POST["nama_jenis_pendaftaran"],
      "nama_directory" => $nama_directory,
  );
  
    if (!is_dir("../../../upload/pendaftaran/".$nama_directory)) {
        mkdir("../../../upload/pendaftaran/".$nama_directory);
    }
   $data = array(
      "nama_jenis_pendaftaran" => $_POST["nama_jenis_pendaftaran"]
   );

    
    
    $up = $db2->update("tb_data_pendaftaran_jenis",$data,"id_jenis_pendaftaran",$_POST["id"]);
    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>