<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';


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
                    'color' => 'ff0000'
                    ),
                'cells' => array(
                    'A1',
                    'B1',
                    'D1',
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
            );

//column width
$col_width = array(
  1 => 16,
  2 => 21,
  3 => 35,
  4 => 18,
  5 => 18,
  6 => 18,
  7 => 18,
  8 => 18,
  9 => 18,
  );
$writer->setColWidth($col_width);

$header = array(
  'Semester'=>'string',
  'Kode matakuliah'=>'string',
  'Nama Matakuliah ' => 'string',
  'Nama Kelas' => 'string',
  'Kode Ruang' => 'string',
  'Hari' => 'String',
  'Jam Mulai' => 'String',
  'Jam Selesai' => 'String',
  'Kode Prodi' => 'String'


);

$data_rec = array();
$kode_prodi = "";
$periode = "";
$kode_tagihan = "";
$hari_filter= "";
$matkul_filter = "";
$jenis_kelas="";
$jur_filter="";
 // if (isset($_GET['jur_filter'])) {

  if ($_GET['jur_filter']!='all') {
    $jur_filter = ' and vnk.kode_jur="'.$_GET['jur_filter'].'"';
  }

  if ($_GET['sem_filter']!='all') {
    $sem_filter = ' and vnk.sem_id="'.$_GET['sem_filter'].'"';
  }

  if ($_GET['matkul_filter']!='all') {
    $matkul_filter = ' and vnk.id_matkul="'.$_GET['matkul_filter'].'"';
  }
  if ($_GET['hari_filter']!='all') {
    $hari_filter = ' and vj.hari="'.$_GET['hari_filter'].'"';
  }
  if ($_GET['jenis_kelas']!='all') {
    $jenis_kelas = ' and jenis_kelas.id="'.$_GET['jenis_kelas'].'"';
  }



    //    $order_by = "order by berlaku_angkatan ASC";

/*    echo "select vnk.kode_jur, vj.jam_mulai,vj.jam_selesai, vj.hari, vj.ruang_id, vnk.kode_paralel, vnk.kode_mk,sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
   from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
     where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas";*/
        $temp_rec = $db->query("select  r.kode_ruang,vnk.kelas_nama,m.nama_mk AS nama_matkul,vj.sem_id,vnk.nm_paralel, vnk.kode_jur, vj.jam_mulai,vj.jam_selesai, vj.hari, vj.ruang_id, vnk.kode_paralel, vnk.kode_mk,sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
   from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
LEFT JOIN matkul m ON m.id_matkul=vnk.id_matkul 
LEFT JOIN ruang_ref r ON r.ruang_id=vj.ruang_id
     where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas ");
       /* echo "select m.nama_mk AS nama_matkul,vj.sem_id,vnk.nm_paralel, vnk.kode_jur, vj.jam_mulai,vj.jam_selesai, vj.hari, vj.ruang_id, vnk.kode_paralel, vnk.kode_mk,sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
   from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
LEFT JOIN matkul m ON m.kode_mk=vnk.kode_mk 
     where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas "; exit();*/
                    foreach ($temp_rec as $key) {

                      $data_rec[] = array(
                                      $key->sem_id,
                                      $key->kode_mk,
                                      $key->nama_matkul,
                                      $key->kelas_nama,
                                      $key->kode_ruang,
                                      $key->hari,
                                      $key->jam_mulai,
                                      $key->jam_selesai,
                                      $key->kode_jur


                        );

            }


$filename = 'jadwal_kuliah_'.$key->sem_id.'.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Tagihan Mhs', $header, $style);
$writer->writeToStdOut();
exit(0);
?>