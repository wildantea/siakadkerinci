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