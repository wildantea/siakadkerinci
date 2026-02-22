<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
  $data = array(
      "id_mk" => $_POST["id_mk"],
      "id_mk_prasyarat" => $_POST["id_mk_prasyarat"],
      "syarat" => $_POST['syarat']
  );

  
   
    $in = $db->insert("prasyarat_mk",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->query("delete from prasyarat_mk where id_mk=? and id_mk_prasyarat=?",array('id_mk' => $_GET["id_mk"],'id_mk_prasyarat' => $_GET['id']));
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("kecamatan","id_kec",$id);
         }
    }
    break;
  case "up":


    
   $data = array(
      "id_kab" => $_POST["id_kab"],
      "nama_kec" => $_POST["nama_kec"],
   );
   
   
   

    
    
    $up = $db->update("kecamatan",$data,"id_kec",$_POST["id"]);
    
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