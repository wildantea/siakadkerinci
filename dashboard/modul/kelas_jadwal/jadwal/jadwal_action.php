<?php
session_start();
include "../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'input_jadwal':
   

$kelas_data = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);



$index = 0;
$session_time = $db->fetch_custom_single("SELECT * FROM sesi_waktu ORDER BY jam_mulai ASC limit 1");
$satu_sesi = strtotime($session_time->jam_selesai) - strtotime($session_time->jam_mulai);
$minute_sesi = floor(($satu_sesi % 3600) / 60);

$total_menit = $kelas_data->sks * $minute_sesi;


$time = $_POST['time']; // Initial time
$minutesToAdd = $total_menit; // Minutes to add

// Convert $time to a timestamp
$timestamp = strtotime($time);

// Add 300 minutes (300 * 60 seconds)
$finishTimestamp = $timestamp + ($minutesToAdd * 60);

// Format the result
$jam_selesai = date('H:i', $finishTimestamp);




//delete all jadwal dan jadwal dosen
$db->query("delete from jadwal_kuliah where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));
$db->query("delete from dosen_kelas where id_kelas=?",array('id_kelas' => $_POST['kelas_id']));


$data_insert_jadwal = array(
                'kelas_id' => $_POST['kelas_id'],
                'hari' => $_POST['hari'],
                'ruang_id' => $_POST['ruang_id'],
                'jam_mulai' => $_POST['time'],
                'jam_selesai' => $jam_selesai
              );
$db->insert('jadwal_kuliah',$data_insert_jadwal);

$ke = 1;
$sks_ajar = $kelas_data->sks / count($_POST['pengajar']);

foreach ($_POST['pengajar'] as $nip_dosen) {
  $data_insert_dosen[] = array(
    'id_kelas' => $_POST['kelas_id'],
    'id_dosen' => $nip_dosen,
    'dosen_ke' => $ke,
    'jml_tm_renc' => 16,
    'jml_tm_real' => 16,
    'sks_ajar' => $sks_ajar
  );
 //  $db2->insert('dosen_kelas',$data_insert_dosen);
    $ke++;
}
$db->insertMulti('dosen_kelas',$data_insert_dosen);
   // $db2->insertMulti('dosen_kelas',$data_insert_dosen);
 action_response($db2->getErrorMessage());
    break;
    case 'update_jadwal':
//delete all jadwal dan jadwal dosen
$db->query("delete from jadwal_kuliah where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));
$db->query("delete from dosen_kelas where id_kelas=?",array('id_kelas' => $_POST['kelas_id']));

$data_insert_jadwal = array(
                'kelas_id' => $_POST['kelas_id'],
                'hari' => $_POST['hari'],
                'ruang_id' => $_POST['ruang_id'],
                'jam_mulai' => $_POST['time'],
                'jam_selesai' => $jam_selesai
              );
  $db->update('jadwal_kuliah',$data_insert_jadwal,'jadwal_id',$_POST['jadwal_id']);


  action_response($db->getErrorMessage());
      break;
  case "delete":

  
    $db->delete("jadwal_kuliah","jadwal_id",$_POST["id"]);

$db->query("delete from dosen_kelas where id_kelas=?",array('id_kelas' => $_POST['kelas_id']));


    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_kelas","kelas_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>