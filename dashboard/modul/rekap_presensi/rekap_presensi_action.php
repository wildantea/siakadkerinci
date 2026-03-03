<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":




    $data = array(
      "kode_paralel" => $_POST["kode_paralel"],
    );




    $in = $db->insert("kelas", $data);


    action_response($db->getErrorMessage());
    break;

  case "geser_jadwal":
    $jur_filter = $_POST['jur_geser'];
    $sem_filter = $_POST['sem_geser'];
    $matkul_filter = $_POST['matkul_geser'];
    $hari_filter = $_POST['hari_geser'];
    $mulai_pertemuan = (int) $_POST['mulai_pertemuan'];
    $jumlah_minggu = (int) $_POST['jumlah_minggu'];
    $hari_tambah = $jumlah_minggu * 7;

    // 1. Bangun kondisi WHERE berdasarkan filter form (mirip rekap_presensi_data.php)
    $where_clause = " WHERE t.pertemuan >= $mulai_pertemuan ";

    // Filter Semester (Wajib)
    $where_clause .= " AND k.sem_id = '$sem_filter' ";

    // Filter Prodi
    if ($jur_filter != 'all') {
      // Kita join ke kurikulum untuk dapat kode_jur karena di tb_data_kelas_pertemuan tidak ada kode_jur
      $where_clause .= " AND ku.kode_jur = '$jur_filter' ";
    }

    // Filter Matakuliah
    if ($matkul_filter != 'all') {
      $where_clause .= " AND k.id_matkul = '$matkul_filter' ";
    }

    // Filter Hari (Berdasarkan jadwal asli di view_jadwal atau kolom hari jika tersedia)
    if ($hari_filter != 'all') {
      $where_clause .= " AND vj.hari = '$hari_filter' ";
    }

    // 2. Jalankan Query Update dengan Join
    // Menggunakan DATE_ADD untuk menggeser tanggal_pertemuan
    $sql = "UPDATE tb_data_kelas_pertemuan t
            INNER JOIN kelas k ON t.kelas_id = k.kelas_id
            INNER JOIN matkul m ON k.id_matkul = m.id_matkul
            INNER JOIN kurikulum ku ON m.kur_id = ku.kur_id
            INNER JOIN view_jadwal vj ON t.jadwal_id = vj.jadwal_id
            SET t.tanggal_pertemuan = DATE_ADD(t.tanggal_pertemuan, INTERVAL $hari_tambah DAY)
            $where_clause";

    $db->query($sql);

    action_response($db->getErrorMessage());
    break;

  case "jadwal_puasa":
    $ruang_filter = $_POST['ruang_puasa'] ?? 'all';
    $sem_filter = $_POST['sem_puasa'] ?? '';
    $hari_filter = $_POST['hari_puasa'] ?? 'all';
    $mulai_pertemuan = (int) ($_POST['mulai_pertemuan'] ?? 1);
    $sampai_pertemuan = (int) ($_POST['sampai_pertemuan'] ?? 16);

    $where_clause = " WHERE t.pertemuan >= $mulai_pertemuan AND t.pertemuan <= $sampai_pertemuan ";
    $where_clause .= " AND k.sem_id = '$sem_filter' ";

    if ($ruang_filter != 'all') {
      $where_clause .= " AND vj.ruang_id = '$ruang_filter' ";
    }
    if ($hari_filter != 'all') {
      $where_clause .= " AND vj.hari = '$hari_filter' ";
    }

    $sql = "SELECT t.id_pertemuan, vj.jam_mulai AS jam_mulai_asli, vj.hari, vnk.sks
            FROM tb_data_kelas_pertemuan t
            INNER JOIN kelas k ON t.kelas_id = k.kelas_id
            INNER JOIN view_jadwal vj ON t.jadwal_id = vj.jadwal_id
            INNER JOIN view_nama_kelas vnk ON t.kelas_id = vnk.kelas_id
            $where_clause
            ORDER BY vj.hari, vj.jam_mulai ASC";

    $records = $db->query($sql);

    // =========================================================
    // Tabel snap: jam_mulai normal → jam_mulai Ramadan
    // Berdasarkan tabel "jam ke-" resmi
    // =========================================================
    // Senin–Kamis: tidak ada istirahat tengah hari
    $slot_normal_seninkamis = [480, 530, 580, 630, 680, 730, 780, 830, 880, 930]; // 08:00..15:30
    $slot_puasa_seninkamis = [480, 520, 560, 600, 640, 680, 720, 760, 800, 840]; // 08:00..14:00

    // Jumat: ada istirahat puasa 12:00–12:40 (40 menit)
    // Slot VII dst geser 40 menit
    $slot_normal_jumat = [480, 530, 580, 630, 680, 730, 780, 830, 880, 930];
    $slot_puasa_jumat = [480, 520, 560, 600, 640, 680, 760, 800, 840, 880]; // VII = 12:40 (setelah break)

    foreach ($records as $r) {
      $is_jumat = (strtolower($r->hari) == 'jumat');

      // Konversi jam_mulai asli ke menit
      $parts = explode(':', $r->jam_mulai_asli);
      $mulai_asli = (int) $parts[0] * 60 + (int) $parts[1];

      // Pilih tabel slot sesuai hari
      $slot_normal = $is_jumat ? $slot_normal_jumat : $slot_normal_seninkamis;
      $slot_puasa = $is_jumat ? $slot_puasa_jumat : $slot_puasa_seninkamis;

      // Cari index "jam ke-" terdekat dari jam_mulai asli
      $min_diff = 9999;
      $idx = 0;
      foreach ($slot_normal as $i => $n) {
        $diff = abs($mulai_asli - $n);
        if ($diff < $min_diff) {
          $min_diff = $diff;
          $idx = $i;
        }
      }

      // jam_mulai Ramadan = slot Ramadan sesuai jam ke-
      $mulai_menit = $slot_puasa[$idx];

      // SKS dari view_nama_kelas
      $jumlah_sks = (int) $r->sks;
      if ($jumlah_sks < 1)
        $jumlah_sks = 1;

      // jam_selesai = jam_mulai_puasa + (sks × 40 menit)
      $selesai_menit = $mulai_menit + ($jumlah_sks * 40);

      // Khusus Jumat: jika kelas melewati break 12:00–12:40, +40 menit
      if ($is_jumat && $mulai_menit < 720 && $selesai_menit > 720) {
        $selesai_menit += 40;
      }

      // Format dan simpan ke DB
      $p_mulai = sprintf("%02d:%02d:00", floor($mulai_menit / 60), $mulai_menit % 60);
      $p_selesai = sprintf("%02d:%02d:00", floor($selesai_menit / 60), $selesai_menit % 60);

      $db->update(
        "tb_data_kelas_pertemuan",
        ["jam_mulai" => $p_mulai, "jam_selesai" => $p_selesai],
        "id_pertemuan",
        $r->id_pertemuan
      );
    }

    action_response($db->getErrorMessage());
    break;


  case "delete":



    $db->delete("kelas", "kelas_id", $_GET["id"]);
    action_response($db->getErrorMessage());
    break;
  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if (!empty($data_id_array)) {
      foreach ($data_id_array as $id) {
        $db->delete("kelas", "kelas_id", $id);
      }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":

    $data = array(
      "kode_paralel" => $_POST["kode_paralel"],
    );






    $up = $db->update("kelas", $data, "kelas_id", $_POST["id"]);

    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>