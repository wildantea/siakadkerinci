<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in_mhs":

  $data = array(
      "nim_beasiswamhs" => $_POST["nim_beasiswamhs"],
      "id_beasiswajns" => $_POST["id_beasiswajns"],
      "id_beasiswa" => $_POST["id_beasiswa"],
      "ipk_beasiswamhs" => $_POST["ipk_beasiswamhs"],
      "stt_beasiswamhs" => 0,
      "created_at"      => date("Y-m-d"),
      "last_update"     => $_SESSION["id_user"],
      "kode_fak"        => $_POST["kode_fak"],
      "kode_jur"        => $_POST["kode_jur"],
      "priode_beasiswa" => $dec->dec($_POST['priode_beasiswa'])
  );
  
    $in = $db->insert("beasiswa_mhs",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;

  case 'in_jns':
    $data = array(
      "jenis_beasiswajns" => $_POST["jenis_beasiswa"],
      "keterangan"     => $_POST["keterangan"],
      "created_at"     => date("Y-m-d"),
      "last_update"    => $_SESSION["id_user"] 
    );

    $in = $db->insert("beasiswa_jenis",$data);

    if($in=true) {
      echo "good";
    }else {
      return false;
    }
    break;

  case 'in_beasiswa':
    $data = array(
      "jns_beasiswa"    => $_POST["jenis_beasiswa"],
      "priode_beasiswa" => $dec->dec($_POST["priode_beasiswa"]),
      "batas_awal"      => $_POST["batas_awal"],
      "batas_akhir"     => $_POST["batas_akhir"],
      "nama_beasiswa"   => $_POST["nama_beasiswa"],
      "syarat"          => $_POST["syarat_beasiswa"],
      "created_at"     => date("Y-m-d"),
      "last_update"    => $_SESSION["id_user"] 
    );

    $in = $db->insert("beasiswa",$data);

    if($in=true) {
      echo "good";
    }else {
      return false;
    }
    break;

  case "change_status":

      $data=array(
        'stt_beasiswamhs'=>$_POST['stat'],
        "updated_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user'],
      );

      $up = $db->update('beasiswa_mhs',$data,'id_beasiswamhs',$_POST['id']);

      if ($up=true) {
        echo "good";
      } else {
        return false;
      }
    break;

  case "delete":
    
    $db->delete("beasiswa_mhs","id_beasiswamhs",$_GET["id"]);
    break;
  
  case 'delete_jns':
    
    $db->delete("beasiswa_jenis","id_beasiswajns",$_POST['id_data']);
    break;
  
  case 'delete_beasiswa':
    
    $db->delete("beasiswa","id_beasiswa",$_POST['id_data']);
    break;

  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("beasiswa_mhs","id_beasiswamhs",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "nim_beasiswamhs" => $_POST["nim_beasiswamhs"],
      "id_beasiswajns" => $_POST["id_beasiswajns"],
      "id_beasiswa" => $_POST["id_beasiswa"],
      "ipk_beasiswamhs" => $_POST["ipk_beasiswamhs"],
   );
   
    $up = $db->update("beasiswa_mhs",$data,"id_beasiswamhs",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case 'up_jns':
    $data = array(
      "jenis_beasiswajns" => $_POST["jenis_beasiswa"],
      "keterangan"        => $_POST["keterangan"],
      "updated_at"        => date("Y-m-d"),
      "last_update"       => $_SESSION["id_user"]
    );

    $up = $db->update("beasiswa_jenis",$data,"id_beasiswajns",$_POST["id"]);

    if($up=true) {
      echo "good";
    }else {
      return false;
    }
    break;
  case 'up_beasiswa':
    $data = array(
      "jns_beasiswa"    => $_POST["jenis_beasiswa"],
      "priode_beasiswa" => $dec->dec($_POST["priode_beasiswa"]),
      "batas_awal"      => $_POST["batas_awal"],
      "batas_akhir"     => $_POST["batas_akhir"],
      "nama_beasiswa"   => $_POST["nama_beasiswa"],
      "syarat"          => $_POST["syarat_beasiswa"],
      "updated_at"     => date("Y-m-d"),
      "last_update"    => $_SESSION["id_user"] 
    );

    $up = $db->update("beasiswa",$data,"id_beasiswa",$_POST["id"]);

    if($up=true) {
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