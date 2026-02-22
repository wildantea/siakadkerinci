<?php
session_start();
include "../../../inc/config.php";
require_once '../../../inc/lib/Writer.php';


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
                    'C1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
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

$jur_kode = aksesProdi('view_matakuliah_kurikulum.kode_jur');

$periode = "";
$kur_id = "";
$sistem_kuliah = "";
$hari = "";
$matakuliah = "";
$fakultas = "";

    if (hasFakultas()) {
      if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
        $fakultas = getProdiFakultas('view_matakuliah_kurikulum.kode_jur',$_POST['fakultas']);
      }
    }

    if ($_POST['jur_filter']!='all') {
      $jur_kode = ' and view_matakuliah_kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and tb_data_kelas.sem_id="'.$_POST['periode'].'"';
    }
    if ($_POST['kurikulum']!='all') {
      $kur_id = ' and view_matakuliah_kurikulum.kur_id="'.$_POST['kurikulum'].'"';
    }
    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and tb_data_kelas.id_matkul="'.$_POST['matakuliah'].'"';
    }
    if ($_POST['sistem_kuliah']!='all') {
      $sistem_kuliah = ' and id_jenis_kelas="'.$_POST['sistem_kuliah'].'"';
    }
    if ($_POST['hari']!='all') {
      $hari = ' and id_hari="'.$_POST['hari'].'"';
    }


        $temp_rec = $db->query("select semester,kuliah_mode,nama_hari,jam_mulai,jam_selesai,kode_mk,nama_kurikulum,nama_mk,tb_data_kelas.kls_nama,
    nm_ruang,id_hari,jadwal,ruang_id,tb_data_kelas.catatan,tb_data_kelas.kuota,nama_jurusan,tb_data_kelas.kelas_id,tb_data_kelas.sem_id,nama_hari,
    view_jadwal_ruang_hari.kode_ruang,view_matakuliah_kurikulum.kode_jur,
    (select group_concat(distinct nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_kelas_dosen on view_nama_gelar_dosen.nip=tb_data_kelas_dosen.nip_dosen where tb_data_kelas_dosen.kelas_id=tb_data_kelas.kelas_id and id_jadwal=view_jadwal_ruang_hari.jadwal_id) as nama_dosen,
(select count(krs_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and disetujui=1) as krs_disetujui,
(select count(krs_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and disetujui=0) as pending_krs
from tb_data_kelas 
inner join view_matakuliah_kurikulum using(id_matkul)
left join view_jadwal_ruang_hari using(kelas_id)
 where tb_data_kelas.kelas_id is not null $fakultas $jur_kode $periode $kur_id $matakuliah $sistem_kuliah $hari");

                    foreach ($temp_rec as $key) {
                      $data_rec[] = array(
                                      $key->sem_id,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->kls_nama,
                                      $key->kode_ruang,
                                      strtolower($key->nama_hari),
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