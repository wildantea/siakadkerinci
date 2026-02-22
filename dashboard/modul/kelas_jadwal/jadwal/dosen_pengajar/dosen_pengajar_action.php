<?php
session_start();
include "../../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'insert_pengajar':
 // echo "<pre>";
     if(isset($_POST["penanggung_jawab"])=="on")
    {
      $is_penanggung_jawab = 'Y';
      //$db->update('tb_data_kelas_dosen',array('penanggung_jawab' => 'N'),'kelas_id',$_POST['kelas_id']);
    } else {
      $is_penanggung_jawab = 'N';
    }

  if (isset($_POST['jadwal'])) {
    $where_jadwal = "";
        $jadwal_id = implode(",", $_POST['jadwal']);
        $where_jadwal = "where jadwal_id in($jadwal_id)";

           $check_jadwal_dosen = array(
      'sem_id' => $_POST['sem_id'],
      'nip' => $_POST['pengajar']
      );
   $kelas_info = $db->fetchSingleRow('view_kelas','kelas_id',$_POST['kelas_id']);
   //get jadwal kelas
   $jadwal_kelas = $db->query("select * from tb_data_kelas_jadwal $where_jadwal");
   if ($jadwal_kelas->rowCount()>0) {
     foreach ($jadwal_kelas as $kelas) {
         $check_bentrok_dosen = $db->fetchCustomSingle("select * from view_jadwal_dosen where
                                    ('".$kelas->jam_mulai."' > jam_mulai 
                                    and '".$kelas->jam_mulai."' < jam_selesai 
                                    or '".$kelas->jam_selesai."'> jam_mulai 
                                    and '".$kelas->jam_selesai."' < jam_selesai 
                                    or jam_mulai > '".$kelas->jam_mulai."' 
                                    and jam_mulai <'".$kelas->jam_selesai."' 
                                    or jam_selesai > '".$kelas->jam_mulai."' 
                                    and jam_selesai < '".$kelas->jam_selesai."' 
                                    or '".$kelas->jam_mulai."'=jam_mulai and '".$kelas->jam_selesai."'=jam_selesai) 
                                    and id_hari='".$kelas->id_hari."'
                                    and sem_id=? and nip=?",$check_jadwal_dosen);
          if ($check_bentrok_dosen) {
                action_response("Maaf Dosen $check_bentrok_dosen->nama_gelar punya jadwal mengajar di Prodi ".$kelas_info->nama_jurusan."  Hari $check_bentrok_dosen->nama_hari Jam ".$check_bentrok_dosen->jam_mulai." S/d ".$check_bentrok_dosen->jam_selesai." Matakuliah $kelas_info->nama_mk Kelas $check_bentrok_dosen->kls_nama");
        }
     }
   }

         foreach ($_POST['jadwal'] as $jd) {
        $array_insert_dosen = array(
          'kelas_id' => $_POST['kelas_id'],
          'id_jadwal' => $jd,
          'nip_dosen' => $_POST['pengajar'],
          'dosen_ke' =>  $_POST['dosen_ke'],
          'penanggung_jawab' => $is_penanggung_jawab,
        );
        $db->insert('tb_data_kelas_dosen',$array_insert_dosen);
      }

    } else {
        $array_insert_dosen = array(
          'kelas_id' => $_POST['kelas_id'],
          'nip_dosen' => $_POST['pengajar'],
          'dosen_ke' =>  $_POST['dosen_ke'],
          'penanggung_jawab' => $is_penanggung_jawab,
        );
        $db->insert('tb_data_kelas_dosen',$array_insert_dosen);
    }
    action_response($db->getErrorMessage());
    break;
    case 'update_pengajar':
     if(isset($_POST["penanggung_jawab"])=="on")
    {
      $is_penanggung_jawab = 'Y';
      //$db->update('tb_data_kelas_dosen',array('penanggung_jawab' => 'N'),'kelas_id',$_POST['kelas_id']);
    } else {
      $is_penanggung_jawab = 'N';
    }

  if (isset($_POST['jadwal'])) {
    $where_jadwal = "";
    $jadwal_id = implode(",", $_POST['jadwal']);
    $where_jadwal = "where jadwal_id in($jadwal_id)";
    $kelas_info = $db->fetchSingleRow('view_kelas','kelas_id',$_POST['kelas_id']);
    $check_jadwal_dosen = array(
      'sem_id' => $kelas_info->sem_id,
      'nip' => $_POST['pengajar']
    );
   //get jadwal kelas
   $jadwal_kelas = $db->query("select * from tb_data_kelas_jadwal $where_jadwal");
   if ($jadwal_kelas->rowCount()>0) {
     foreach ($jadwal_kelas as $kelas) {
         $check_bentrok_dosen = $db->fetchCustomSingle("select * from view_jadwal_dosen where
                                    ('".$kelas->jam_mulai."' > jam_mulai 
                                    and '".$kelas->jam_mulai."' < jam_selesai 
                                    or '".$kelas->jam_selesai."'> jam_mulai 
                                    and '".$kelas->jam_selesai."' < jam_selesai 
                                    or jam_mulai > '".$kelas->jam_mulai."' 
                                    and jam_mulai <'".$kelas->jam_selesai."' 
                                    or jam_selesai > '".$kelas->jam_mulai."' 
                                    and jam_selesai < '".$kelas->jam_selesai."' 
                                    or '".$kelas->jam_mulai."'=jam_mulai and '".$kelas->jam_selesai."'=jam_selesai) 
                                    and id_hari='".$kelas->id_hari."'
                                    and sem_id=? and nip=? and jadwal_id!='$kelas->jadwal_id'",$check_jadwal_dosen);
          if ($check_bentrok_dosen) {
                action_response("Maaf Dosen $check_bentrok_dosen->nama_gelar punya jadwal mengajar di Prodi ".$kelas_info->nama_jurusan."  Hari $check_bentrok_dosen->nama_hari Jam ".$check_bentrok_dosen->jam_mulai." S/d ".$check_bentrok_dosen->jam_selesai." Matakuliah $kelas_info->nama_mk Kelas $check_bentrok_dosen->kls_nama");
        }
     }
   }


   $db->query('delete from tb_data_kelas_dosen where kelas_id=? and nip_dosen=?',array('kelas_id' => $_POST['kelas_id'],'nip_dosen' => $_POST['pengajar']));

         foreach ($_POST['jadwal'] as $jd) {
        $array_insert_dosen = array(
          'kelas_id' => $_POST['kelas_id'],
          'id_jadwal' => $jd,
          'nip_dosen' => $_POST['pengajar'],
          'dosen_ke' =>  $_POST['dosen_ke'],
          'penanggung_jawab' => $is_penanggung_jawab,
        );
        $db->insert('tb_data_kelas_dosen',$array_insert_dosen);
      }

    } else {
         $db->query('delete from tb_data_kelas_dosen where kelas_id=? and nip_dosen=?',array('kelas_id' => $_POST['kelas_id'],'nip_dosen' => $_POST['pengajar']));

        $array_insert_dosen = array(
          'kelas_id' => $_POST['kelas_id'],
          'nip_dosen' => $_POST['pengajar'],
          'dosen_ke' =>  $_POST['dosen_ke'],
          'penanggung_jawab' => $is_penanggung_jawab,
        );
        $db->insert('tb_data_kelas_dosen',$array_insert_dosen);
    }
    action_response($db->getErrorMessage());
    break;
  case "delete":
    $db->delete("tb_data_kelas_dosen","id",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
}

?>