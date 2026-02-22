<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_GET["act"]) {
  case "in":

    $folder = "file/";
    $ukuran = $_FILES['file']['size'];
    $ekstensi_diperbolehkan = array('pdf','docx','doc');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $ekstensi = strtolower(end($x));

    if($_FILES['file']['tmp_name'] == false){
      //set keterangan baru
      $keterangan_new="";
      $data_new=$db->query("select * from jenis_keluar where id_jns_keluar=".$_POST['jenis_keluar']."");
      foreach ($data_new as $dn) {
        $keterangan_new = "Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
      }

      $data = array(
          "nim"           => $_POST["nim"],
          "jenis_keluar"  => $_POST["jenis_keluar"],
          "tgl_keluar"    => $_POST["tgl_keluar"],
          "tgl_berakhir"  => $_POST["tgl_berakhir"],
          "keterangan"    => $keterangan_new,
          "kode_fak"      => $_POST["kode_fak"],
          "kode_jur"      => $_POST["kode_jur"]
      );

      $in_nofile = $db->insert("cuti_mahasiswa",$data);
       
      if ($in_nofile=true) {
        echo "good";
      } else {
        return false;
      }

    } else{
      //set keterangan baru
      $keterangan_new="";
      $data_new=$db->query("select * from jenis_keluar where id_jns_keluar='".$_POST['jenis_keluar']."'");
      foreach ($data_new as $dn) {
        $keterangan_new = "Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
      }

      if(in_array($ekstensi, $ekstensi_diperbolehkan) == true OR $nama == ""){
        if($ukuran < 1044070){      
          move_uploaded_file($_FILES['file']['tmp_name'],$folder.$nama);
          $data = array(
              "nim"           => $_POST["nim"],
              "jenis_keluar"  => $_POST["jenis_keluar"],
              "tgl_keluar"    => $_POST["tgl_keluar"],
              "tgl_berakhir"  => $_POST["tgl_berakhir"],
              "file_sk"       => $nama,
              "keterangan"    => $keterangan_new,
              "kode_fak"      => $_POST["kode_fak"],
              "kode_jur"      => $_POST["kode_jur"],
              "created_at"    => date("Y-m-d"),
              "last_update"   => $_SESSION["id_user"]
          );

          $in_file = $db->insert("cuti_mahasiswa",$data);
           
          if ($in_file=true) {
            echo "good";
          } else {
            echo "bad";
          }

        }else{
          echo 'UKURAN FILE TERLALU BESAR';
        }
      }else{
        echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
      }
    }
  break;
  case "in_mhs":
    $folder = "file/";
    $ukuran = $_FILES['file']['size'];
    $ekstensi_diperbolehkan = array('pdf','docx','doc');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $ekstensi = strtolower(end($x));

    $nim = $_SESSION['username'];
    $check = $db->check_exist('cuti_mahasiswa',array('nim'=>$nim));
    if($check == false){
      if($_FILES['file']['tmp_name'] == false){
        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar=".$_POST['jenis_keluar']."");
        foreach ($data_new as $dn) {
          $keterangan_new = "Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        $data = array(
            "nim"           => $_POST["nim"],
            "jenis_keluar"  => $_POST["jenis_keluar"],
            "keterangan"    => $keterangan_new,
            "kode_fak"      => $_POST["kode_fak"],
            "kode_jur"      => $_POST["kode_jur"],
            "created_at"    => date("Y-m-d"),
            "last_update"   => $_SESSION["id_user"]
        );

        $in_nofile = $db->insert("cuti_mahasiswa",$data);
         
        if ($in_nofile=true) {
          echo "good";
        } else {
          echo "bad";
        }

      } else{
        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar='".$_POST['jenis_keluar']."'");
        foreach ($data_new as $dn) {
          $keterangan_new = "<br/> Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        if(in_array($ekstensi, $ekstensi_diperbolehkan) == true OR $nama == ""){
          if($ukuran < 1044070){      
            move_uploaded_file($_FILES['file']['tmp_name'],$folder.$nama);
            $data = array(
                "nim"           => $_POST["nim"],
                "jenis_keluar"  => $_POST["jenis_keluar"],
                "file_sk"       => $nama,
                "keterangan"    => $keterangan_new,
                "kode_fak"      => $_POST["kode_fak"],
                "kode_jur"      => $_POST["kode_jur"],
                "updated_at"    => date("Y-m-d"),
                "last_update"   => $_SESSION["id_user"]
            );

            $in_file = $db->insert("cuti_mahasiswa",$data);
             
            if ($in_file=true) {
              echo "good";
            } else {
              echo "bad";
            }

          }else{
            echo 'UKURAN FILE TERLALU BESAR';
          }
        }else{
          echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
        }
      }
    } else{
      if($_FILES['file']['tmp_name'] == false){
        //set keterangan lama
        $keterangan_old="";
        $data_old=$db->query("select * from cuti_mahasiswa where nim=".$nim."");
        foreach ($data_old as $do) {
          $keterangan_old = $do->keterangan;
        }

        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar=".$_POST['jenis_keluar']."");
        foreach ($data_new as $dn) {
          $keterangan_new = "<br/> Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        $data = array(
            "nim"           => $_POST["nim"],
            "jenis_keluar"  => $_POST["jenis_keluar"],
            "keterangan"    => $keterangan_old.$keterangan_new,
            "kode_fak"      => $_POST["kode_fak"],
            "kode_jur"      => $_POST["kode_jur"]
        );

        $in_nofile = $db->update("cuti_mahasiswa",$data,"nim",$nim);
         
        if ($in_nofile=true) {
          echo "good";
        } else {
          echo "bad";
        }

      } else{
        //set keterangan lama
        $keterangan_old="";
        $data_old=$db->query("select * from cuti_mahasiswa where nim=".$nim."");
        foreach ($data_old as $do) {
          $keterangan_old = $do->keterangan;
        }
        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar='".$_POST['jenis_keluar']."'");
        foreach ($data_new as $dn) {
          $keterangan_new = "<br/> Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        if(in_array($ekstensi, $ekstensi_diperbolehkan) == true OR $nama == ""){
          if($ukuran < 1044070){      
            move_uploaded_file($_FILES['file']['tmp_name'],$folder.$nama);
            $data = array(
                "nim"           => $_POST["nim"],
                "jenis_keluar"  => $_POST["jenis_keluar"],
                "file_sk"       => $nama,
                "keterangan"    => $keterangan_old.$keterangan_new,
                "kode_fak"      => $_POST["kode_fak"],
                "kode_jur"      => $_POST["kode_jur"]
            );

            $in_file = $db->update("cuti_mahasiswa",$data,"nim",$nim);
             
            if ($in_file=true) {
              echo "good";
            } else {
              echo "bad";
            }

          }else{
            echo 'UKURAN FILE TERLALU BESAR';
          }
        }else{
          echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
        }
      }
    }
  break;
  case "delete":
    $data = $db->query("select * from cuti_mahasiswa where id_cuti = '".$_GET["id"]."'"); 
    foreach ($data as $key) {
      if(file_exists("file/".$key->file_sk)) {
        unlink("file/".$key->file_sk);
      }
    }
    $db->delete("cuti_mahasiswa","id_cuti",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("cuti_mahasiswa","id_cuti",$id);
         }
    }
    break;
  case "up":
    
    $folder = "file/";
    $ukuran = $_FILES['file']['size'];
    $ekstensi_diperbolehkan = array('pdf','docx','doc');
    $nama = $_FILES['file']['name'];
    $x = explode('.', $nama);
    $ekstensi = strtolower(end($x));

    $nim = $_SESSION['username'];
    $check = $db->check_exist('cuti_mahasiswa',array('nim'=>$nim));
    if($check == false){
      if($_FILES['file']['tmp_name'] == false){
        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar=".$_POST['jenis_keluar']."");
        foreach ($data_new as $dn) {
          $keterangan_new = "Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        $data = array(
            "nim"           => $_POST["nim"],
            "jenis_keluar"  => $_POST["jenis_keluar"],
            "tgl_keluar"    => $_POST["tgl_keluar"],
            "keterangan"    => $keterangan_new,
            "kode_fak"      => $_POST["kode_fak"],
            "kode_jur"      => $_POST["kode_jur"],
            "created_at"    => date("Y-m-d"),
            "last_update"   => $_SESSION["id_user"],
            "tgl_berakhir"  => $_POST["tgl_berakhir"]
        );

        $up_nofile = $db->update("cuti_mahasiswa",$data,"id_cuti",$_POST["id"]);
         
        if ($up_nofile=true) {
          echo "good";
        } else {
          echo false;
        }

      } else{
        //set keterangan baru
        $keterangan_new="";
        $data_new=$db->query("select * from jenis_keluar where id_jns_keluar='".$_POST['jenis_keluar']."'");
        foreach ($data_new as $dn) {
          $keterangan_new = "<br/> Telah ".$dn->ket_keluar." pada tanggal ".$_POST['tgl_keluar'];
        }

        if(in_array($ekstensi, $ekstensi_diperbolehkan) == true OR $nama == ""){
          if($ukuran < 1044070){      
            move_uploaded_file($_FILES['file']['tmp_name'],$folder.$nama);
            $data = array(
                "nim"           => $_POST["nim"],
                "jenis_keluar"  => $_POST["jenis_keluar"],
                "tgl_keluar"    => $_POST["tgl_keluar"],
                "file_sk"       => $nama,
                "keterangan"    => $keterangan_new,
                "kode_fak"      => $_POST["kode_fak"],
                "kode_jur"      => $_POST["kode_jur"],
                "updated_at"    => date("Y-m-d"),
                "last_update"   => $_SESSION["id_user"],
                "tgl_berakhir"  => $_POST["tgl_berakhir"]
            );

            $up_file = $db->update("cuti_mahasiswa",$data,"id_cuti",$_POST["id"]);
             
            if ($up_file=true) {
              echo "good";
            } else {
              echo false;
            }

          }else{
            echo 'UKURAN FILE TERLALU BESAR';
          }
        }else{
          echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
        }
      }
    } 

    break;
    case 'set_tgl':
        
        $data = array(
            "tgl_keluar"    => $_POST["tgl_keluar"],
            "created_at"    => date("Y-m-d"),
            "last_update"   => $_SESSION["id_user"],
            "tgl_berakhir"  => $_POST["tgl_berakhir"]
        );

        $set_tgl = $db->update("cuti_mahasiswa",$data,"id_cuti",$_POST["id"]);
         
        if ($set_tgl=true) {
          echo "good";
        } else {
          echo false;
        }
      break;
  default:
    # code...
    break;
}

?>