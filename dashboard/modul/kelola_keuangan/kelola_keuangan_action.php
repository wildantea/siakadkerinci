<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":
    $nilai = $_POST["total_bayar"];
    $bayar = preg_replace("/[^A-Za-z0-9]/", "", $nilai);
    $kelas = $db->query('select kelas,nama from mahasiswa where nim=?',array('nim' => $_POST['nim']));

    $data = array(
        "nim"             => $_POST["nim"],
        "sem_id"          => $_POST["sem"],
        "reg_sks_teori"   => 0,
        "total_bayar"     => $bayar,
        "no_kwitansi"     => $_POST["no_kwitansi"],
        "created_at"      => date('Y-m-d'),
        "tgl_registrasi"  => date('Y-m-d'),
        "last_update"     => $_SESSION['id_user']
    );
  
    $kelas_manual = "";
    foreach ($kelas as $ks){
      $nama = substr($ks->nama, 0,1);
      if($ks->kelas == NULL){
        if ($nama == 'A') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }
        if ($nama == 'B') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }        
        if ($nama == 'C') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }        
        if ($nama == 'D') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }
        if ($nama == 'E') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }
        if ($nama == 'F') {
          $kelas_melekat = array(
            'kelas' => '01'
          );
        }
        if ($nama == 'G') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        }
        if ($nama == 'H') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        }           
        if ($nama == 'I') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        }   
        if ($nama == 'J') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        }
        if ($nama == 'K') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        }    
        if ($nama == 'L') {
          $kelas_melekat = array(
            'kelas' => '02'
          );
        } 
        if ($nama == 'M') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        } 
        if ($nama == 'N') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        }  
        if ($nama == 'O') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        }
        if ($nama == 'P') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        }
        if ($nama == 'Q') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        }
        if ($nama == 'R') {
          $kelas_melekat = array(
            'kelas' => '03'
          );
        }   
        if ($nama == 'S') {
          $kelas_melekat = array(
            'kelas' => '04'
          );
        }
        if ($nama == 'T') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'U') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'V') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'W') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'X') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'Y') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }
        if ($nama == 'Z') {
          $kelas_melekat = array(
            'kelas' => '05'
          );
        }                                                                               
      }
    }

    $set_kelas = $db->update('mahasiswa',$kelas_melekat,'nim',$_POST['nim']);
    $in = $db->insert("mhs_registrasi",$data);
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
  break;
  case "delete":
    
    
    
    $db->delete("mhs_registrasi","id_reg",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mhs_registrasi","id_reg",$id);
         }
    }
    break;
  case "up":
    $nilai = $_POST["total_bayar"];
    $bayar = preg_replace("/[^A-Za-z0-9]/", "", $nilai);
    $data = array(
      "nim" => $_POST["nim"],
      "sem_id" => $_POST["sem_id"],
      "total_bayar" => $bayar,
      "no_kwitansi" => $_POST["no_kwitansi"],
      "updated_at"  => date('Y-m-d'),
      "last_update" => $_SESSION['id_user']
    );
    
    $up = $db->update("mhs_registrasi",$data,"id_reg",$_POST["id"]);
    
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