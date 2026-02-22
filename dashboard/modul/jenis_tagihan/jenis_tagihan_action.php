<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  $check = $db->check_exist("keu_jenis_tagihan",array('kode_tagihan' => $_POST['kode_tagihan']));
  if ($check) {
    action_response("Kode Tagihan ini Sudah digunakan");
  }
  
  
  $data = array(
      "kode_tagihan" => $_POST["kode_tagihan"],
      "nama_tagihan" => $_POST["nama_tagihan"],
      "kode_pembayaran" => $_POST["kode_pembayaran"],
  );
  
  
  
   
          if(isset($_POST["syarat_krs"])=="on")
          {
            $syarat_krs = array("syarat_krs"=>"Y");
            $data=array_merge($data,$syarat_krs);
          } else {
            $syarat_krs = array("syarat_krs"=>"N");
            $data=array_merge($data,$syarat_krs);
          }
    $in = $db->insert("keu_jenis_tagihan",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_jenis_tagihan","kode_tagihan",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_jenis_tagihan","kode_tagihan",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
      if ($_POST['id']!=$_POST['kode_tagihan']) {
      $check = $db->query("select * from keu_jenis_tagihan where kode_tagihan=? and kode_tagihan!='".$_POST['id']."'",array('kode_tagihan' => $_POST['kode_tagihan']));
      if ($check->rowCount()>0) {
        action_response("Kode Tagihan ini Sudah digunakan");
      }
  }
   $data = array(
      "kode_tagihan" => $_POST["kode_tagihan"],
      "nama_tagihan" => $_POST["nama_tagihan"],
      "kode_pembayaran" => $_POST["kode_pembayaran"],
   );
   
   
   

    
          if(isset($_POST["syarat_krs"])=="on")
          {
            $syarat_krs = array("syarat_krs"=>"Y");
            $data=array_merge($data,$syarat_krs);
          } else {
            $syarat_krs = array("syarat_krs"=>"N");
            $data=array_merge($data,$syarat_krs);
          }
    
    $up = $db->update("keu_jenis_tagihan",$data,"kode_tagihan",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>