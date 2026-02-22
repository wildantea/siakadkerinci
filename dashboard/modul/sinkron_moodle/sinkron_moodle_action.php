<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {

    case "get_jur":
    $kode_fak = $_POST['kode_fak'];
    $q = $db->query("select kode_jur,nama_jur from jurusan where fak_kode='$kode_fak' ");
    echo "<option value=''>Pilih Jurusan</option>";
     echo "<option value='all'>Semua Jurusan</option>";
    foreach ($q as $k) {
      echo "<option value='$k->kode_jur'>$k->nama_jur</value>";
    }
    break;

  case "in":
    
  
  
  
  $data = array(
      "shortname" => $_POST["shortname"],
      "nama_mk" => $_POST["nama_mk"],
      "category" => $_POST["category"],
      "sumary" => $_POST["sumary"],
  );
  
  
  
   
    $in = $db->insert("v_matkul_salam",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("v_matkul_salam","",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("v_matkul_salam","",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "shortname" => $_POST["shortname"],
      "nama_mk" => $_POST["nama_mk"],
      "category" => $_POST["category"],
      "sumary" => $_POST["sumary"],
   );
   
   
   

    
    
    $up = $db->update("v_matkul_salam",$data,"",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>