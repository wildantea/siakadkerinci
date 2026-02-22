<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case 'up_set':
      $data= array(
        "penguji_1" => $_POST['penguji_1'],
        "penguji_2" => $_POST['penguji_2'],
        "updated_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user'],
        "penguji_3" => $_POST['penguji_3'],
        "ketua_sidang"  => $_POST['ketua_sidang'],
        "sekertaris_sidang" => $_POST['sekertaris_sidang']
      );

      $up = $db->update('tugas_akhir',$data,'id_ta',$_POST['id']);

      if ($up=true) {
        echo "good";
      } else {
        echo "bad";
      }
  break;
  case "change_status":

    $data=array(
      "updated_at" => date('Y-m-d'),
      "last_update" => $_SESSION['id_user'],
      'status_ta'=>$_POST['stat'],
    );

    $up = $db->update('tugas_akhir',$data,'id_ta',$_POST['id']);

    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
  break;
  case "in_mhs":
    $data = array(
        "kode_fak" => $_POST["kode_fak"],
        "kode_jurusan" => $_POST["kode_jurusan"],
        "nim" => $_POST["nim"],
        "judul_ta" => $_POST["judul_ta"],
        "pembimbing_1" => $_POST["pembimbing_1"],
        "pembimbing_2" => $_POST["pembimbing_2"],
        "tgl_daftar"  => date('Y-m-d'),
        "created_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user'],
        'status_ta'  => 1,
        "priode_muna" => $dec->dec($_POST['priode_muna'])
    );

    $in = $db->insert("tugas_akhir",$data);

    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
  break;
  case "in":
    $data = array(
        "kode_fak" => $_POST["kode_fak"],
        "kode_jurusan" => $_POST["kode_jurusan"],
        "nim" => $_POST["nim"],
        "judul_ta" => $_POST["judul_ta"],
        "pembimbing_1" => $_POST["pembimbing_1"],
        "pembimbing_2" => $_POST["pembimbing_2"],
        "priode_muna"  => $_POST["priode_muna"],
        "created_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user'],
        'status_ta'  => 1
    );

    $in = $db->insert("tugas_akhir",$data);

    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
  break;
  case 'in_jadwal':
    $data = array(
      "priode_muna" => $dec->dec($_POST['priode_muna']),
      "batas_awal"  => $_POST['batas_awal'],
      "batas_akhir" => $_POST['batas_akhir'],
      "tanggal_sidang"  => $_POST['tanggal_sidang'],
      "created_at"  => date('Y-m-d'),
      "last_updated" => $_SESSION['id_user']
    );

    $in = $db->insert("jadwal_muna",$data);

    if($in=true){
      echo "good";
    }else {
      return false;
    }
    break;
  case "delete":

    $db->delete("tugas_akhir","id_ta",$_GET["id"]);
    break;
  
  case "delete_jadwal":
    $db->delete("jadwal_muna","id_muna",$_POST["id_data"]);
    break;

   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tugas_akhir","id_ta",$id);
         }
    }
    break;

  case "del_massal_jadwal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("jadwal_muna","id_muna",$id);
         }
    }
    break;


  case "up":

   $data = array(
      "kode_fak" => $_POST["kode_fak"],
      "kode_jurusan" => $_POST["kode_jurusan"],
      "nim" => $_POST["nim"],
      "judul_ta"=>$_POST["judul_ta"],
      "pembimbing_1" => $_POST["pembimbing_1"],
      "pembimbing_2" => $_POST["pembimbing_2"],
      "updated_at" => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
   );

    $up = $db->update("tugas_akhir",$data,"id_ta",$_POST["id"]);

    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
    case "up_mhs":

     $data = array(
        "kode_fak" => $_POST["kode_fak"],
        "kode_jurusan" => $_POST["kode_jurusan"],
        "nim" => $_POST["nim"],
        "judul_ta"=>$_POST["judul_ta"],
        "updated_at" => date('Y-m-d'),
        "last_update" => $_SESSION['id_user']
     );

      $up = $db->update("tugas_akhir",$data,"id_ta",$_POST["id"]);

      if ($up=true) {
        echo "good";
      } else {
        return false;
      }
      break;
    case 'up_jadwal':
      $data = array(
        "priode_muna" => $dec->dec($_POST['priode_muna']),
        "batas_awal"  => $_POST['batas_awal'],
        "batas_akhir" => $_POST['batas_akhir'],
        "tanggal_sidang"  => $_POST['tanggal_sidang'],
        "last_updated" => $_SESSION['id_user'],
        "updated_at"  => date('Y-m-d')
      );

      $up = $db->update("jadwal_muna",$data,"id_muna",$_POST["id"]);

      if($up=true){
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
