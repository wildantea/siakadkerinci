<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nama_komponen" => $_POST["nama_komponen"],
  );
  
  
  
   
          if(isset($_POST["wajib"])=="on")
          {
            $wajib = array("wajib"=>"1");
            $data=array_merge($data,$wajib);
          } else {
            $wajib = array("wajib"=>"0");
            $data=array_merge($data,$wajib);
          }
          if(isset($_POST["isShow"])=="on")
          {
            $isShow = array("isShow"=>"1");
            $data=array_merge($data,$isShow);
          } else {
            $isShow = array("isShow"=>"0");
            $data=array_merge($data,$isShow);
          }
    $in = $db->insert("komponen_nilai",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("komponen_nilai","id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("komponen_nilai","id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nama_komponen" => $_POST["nama_komponen"],
   );
   
   
   

    
          if(isset($_POST["wajib"])=="on")
          {
            $wajib = array("wajib"=>"1");
            $data=array_merge($data,$wajib);
          } else {
            $wajib = array("wajib"=>"0");
            $data=array_merge($data,$wajib);
          }
          if(isset($_POST["isShow"])=="on")
          {
            $isShow = array("isShow"=>"1");
            $data=array_merge($data,$isShow);
          } else {
            $isShow = array("isShow"=>"0");
            $data=array_merge($data,$isShow);
          }
    
    $up = $db->update("komponen_nilai",$data,"id",$_POST["id"]);
    
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