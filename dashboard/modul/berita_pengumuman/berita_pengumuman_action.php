<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "judul" => $_POST["judul"],
      "isi" => $_POST["isi"],
      "date_created" => date('Y-m-d'),
      'created_by' => $_SESSION['group_level']
  );
  
  
  
   
          if(isset($_POST["tampil"])=="on")
          {
            $tampil = array("tampil"=>"Y");
            $data=array_merge($data,$tampil);
          } else {
            $tampil = array("tampil"=>"N");
            $data=array_merge($data,$tampil);
          }
    $in = $db->insert("tabel_berita",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("tabel_berita","id_news",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tabel_berita","id_news",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "judul" => $_POST["judul"],
      "isi" => $_POST["isi"],
   );
   
   
   

    
          if(isset($_POST["tampil"])=="on")
          {
            $tampil = array("tampil"=>"Y");
            $data=array_merge($data,$tampil);
          } else {
            $tampil = array("tampil"=>"N");
            $data=array_merge($data,$tampil);
          }
    
    $up = $db->update("tabel_berita",$data,"id_news",$_POST["id"]);
    
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