<?php
session_start();
include "../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'input_jadwal':


    $kelas_data = $db->fetch_single_row("view_nama_kelas", "kelas_id", $_POST['kelas_id']);



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




    //========================================================
// AMAN: jadwal_kuliah — UPDATE jika sudah ada, INSERT jika belum
// (jadwal_id tidak berubah → referensi di tb_data_kelas_pertemuan tetap aman)
//========================================================
    $existing_jadwal = $db->fetch_custom_single(
      "SELECT jadwal_id FROM jadwal_kuliah WHERE kelas_id=?",
      ['kelas_id' => $_POST['kelas_id']]
    );

    var_dump($existing_jadwal);
    die;

    $data_jadwal = [
      'kelas_id' => $_POST['kelas_id'],
      'hari' => $_POST['hari'],
      'ruang_id' => $_POST['ruang_id'],
      'jam_mulai' => $_POST['time'],
      'jam_selesai' => $jam_selesai,
    ];

    if ($existing_jadwal) {
      $data_jadwal_up = $data_jadwal;
      unset($data_jadwal_up['kelas_id']);
      $db->update('jadwal_kuliah', $data_jadwal_up, 'jadwal_id', $existing_jadwal->jadwal_id);
    } else {
      $db->insert('jadwal_kuliah', $data_jadwal);
    }

    //========================================================
// AMAN: dosen_kelas — sync tanpa hapus semua
// Hapus hanya yang tidak ada di form, insert/update yang ada
//========================================================
    $nip_baru = $_POST['pengajar'] ?? [];
    $ke = 1;
    $sks_ajar = count($nip_baru) > 0 ? $kelas_data->sks / count($nip_baru) : $kelas_data->sks;

    // Ambil dosen lama
    $existing_dosen = $db->query(
      "SELECT id_dosen FROM dosen_kelas WHERE id_kelas=?",
      ['id_kelas' => $_POST['kelas_id']]
    );
    $nip_lama = [];
    foreach ($existing_dosen as $ed) {
      $nip_lama[] = $ed->id_dosen;
    }

    // Hapus dosen yang tidak ada di form
    $nip_dihapus = array_diff($nip_lama, $nip_baru);
    foreach ($nip_dihapus as $nip_del) {
      $db->query(
        "DELETE FROM dosen_kelas WHERE id_kelas=? AND id_dosen=?",
        ['id_kelas' => $_POST['kelas_id'], 'id_dosen' => $nip_del]
      );
    }

    // Insert atau update dosen dari form
    foreach ($nip_baru as $nip_dosen) {
      if (in_array($nip_dosen, $nip_lama)) {
        $db->query(
          "UPDATE dosen_kelas SET dosen_ke=?, jml_tm_renc=16, jml_tm_real=16, sks_ajar=? WHERE id_kelas=? AND id_dosen=?",
          [$ke, $sks_ajar, $_POST['kelas_id'], $nip_dosen]
        );
      } else {
        $db->insert('dosen_kelas', [
          'id_kelas' => $_POST['kelas_id'],
          'id_dosen' => $nip_dosen,
          'dosen_ke' => $ke,
          'jml_tm_renc' => 16,
          'jml_tm_real' => 16,
          'sks_ajar' => $sks_ajar,
        ]);
      }
      $ke++;
    }

    action_response($db2->getErrorMessage());
    break;
  case 'update_jadwal':

    $kelas_data2 = $db->fetch_single_row("view_nama_kelas", "kelas_id", $_POST['kelas_id']);
    $session_time2 = $db->fetch_custom_single("SELECT * FROM sesi_waktu ORDER BY jam_mulai ASC limit 1");
    $satu_sesi2 = strtotime($session_time2->jam_selesai) - strtotime($session_time2->jam_mulai);
    $minute_sesi2 = floor(($satu_sesi2 % 3600) / 60);
    $total_menit2 = $kelas_data2->sks * $minute_sesi2;
    $timestamp2 = strtotime($_POST['time']);
    $finishTimestamp2 = $timestamp2 + ($total_menit2 * 60);
    $jam_selesai2 = date('H:i', $finishTimestamp2);

    //========================================================
// AMAN: update_jadwal — UPDATE saja (tidak delete jadwal_kuliah)
//========================================================
    $data_update_jadwal = [
      'hari' => $_POST['hari'],
      'ruang_id' => $_POST['ruang_id'],
      'jam_mulai' => $_POST['time'],
      'jam_selesai' => $jam_selesai2,
    ];
    $db->update('jadwal_kuliah', $data_update_jadwal, 'jadwal_id', $_POST['jadwal_id']);


    action_response($db->getErrorMessage());
    break;
  case "delete":


    $db->delete("jadwal_kuliah", "jadwal_id", $_POST["id"]);

    $db->query("delete from dosen_kelas where id_kelas=?", array('id_kelas' => $_POST['kelas_id']));


    action_response($db->getErrorMessage());
    break;
  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if (!empty($data_id_array)) {
      foreach ($data_id_array as $id) {
        $db->delete("tb_data_kelas", "kelas_id", $id);
      }
    }
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>