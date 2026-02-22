<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {

   case "get_matkul":
    $par = $_GET['term'];
   // print_r($key);
    $key = $par['term'];
    $res = array();
    $q = $db->query("select m.kode_mk,m.nama_mk,m.id_matkul from matkul m join kurikulum k on k.kur_id=m.kur_id  where (m.kode_mk like '%$key%' or m.nama_mk like '%$key%') and k.kode_jur='".$_GET['kode_jur']."' ");
    foreach ($q as $k) {
       $data['id']       = $k->kode_mk; 
       $data['text']     = $k->kode_mk." - ".$k->nama_mk; 
       $res['results'][] = $data;
    }
    echo json_encode($res);
  break;
  case "in":
    
  
  
  
  $data = array(
      "jurusan" => $_POST["jurusan"],
      "kode_mk" => $_POST["kode_mk"],
  );
  
  
  
   
    $in = $db->insert("matkul_ppl",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("matkul_ppl","",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("matkul_ppl","",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "jurusan" => $_POST["jurusan"],
      "kode_mk" => $_POST["kode_mk"],
   );
   
   
   

    
    
    $up = $db->update("matkul_ppl",$data,"",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>