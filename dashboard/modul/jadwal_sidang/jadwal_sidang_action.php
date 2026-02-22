<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'pilih_dosen':
      $q = $db2->query("select * from view_dosen where id_dosen ='".$_POST['id_dosen']."'");
      foreach ($q as $dosen_kelas) {
        echo "<tr class='komponen_list'>                     
                      <td>$dosen_kelas->nip 
                      <input type='hidden' name='id_dosen[]' value='$dosen_kelas->id_dosen'></td>
                      <td>$dosen_kelas->nama_gelar</td>
                      <td><input type='text' required style='width:100px' class='dosen-ke' name='penguji_ke[]'></td>             
                      <td><span class='btn btn-danger hapus_komponen_dosen btn-xs'><i class='fa fa-trash'></i></span></td>
                     </tr>";
      }

    break;
  case 'in_massal':
    $data_ids = $_REQUEST["id_pendaftaran"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
      $db2->begin_transaction();
        foreach($data_id_array as $id) {
          $db2->query("delete from tb_data_pendaftaran_jadwal_ujian where id_pendaftaran=?",array('id_jadwal_ujian' => $id));
            $data = array(
                "id_pendaftaran" => $id,
                "tanggal_ujian" => $_POST["tanggal_ujian"],
               // "jam_mulai" => $_POST["jam_mulai"],
               // "jam_selesai" => $_POST["jam_selesai"],
                "tempat" => $_POST['tempat'],
                "date_created" => date('Y-m-d H:i:s')
            );
            if ($_POST['tempat']=='Ruangan') {
              $data['id_ruang'] = $_POST['ruang_id'];
            }
              $in = $db2->insert("tb_data_pendaftaran_jadwal_ujian",$data);
              if ($in) {
                $last_id = $db2->getLastInsertId();
                if (isset($_POST['id_dosen'])) {
                  $i=1;
                  foreach ($_POST['id_dosen'] as $key => $value) {
                    $array_input_dosen_penguji[] = array(
                      'id_jadwal_ujian' => $last_id,
                      'nip_dosen' => $value,
                      'penguji_ke' => $i
                    );
                    $i++;
                  }
                  $insert_penguji = $db2->insertMulti('tb_data_pendaftaran_penguji',$array_input_dosen_penguji);
                  if ($insert_penguji==false) {
                    $db2->rollback();
                  }
                }
              }
              $i=0;
              $array_input_dosen_penguji = array();
         }
        $db2->commit();
    }
    action_response($db2->getErrorMessage());

    break;
  case "in":
  
  $data = array(
      "id_pendaftaran" => $_POST["id_pendaftaran"],
      "tanggal_ujian" => $_POST["tanggal_ujian"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
      "tempat" => $_POST['tempat'],
      "date_created" => date('Y-m-d H:i:s')
  );
  if ($_POST['tempat']=='Ruangan') {
    $data['id_ruang'] = $_POST['ruang_id'];
  }
    $db2->begin_transaction();
    $in = $db2->insert("tb_data_pendaftaran_jadwal_ujian",$data);
    if ($in) {
      $last_id = $db2->getLastInsertId();
      if (isset($_POST['id_dosen'])) {
        $i=1;
        foreach ($_POST['id_dosen'] as $key => $value) {
          $array_input_dosen_penguji[] = array(
            'id_jadwal_ujian' => $last_id,
            'nip_dosen' => $value,
            'penguji_ke' => $i
          );
          $i++;
        }
        $insert_penguji = $db2->insertMulti('tb_data_pendaftaran_penguji',$array_input_dosen_penguji);
        if ($insert_penguji==false) {
          $db2->rollback();
        }
      }
    }
    
     $db2->commit();
    action_response($db2->getErrorMessage());
    break;
  case "delete":
    $id_jadwal = $db2->fetchSingleRow("tb_data_pendaftaran_jadwal_ujian","id_pendaftaran",$_POST['id']);
    if ($id_jadwal) {
      $db2->delete("tb_data_pendaftaran_jadwal_ujian","id_jadwal_ujian",$id_jadwal->id_jadwal_ujian);
    }
    
    
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":

    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_pendaftaran_jadwal_ujian","id_pendaftaran",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":

  $data = array(
      "tanggal_ujian" => $_POST["tanggal_ujian"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
      "tempat" => $_POST['tempat'],
      "date_updated" => date('Y-m-d H:i:s')
  );
  if ($_POST['tempat']=='Ruangan') {
    $data['id_ruang'] = $_POST['ruang_id'];
  }
    $db2->begin_transaction();
    $up = $db2->update("tb_data_pendaftaran_jadwal_ujian",$data,"id_jadwal_ujian",$_POST["id"]);
    if ($up) {
      if (isset($_POST['id_dosen'])) {
        $i=1;
        foreach ($_POST['id_dosen'] as $key => $value) {
          $array_input_dosen_penguji[] = array(
            'id_jadwal_ujian' => $_POST['id'],
            'nip_dosen' => $value,
            'penguji_ke' => $i
          );
          $i++;
        }

          $db2->query("delete from tb_data_pendaftaran_penguji where id_jadwal_ujian=?",array('id_jadwal_ujian' => $_POST['id']));
          $insert_penguji = $db2->insertMulti('tb_data_pendaftaran_penguji',$array_input_dosen_penguji);
          if ($insert_penguji==false) {
            $db2->rollback();
          }
        
      }
    }
    
     $db2->commit();


    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>