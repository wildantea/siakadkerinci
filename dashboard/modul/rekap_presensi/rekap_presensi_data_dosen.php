<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nm_matkul',
    'nama_kelas',
    'jam_mulai',
    'nm_ruang',
    '(SELECT GROUP_CONCAT(DISTINCT nama_dosen SEPARATOR "#") 
        FROM view_dosen_kelas 
        WHERE view_dosen_kelas.id_kelas = vnk.kelas_id)',
    'jurusan',
    'vnk.kelas_id',
);

$datatable2->disableSearch = array('waktu', 'jurusan');
$datatable2->setNumberingStatus(1);

// ================= FILTER ===================
$jur_filter = "";
$fakultas = "";
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("SELECT GROUP_CONCAT(kode_jur) AS kode_jur FROM view_prodi_jenjang $akses_prodi");

if ($akses_jur) {
    $jur_filter = "AND vnk.kode_jur IN(" . $akses_jur->kode_jur . ")";
} else {
    $jur_filter = "AND vnk.kode_jur IN(0)";
}

$semester_aktif = $db->fetch_single_row("semester_ref", "aktif", 1);
$sem_filter = "AND vnk.sem_id='" . $semester_aktif->id_semester . "'";
$matkul_filter = "";
$hari_filter = "";

if (isset($_POST['sem_filter'])) {
    /*  if ($_POST['jur_filter'] != 'all') {
          $jur_filter = ' AND vnk.kode_jur="' . $_POST['jur_filter'] . '"';
      }
      if ($_POST['fakultas'] != 'all') {
          $fakultas = getProdiFakultas('vnk.kode_jur', $_POST['fakultas']);
      }

      if ($_POST['matkul_filter'] != 'all') {
          $matkul_filter = ' AND vnk.id_matkul="' . $_POST['matkul_filter'] . '"';
      }
      */

    if ($_POST['sem_filter'] != 'all') {
        $sem_filter = ' AND vnk.sem_id="' . $_POST['sem_filter'] . '"';
    }
    if ($_POST['hari_filter'] != 'all') {
        $hari_filter = ' AND vj.hari="' . $_POST['hari_filter'] . '"';
    }
}

$query_dosen = "";
if ($_SESSION['group_level'] == 'dosen') {
    $query_dosen = "AND view_dosen_kelas_single.id_dosen='" . $_SESSION['username'] . "'";
    $jur_filter = "";
}

$datatable2->setDebug(1);

// ========================
// Subquery builder
// ========================
$tanggal_absen = [];
$tanggal_keluar = [];
$tanggal_pertemuan = [];

for ($i = 1; $i <= 16; $i++) {

    $tanggal_absen[] = "(
  SELECT JSON_UNQUOTE(JSON_EXTRACT(t.kehadiran_dosen,'$[0].tanggal_absen'))
  FROM tb_data_kelas_pertemuan t
  WHERE t.kelas_id = vnk.kelas_id AND t.pertemuan = '$i'
) AS tanggal_absen_pert_$i";

    $tanggal_keluar[] = "(
  SELECT JSON_UNQUOTE(JSON_EXTRACT(t.kehadiran_dosen_keluar,'$[0].tanggal_absen'))
  FROM tb_data_kelas_pertemuan t
  WHERE t.kelas_id = vnk.kelas_id AND t.pertemuan = '$i'
) AS tanggal_absen_keluar_pert_$i";

    $tanggal_pertemuan[] = "(
  SELECT tanggal_pertemuan
  FROM tb_data_kelas_pertemuan t
  WHERE t.kelas_id = vnk.kelas_id AND t.pertemuan = '$i'
) AS tanggal_pertemuan_$i";
}

$q_absen = implode(", ", $tanggal_absen);
$q_keluar = implode(", ", $tanggal_keluar);
$q_tgl = implode(", ", $tanggal_pertemuan);

// ========================
// Query utama
// ========================

