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

    $sql = "SELECT t.id_pertemuan, vj.jam_mulai AS jam_mulai_asli, vj.jam_selesai AS jam_selesai_asli,
                   vj.hari, vj.ruang_id, vnk.sks
            FROM tb_data_kelas_pertemuan t
            INNER JOIN kelas k ON t.kelas_id = k.kelas_id
            INNER JOIN view_jadwal vj ON t.jadwal_id = vj.jadwal_id
            INNER JOIN view_nama_kelas vnk ON t.kelas_id = vnk.kelas_id
            $where_clause
            ORDER BY vj.ruang_id, vj.hari, vj.jam_mulai ASC";

    $records = $db->query($sql);

    // Track jam selesai PUASA per ruangan+hari (untuk chaining)
    $prev_selesai_puasa = [];

    foreach ($records as $r) {
      $key = $r->ruang_id . '|' . strtolower($r->hari);
      $is_jumat = (strtolower($r->hari) == 'jumat');

      // Konversi jam ASLI ke menit
      $parts_mulai = explode(':', $r->jam_mulai_asli);
      $parts_selesai = explode(':', $r->jam_selesai_asli);
      $mulai_asli_menit = (int) $parts_mulai[0] * 60 + (int) $parts_mulai[1];
      $selesai_asli_menit = (int) $parts_selesai[0] * 60 + (int) $parts_selesai[1];

      // 1. Jam mulai puasa:
      //    - Jika back-to-back dengan kelas sebelumnya di ruangan+hari yang sama
      //      (mulai_asli <= selesai_asli kelas sebelumnya) → chain dari selesai puasa sebelumnya
      //    - Jika tidak → pakai jam_mulai asli
      if (isset($prev_selesai_puasa[$key]) && $mulai_asli_menit <= $prev_selesai_puasa[$key]['selesai_asli']) {
        $mulai_menit = $prev_selesai_puasa[$key]['selesai_puasa'];
      } else {
        $mulai_menit = $mulai_asli_menit;
      }

      // 2. SKS dari view_nama_kelas
      $jumlah_sks = (int) $r->sks;
      if ($jumlah_sks < 1)
        $jumlah_sks = 1;

      // 3. Jam selesai = jam_mulai_puasa + (sks × 40 menit)
      $selesai_menit = $mulai_menit + ($jumlah_sks * 40);

      // 4. Khusus Jumat: istirahat 12:00-12:40 (+40 menit)
      if ($is_jumat && $mulai_menit < 720 && $selesai_menit > 720) {
        $selesai_menit += 40;
      }

      // 5. Simpan untuk chaining kelas berikutnya di ruangan+hari yang sama
      $prev_selesai_puasa[$key] = [
        'selesai_puasa' => $selesai_menit,
        'selesai_asli' => $selesai_asli_menit,
      ];

      // 6. Format dan simpan ke DB
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