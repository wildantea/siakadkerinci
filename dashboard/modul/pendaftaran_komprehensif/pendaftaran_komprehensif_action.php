<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":

    $data = array(
        "nim" => $_POST["nim"],
        "kode_jurusan"  => $_POST["kode_jurusan"],
        "kode_fak"  => $_POST["kode_fak"],
        "priode_kompre" => $_POST["priode_kompre"],
        "created_at"  => date('Y-m-d'),
        "last_update" => $_SESSION["id_user"]
    );

    $in = $db->insert("kompre",$data);

    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  
  case 'in_jadwal':
    $data = array(
      "batas_awal"  => $_POST['batas_awal'],
      "batas_akhir" => $_POST['batas_akhir'],
      "tanggal_kompre"  => $_POST['tanggal_sidang'],
      "created_at"  => date('Y-m-d'),
      "last_updated"  => $_SESSION['id_user'],
      "priode_kompre" => $dec->dec($_POST['priode_kompre'])
    );

    $in = $db->insert("jadwal_kompre",$data);

    if($in=true){
      echo "good";
    } else{
      return false;
    }
    break;
  
  case "in_mhs":

    $data = array(
        "nim" => $_POST["nim"],
        "kode_jurusan"  => $_POST["kode_jur"],
        "kode_fak"  => $_POST["kode_fak"],
        "created_at"  => date('Y-m-d'),
        "last_update" => $_SESSION["id_user"],
        "priode_kompre" => $_POST['priode_kompre']
    );

    $in_mhs = $db->insert("kompre",$data);

    if ($in_mhs=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    $db->delete("kompre","id",$_GET["id"]);
    break;
  case "delete_jadwal":
    $db->delete("jadwal_kompre","id_kompre",$_POST["id_data"]);
  break;
  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("kompre","id",$id);
         }
    }
    break;
  case "del_massal_jadwal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jadwal_kompre","id_kompre",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "updated_at"  => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
   );
    
    $up = $db->update("kompre",$data,"id",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case 'set':
    $data = array(
      "penguji_1" => $_POST["penguji_1"],
      "penguji_2" => $_POST["penguji_2"],
      "penguji_3" => $_POST["penguji_3"],
      "updated_at"  => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
    );

    $set = $db->update("kompre",$data,"id",$_POST["id"]);

    if($set=true) {
      echo "good";
    }else {
      return false;
    }
    break;

  case 'nilai':
    $data = array(
      "nilai_kompre"  => $_POST["nilai"],
      "updated_at"    => date("Y-m-d"),
      "last_update"   => $_SESSION['id_user']
    );

    $nilai = $db->update("kompre",$data,"id",$_POST["id"]);

    if($nilai=true) {
      echo "good";
    }else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>