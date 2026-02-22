<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "tgl_awal_input_nilai" => $_POST["tgl_awal_input_nilai"],
      "tgl_akhir_input_nilai" => $_POST["tgl_akhir_input_nilai"],
      "id_priode" => $_POST["id_priode"],
      "priode" => $_POST["priode"],
      "nama_periode" => $_POST["nama_periode"],
  );
  
  
  
   
    $in = $db->insert("v_dpl_ppl",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("v_dpl_ppl","",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("v_dpl_ppl","",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "tgl_awal_input_nilai" => $_POST["tgl_awal_input_nilai"],
      "tgl_akhir_input_nilai" => $_POST["tgl_akhir_input_nilai"],
      "id_priode" => $_POST["id_priode"],
      "priode" => $_POST["priode"],
      "nama_periode" => $_POST["nama_periode"],
   );
   
   
   

    
    
    $up = $db->update("v_dpl_ppl",$data,"",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>