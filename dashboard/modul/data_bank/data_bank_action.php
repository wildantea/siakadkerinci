<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_bank" => $_POST["kode_bank"],
      "nomor_rekening" => $_POST["nomor_rekening"],
      "nama_singkat" => $_POST["nama_singkat"],
      "nama_bank" => $_POST["nama_bank"],
      "cabang" => $_POST["cabang"],
  );
  
  
  
   
    $in = $db->insert("keu_bank",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_bank","kode_bank",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_bank","kode_bank",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
   $data = array(
      "kode_bank" => $_POST["kode_bank"],
      "nomor_rekening" => $_POST["nomor_rekening"],
      "nama_singkat" => $_POST["nama_singkat"],
      "nama_bank" => $_POST["nama_bank"],
      "cabang" => $_POST["cabang"],
   );
   
    if(isset($_POST["aktif"])=="on")
    {
      $aktif = array("aktif"=>"Y");
      $data=array_merge($data,$aktif);
    } else {
      $aktif = array("aktif"=>"N");
      $data=array_merge($data,$aktif);
    }

    if (!empty($_POST['peruntukan'])) {
        $peruntukan_array = array_map('intval', $_POST['peruntukan']);
        // Encode the array as JSON
        $peruntukan_json = json_encode($peruntukan_array);
        $data["peruntukan"] = $peruntukan_json;
    }
    
    $up = $db->update("keu_bank",$data,"kode_bank",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>