<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "kode_gedung" => $_POST["kode_gedung"],
      "nm_gedung" => $_POST["nm_gedung"],
  );
  
  
  
   
          if(isset($_POST["is_aktif"])=="on")
          {
            $is_aktif = array("is_aktif"=>"Y");
            $data=array_merge($data,$is_aktif);
          } else {
            $is_aktif = array("is_aktif"=>"N");
            $data=array_merge($data,$is_aktif);
          }
    $in = $db->insert("gedung_ref",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("gedung_ref","gedung_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("gedung_ref","gedung_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "kode_gedung" => $_POST["kode_gedung"],
      "nm_gedung" => $_POST["nm_gedung"],
   );
   
   
   

    
          if(isset($_POST["is_aktif"])=="on")
          {
            $is_aktif = array("is_aktif"=>"Y");
            $data=array_merge($data,$is_aktif);
            $db->update('ruang_ref',array('is_aktif' => 'Y'),'gedung_id',$_POST['id']);
          } else {
            $is_aktif = array("is_aktif"=>"N");
            $data=array_merge($data,$is_aktif);
            $db->update('ruang_ref',array('is_aktif' => 'N'),'gedung_id',$_POST['id']);
          }
    
    $up = $db->update("gedung_ref",$data,"gedung_id",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>