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
    $jur_filter = $_POST['jur_puasa'];
    $sem_filter = $_POST['sem_puasa'];
    $matkul_filter = $_POST['matkul_puasa'];
    $hari_filter = $_POST['hari_puasa'];
    $mulai_pertemuan = (int) $_POST['mulai_pertemuan'];
    $sampai_pertemuan = (int) $_POST['sampai_pertemuan'];

    $where_clause = " WHERE t.pertemuan >= $mulai_pertemuan AND t.pertemuan <= $sampai_pertemuan ";
    $where_clause .= " AND k.sem_id = '$sem_filter' ";

    if ($jur_filter != 'all') {
      $where_clause .= " AND ku.kode_jur = '$jur_filter' ";
    }
    if ($matkul_filter != 'all') {
      $where_clause .= " AND k.id_matkul = '$matkul_filter' ";
    }
    if ($hari_filter != 'all') {
      $where_clause .= " AND vj.hari = '$hari_filter' ";
    }

    $sql = "SELECT t.id_pertemuan, t.jam_mulai, t.jam_selesai, vj.hari 
            FROM tb_data_kelas_pertemuan t
            INNER JOIN kelas k ON t.kelas_id = k.kelas_id
            INNER JOIN matkul m ON k.id_matkul = m.id_matkul
            INNER JOIN kurikulum ku ON m.kur_id = ku.kur_id
            INNER JOIN view_jadwal vj ON t.jadwal_id = vj.jadwal_id
            $where_clause";

    $records = $db->query($sql);

    // Fungsi: snap jam_mulai ke slot Puasa terdekat (only for start time)
    if (!function_exists('snap_to_puasa_start')) {
      function snap_to_puasa_start($time_str, $hari)
      {
        if (!$time_str)
          return $time_str;
        $parts = explode(':', $time_str);
        if (count($parts) < 2)
          return $time_str;
        $minutes = (int) $parts[0] * 60 + (int) $parts[1];

        $is_jumat = (strtolower($hari) == 'jumat');

        // Waktu mulai normal jam ke I-IX
        $normal_start = $is_jumat
          ? [480, 530, 580, 630, 680, 810, 860, 910, 960]
          : [480, 530, 580, 630, 680, 780, 830, 880, 930];

        // Puasa: slot 40 menit. 
        // Senin-Kamis continuous
        // Jumat ada istirahat 12:00-12:40 (720-760)
        $puasa_start = $is_jumat
          ? [480, 520, 560, 600, 640, 680, 760, 800, 840]
          : [480, 520, 560, 600, 640, 680, 720, 760, 800];

        $min_diff = 9999;
        $closest_idx = 0;
        foreach ($normal_start as $idx => $norm_min) {
          $diff = abs($minutes - $norm_min);
          if ($diff < $min_diff) {
            $min_diff = $diff;
            $closest_idx = $idx;
          }
        }

        return $puasa_start[$closest_idx]; // kembalikan dalam menit
      }
    }

    foreach ($records as $r) {
      // 1. Hitung durasi asli dalam menit
      $parts_mulai = explode(':', $r->jam_mulai);
      $parts_selesai = explode(':', $r->jam_selesai);
      $menit_mulai_asli = (int) $parts_mulai[0] * 60 + (int) $parts_mulai[1];
      $menit_selesai_asli = (int) $parts_selesai[0] * 60 + (int) $parts_selesai[1];

      $is_jumat = (strtolower($r->hari) == 'jumat');

      // Koreksi pembacaan durasi asli (Potong jam istirahat normal jika jadwal aslinya memotong istirahat)
      // Istirahat normal Senin-Kamis: 12:10 s/d 13:00 (730 s/d 780 = 50 menit)
      // Istirahat normal Jumat:       12:10 s/d 13:30 (730 s/d 810 = 80 menit)
      $durasi_istirahat_asli = 0;
      if ($is_jumat && $menit_mulai_asli < 730 && $menit_selesai_asli > 730) {
        $durasi_istirahat_asli = 80;
      } elseif (!$is_jumat && $menit_mulai_asli < 730 && $menit_selesai_asli > 730) {
        $durasi_istirahat_asli = 50;
      }

      $durasi_asli_tanpa_istirahat = ($menit_selesai_asli - $menit_mulai_asli) - $durasi_istirahat_asli;

      // 2. Hitung jumlah SKS dari durasi bersih (1 SKS ≈ 50 menit)
      $jumlah_sks = round($durasi_asli_tanpa_istirahat / 50);
      if ($jumlah_sks < 1)
        $jumlah_sks = 1;

      // 3. Snap jam mulai ke slot Puasa terdekat
      $puasa_mulai_menit = snap_to_puasa_start($r->jam_mulai, $r->hari);

      // 4. Hitung jam selesai Puasa: mulai + (SKS × 40 menit)
      $puasa_selesai_menit = $puasa_mulai_menit + ($jumlah_sks * 40);

      // Tambahkan offset istirahat KHUSUS hari Jumat bulan Puasa (istirahat puasa: 12:00-12:40 / 720-760)
      if (strtolower($r->hari) == 'jumat') {
        if ($puasa_mulai_menit < 720 && $puasa_selesai_menit > 720) {
          $puasa_selesai_menit += 40;
        }
      }

      // 5. Format ke HH:MM:SS
      $p_mulai = sprintf("%02d:%02d:00", floor($puasa_mulai_menit / 60), $puasa_mulai_menit % 60);
      $p_selesai = sprintf("%02d:%02d:00", floor($puasa_selesai_menit / 60), $puasa_selesai_menit % 60);

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