$query = $datatable2->execQuery("
SELECT 
  vj.hari,
  vj.jam_mulai,
  vj.jam_selesai,
  sem_matkul,
  nm_matkul,
  nama_kelas,
  vj.nm_ruang,
  vnk.id_matkul,
  CONCAT(jam_mulai,' - ',jam_selesai) AS jam,
  vnk.nama_mk,
  vnk.nama_kelas,
  vj.waktu,
  vnk.peserta_max,
  vnk.jurusan,
  vnk.sks,
  $q_absen,
  $q_keluar,
  $q_tgl,
  vnk.kelas_id,
  nama_dosen
FROM view_nama_kelas vnk
INNER JOIN view_dosen_kelas_single ON vnk.kelas_id=view_dosen_kelas_single.id_kelas
INNER JOIN view_jadwal vj ON vnk.kelas_id=vj.kelas_id
WHERE vnk.kelas_id IS NOT NULL
$sem_filter $jur_filter $hari_filter $matkul_filter $query_dosen
", $columns);

// ========================
// Output data
// ========================
$data = [];

foreach ($query as $value) {

    $ResultData = [];

    $ResultData[] = $value->nm_matkul . '(' . $value->sks . ' SKS) - SMT ' . $value->sem_matkul;
    $ResultData[] = $value->nama_kelas;

    if ($value->jam != '') {
        $ResultData[] = ucwords($value->hari) . ', ' . substr($value->jam_mulai, 0, 5) . " - " . substr($value->jam_selesai, 0, 5);
        $ResultData[] = $value->nm_ruang;
    } else {
        $ResultData[] = '<span class="btn btn-xs btn-primary edit-jadwal" 
                            data-act="add" data-toggle="tooltip" 
                            data-title="Atur Jadwal" 
                            data-id="' . $value->kelas_id . '">
                            <i class="fa fa-calendar"></i> Tambah Jadwal
                         </span>';
        $ResultData[] = '';
    }

    $ResultData[] = $value->nama_dosen ?: '';

    $jml_hadir = 0;        // rekap masuk
    $jml_keluar = 0;       // rekap keluar

    // ================= 16 PERTEMUAN =================
    for ($i = 1; $i <= 16; $i++) {

        $col_masuk = 'tanggal_absen_pert_' . $i;
        $col_keluar = 'tanggal_absen_keluar_pert_' . $i;
        $col_tgl = 'tanggal_pertemuan_' . $i;

        // default icon (belum absen)
        $icon_masuk = "<a class='btn btn-default'><i class='fa fa-close' style='color:red'></i></a>";
        $icon_keluar = "<a class='btn btn-default'><i class='fa fa-close' style='color:red'></i></a>";

        // ===== MASUK =====
        if ((int) $value->$col_masuk > 0) {
            $jml_hadir++;

            $hari_masuk = getHariFromDate($value->$col_masuk);
            $jam_masuk = substr($value->$col_masuk, -8);

            if (
                $value->$col_tgl == substr($value->$col_masuk, 0, 10) &&
                $jam_masuk >= $value->jam_mulai &&
                $jam_masuk <= $value->jam_selesai &&
                strtolower($hari_masuk) == strtolower($value->hari)
            ) {
                $icon_masuk = "<a class='btn btn-default'><i class='fa fa-check' style='color:green' data-toggle='tooltip' data-title='Masuk: " . $hari_masuk . ", " . $jam_masuk . "'></i></a>";
            } else {
                $icon_masuk = "<a class='btn btn-default'><i class='fa fa-check' style='color:orange' data-toggle='tooltip' data-title='Masuk: " . $hari_masuk . ", " . $jam_masuk . "'></i></a>";
            }
        }

        // ===== KELUAR =====
        if ((int) $value->$col_keluar > 0) {
            $jml_keluar++;

            $hari_keluar = getHariFromDate($value->$col_keluar);
            $jam_keluar = substr($value->$col_keluar, -8);

            if (
                $value->$col_tgl == substr($value->$col_keluar, 0, 10) &&
                $jam_keluar >= $value->jam_mulai &&
                $jam_keluar <= $value->jam_selesai &&
                strtolower($hari_keluar) == strtolower($value->hari)
            ) {
                $icon_keluar = "<a class='btn btn-default'><i class='fa fa-check' style='color:green' data-toggle='tooltip' data-title='Keluar: " . $hari_keluar . ", " . $jam_keluar . "'></i></a>";
            } else {
                $icon_keluar = "<a class='btn btn-default'><i class='fa fa-check' style='color:orange' data-toggle='tooltip' data-title='Keluar: " . $hari_keluar . ", " . $jam_keluar . "'></i></a>";
            }
        }

        // 1 KOLOM = 2 ICON (Masuk & Keluar)
        $ResultData[] = $icon_masuk . " " . $icon_keluar;
    }

    $ResultData[] = $value->jurusan;
    $ResultData[] = $jml_hadir;
    $ResultData[] = $jml_keluar;

    $data[] = $ResultData;
}

$datatable2->setData($data);
$datatable2->createData();
?>