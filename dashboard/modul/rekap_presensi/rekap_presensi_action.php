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

    // time mapping func
    if (!function_exists('get_puasa_time')) {
      function get_puasa_time($time_str, $is_start, $hari)
      {
        if (!$time_str)
          return $time_str;
        $parts = explode(':', $time_str);
        if (count($parts) < 2)
          return $time_str;
        $minutes = (int) $parts[0] * 60 + (int) $parts[1];

        $is_jumat = (strtolower($hari) == 'jumat');

        if ($is_start) {
          $normal = $is_jumat ?
            [480, 530, 580, 630, 680, 810, 860, 910, 960] :
            [480, 530, 580, 630, 680, 780, 830, 880, 930];
          $puasa = [480, 520, 560, 600, 640, 680, 760, 800, 840];
        } else {
          $normal = $is_jumat ?
            [530, 580, 630, 680, 730, 860, 910, 960, 1010] :
            [530, 580, 630, 680, 730, 830, 880, 930, 980];
          $puasa = [520, 560, 600, 640, 680, 720, 800, 840, 880];
        }

        $min_diff = 9999;
        $closest_idx = 0;
        foreach ($normal as $idx => $norm_min) {
          $diff = abs($minutes - $norm_min);
          if ($diff < $min_diff) {
            $min_diff = $diff;
            $closest_idx = $idx;
          }
        }

        $puasa_min = $puasa[$closest_idx];
        $h = floor($puasa_min / 60);
        $m = $puasa_min % 60;
        return sprintf("%02d:%02d:00", $h, $m);
      }
    }

    foreach ($records as $r) {
      $p_mulai = get_puasa_time($r->jam_mulai, true, $r->hari);
      $p_selesai = get_puasa_time($r->jam_selesai, false, $r->hari);



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