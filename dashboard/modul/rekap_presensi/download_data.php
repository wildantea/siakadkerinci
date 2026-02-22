<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';


$jur_filter = "";
$fakultas   = ""; // inisialisasi supaya tidak undefined
$akses_prodi = get_akses_prodi();
$akses_jur   = $db->fetch_custom_single("SELECT GROUP_CONCAT(kode_jur) AS kode_jur FROM view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
    $jur_filter = "AND vnk.kode_jur IN(".$akses_jur->kode_jur.")";
} else {
    $jur_filter = "AND vnk.kode_jur IN(0)";
}

// default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter     = "AND vnk.sem_id='".$semester_aktif->id_semester."'";
$matkul_filter  = "";
$hari_filter    = "";
$status_hadir    = "";

if (isset($_POST['jur_filter'])) {
    if ($_POST['jur_filter'] != 'all') {
        $jur_filter = ' AND vnk.kode_jur="'.$_POST['jur_filter'].'"';
    }
    if ($_POST['fakultas'] != 'all') {
        $fakultas = getProdiFakultas('vnk.kode_jur', $_POST['fakultas']);
    }
    if ($_POST['sem_filter'] != 'all') {
        $sem_filter = ' AND vnk.sem_id="'.$_POST['sem_filter'].'"';
    }
    if ($_POST['matkul_filter'] != 'all') {
        $matkul_filter = ' AND vnk.id_matkul="'.$_POST['matkul_filter'].'"';
    }
    if ($_POST['hari_filter'] != 'all') {
        $hari_filter = ' AND vj.hari="'.$_POST['hari_filter'].'"';
    }
}


$writer = new XLSXWriter();
$style =
        array (
                      array(
              'border' => array(
                'style' => 'thin',
                'color' => '000000'
                ),
            'allfilleddata' => true
            ),
                        array(
                'fill' => array(
                    'color' => '00ff00'
                    ),
                'cells' => array(
                    'A1',
                    'B1',
                    'C1',
                    'D1',
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    'J1',
                    'K1',
                    'L1',
                    'M1',
                    'N1',
                    'O1',
                    'P1',
                    'Q1',
                    'R1',
                    'S1',
                    'T1',
                    'U1',
                    'V1',
                    'W1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
                )
            );

//column width
$col_width = array(
  1 => 66,
  2 => 30,
  3 => 30,
  4 => 25,
  5 => 29,
  6 => 44,
  7 => 15,
  8 => 25,
  9 => 15,
  10 => 15,
  11 => 15,
  12 => 15,
  13 => 15,
  14 => 15,
  15 => 15,
  16 => 15,
  17 => 15,
  18 => 15,
  19 => 15,
  20 => 15,
  21 => 15,
  22 => 15,
  23 => 15
  ); 
$writer->setColWidth($col_width);

# Matakuliah  Kelas Jadwal  Dosen

$header = array(
  'Nama Matakuliah'=>'string',
  'Kelas'=>'string',
  'Hari/Jam'=>'string',
  'Ruang'=>'string',
  'Dosen'=>'string',
   'Jurusan' => 'string',
  '1' => 'string',
  '2' => 'string',
  '3' => 'string',
  '4' => 'string',
  '5' => 'string',
  '6' => 'string',
  '7' => 'string',
  '8' => 'string',
  '9' => 'string',
  '10' => 'string',
  '11' => 'string',
  '12' => 'string',
  '13' => 'string',
  '14' => 'string',
  '15' => 'string',
  '16' => 'string',
 
  'Jml Hadir' => 'string'
);


$pertemuans = [];
$hadirs     = [];
$sesuai_jadwals = [];
$tanggal_pertemuan = [];
for ($i=1; $i<=16; $i++) {

$tanggal_absen[] = "(
  SELECT 
    JSON_UNQUOTE(
      JSON_EXTRACT(
        t.kehadiran_dosen,
        '$[0].tanggal_absen'
      )
    )
  FROM tb_data_kelas_pertemuan t
  WHERE t.kelas_id = vnk.kelas_id
    AND t.pertemuan = '$i'
limit 1) AS tanggal_absen_pert_$i";

$tanggal_pertemuan[] = "(
  SELECT 
  tanggal_pertemuan 
  FROM tb_data_kelas_pertemuan t
  WHERE t.kelas_id = vnk.kelas_id
    AND t.pertemuan = '$i'
limit 1) AS tanggal_pertemuan_$i";


}

$query_tanggal_absen = implode(", ", $tanggal_absen);
$query_tanggal_pertemuan = implode(", ", $tanggal_pertemuan);

$data_rec = array();
   
        $order_by = "order by nama_dosen ASC";

    
$temp_rec = $db->query("
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
      $query_tanggal_absen,
      $query_tanggal_pertemuan,
      vnk.kelas_id,
      nama_dosen
    FROM view_nama_kelas vnk
    INNER JOIN view_dosen_kelas_single ON vnk.kelas_id=view_dosen_kelas_single.id_kelas
    INNER JOIN view_jadwal vj ON vnk.kelas_id=vj.kelas_id
    WHERE vnk.kelas_id IS NOT NULL
    $sem_filter $jur_filter $hari_filter $matkul_filter $fakultas
    ");

foreach ($temp_rec as $value) {

    // loop 16 pertemuan
    $jml_hadir = 0;
$hadir_list = [];

for ($i=1; $i<=16; $i++) { 
    $tanggal_absen = 'tanggal_absen_pert_'.$i;
    $tanggal_pertemuan = 'tanggal_pertemuan_'.$i;

    $hari = getHariFromDate($value->$tanggal_absen);
    $jam_absen = substr($value->$tanggal_absen, -8);

    if (!empty($value->$tanggal_absen) && (int)$value->$tanggal_absen > 0) {
        $jml_hadir++;
        if (
            $value->$tanggal_pertemuan==substr($value->$tanggal_absen,0,10) 
            && $jam_absen >= $value->jam_mulai 
            && $jam_absen <= $value->jam_selesai 
            && strtolower($hari)==strtolower($value->hari)
        ) {
            $hadir_list[$i] = "Hadir Tepat";
        } else {
            $hadir_list[$i] = "Hadir Telat";
        }
    } else {
        $hadir_list[$i] = "";
    }
}

// gabungkan data utama + 16 hadir + jml_hadir
$data_rec[] = array_merge(
    [
        $value->nm_matkul.'('.$value->sks.' SKS) - SMT '.$value->sem_matkul,
        $value->nama_kelas,
        ucwords($value->hari).', '.substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai, 0,5),
        $value->nm_ruang,
        $value->nama_dosen,
        $value->jurusan,
    ],
    $hadir_list,   // 16 data hadir masuk ke array utama
    [$jml_hadir]   // terakhir jumlah hadir

);

            }

$filename = 'Rekap_Kehadiran.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Hadir Dosen', $header, $style);
$writer->writeToStdOut();
exit(0);
?>