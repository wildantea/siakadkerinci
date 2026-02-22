<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
    case "in":

    $data=array(
      "nim"         => $_POST['nim'],
      "id_wisuda"   => $_POST['id_wisuda'],
      "created_at"  => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
    );

    $inpriode = $db->insert("detail_wisuda",$data);

    if($inpriode=true) {
      echo "good";
    } else{
      return false;
    }
    
  break;

  case "in_priode":

    $data=array(
      "priode"      => $_POST['priode'],
      "nama_wisuda" => $_POST['nama_wisuda'],
      "tempat"      => $_POST['tempat'],
      "biaya"       => $_POST['biaya'],
      "kuota"       => $_POST['kuota'],
      "tanggal"     => $_POST['tgl_wisuda'],
      "tgl_awal"    => $_POST['tgl_awal'],
      "tgl_akhir"   => $_POST['tgl_akhir'],
      "created_at"  => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
    );

    $inpriode = $db->insert("kelola_wisuda",$data);

    if($inpriode=true) {
      echo "good";
    } else{
      return false;
    }
    
  break;
  
  case "change_status":

    $data=array(
      "updated_at" => date('Y-m-d'),
      "last_update" => $_SESSION['id_user'],
      'status_ta'=>$_POST['stat'],
    );

    $up = $db->update('detail_wisuda',$data,'id_detail',$_POST['id']);

    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
  break;

  case "delete":
    
    $db->delete("detail_wisuda","id_detail",$_GET["id"]);
    break;
  case "delete_priode":
    
    $db->delete("kelola_wisuda","id_wisuda",$_POST["id_data"]);
  break;

  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("detail_wisuda","id_wisuda",$id);
         }
    }
    break;
  case "up":
    
    $data = array(
      "nim"         => $_POST["nim"],
      "id_wisuda"   => $_POST["id_wisuda"],
      "updated_at"  => date("Y-m-d"),
      "last_update" => $_SESSION["id_user"]
    );    
    
    $up = $db->update("detail_wisuda",$data,"id_detail",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;

    case "up_priode":
    
    $data = array(
      "priode"      => $_POST["priode"],
      "nama_wisuda" => $_POST["nama"],
      "tempat"      => $_POST["tempat"],
      "biaya"       => $_POST["biaya"],
      "kuota"       => $_POST["kuota"],
      "tanggal"     => $_POST["tanggal"],
      "updated_at"  => date("Y-m-d"),
      "last_update" => $_SESSION["id_user"]
    );    
    
    $up = $db->update("kelola_wisuda",$data,"id_wisuda",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;

    case "in_mhs":
    $data = array(
        "nim" => $_POST["nim"],
        "id_wisuda" => $_POST["id_wisuda"],
        "created_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user'],
    );

    $in = $db->insert("detail_wisuda",$data);

    if ($in=true) {
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