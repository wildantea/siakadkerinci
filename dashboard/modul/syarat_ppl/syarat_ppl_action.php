<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {

    case "get_matkul2":
   // $par = $_GET['term'];
   // print_r($key);
    //$key = $par['term'];
    $res = array();
    $q = $db->query("select s.*,m.nama_mk,m.kode_mk from syarat_ppl_matkul s join matkul m on m.id_matkul=s.id_matkul where s.id_syarat='".$_GET['id']."' ");
    foreach ($q as $k) {
       $data['id']       = $k->id_matkul; 
       $data['text']     = $k->kode_mk." - ".$k->nama_mk; 
       $res[] = json_encode($data);
       //$res['results'][] = $data; 
    }
   echo "[".implode(",", $res)."]";
  break;

  case "get_matkul":
    $par = $_GET['term'];
   // print_r($key);
    $key = $par['term'];
    $res = array();
    $q = $db->query("select m.kode_mk,m.nama_mk,m.id_matkul from matkul m join kurikulum k on k.kur_id=m.kur_id  where (m.kode_mk like '%$key%' or m.nama_mk like '%$key%') and k.kode_jur='".$_GET['kode_jur']."' ");
    foreach ($q as $k) {
       $data['id']       = $k->id_matkul; 
       $data['text']     = $k->kode_mk." - ".$k->nama_mk; 
       $res['results'][] = $data;
    }
    echo json_encode($res);
  break;


  case "in":
    
  
  
  
  $data = array(
      "syarat_sks" => $_POST["syarat_sks"],
      "syarat_semester" => $_POST["syarat_semester"],
      "kode_jur" => $_POST["kode_jur"],
      "kondisi" => $_POST["kondisi"],
      ""
  );
  
  
  
   
    $in = $db->insert("syarat_ppl",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("syarat_ppl","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("syarat_ppl","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "syarat_sks" => $_POST["syarat_sks"],
      "syarat_semester" => $_POST["syarat_semester"],
      "kode_jur" => $_POST["kode_jur"],
       "kondisi" => $_POST["kondisi"],
   );

    
    $up = $db->update("syarat_ppl",$data,"id",$_POST["id"]);
    $db->query("delete from syarat_ppl_matkul where id_syarat=?",array($_POST["id"]));
    foreach ($_POST['matkul_prasyarat'] as $key => $value) {
       $dt = array('id_syarat' => $_POST["id"], 
                   'id_matkul' => $value);
       $db->insert("syarat_ppl_matkul",$dt);
    }
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>