<?php
session_start();
include "../../inc/config.php";
session_check_json();
require_once '../../inc/lib/Writer.php';
$periode = "";
$fakultas = "";
$matakuliah = "";
$jur_kode = aksesProdi('view_matakuliah_kurikulum.kode_jur');

$file_name = "";
$nim = '';

    if (hasFakultas()) {
      $array_filter['fakultas'] = $_POST['fakultas'];
      if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
        $fakultas = getProdiFakultas('view_matakuliah_kurikulum.kode_jur',$_POST['fakultas']);
        $fak_name = $db2->fetchSingleRow('tb_master_fakultas','id_fakultas',$_POST['fakultas']);
        $file_name = str_replace(' ','_',trimmer($fak_name->nama_fakultas));
      }
    }

    if ($_POST['jur_filter']!='all') {
      $jur_kode = ' and view_matakuliah_kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
      $jurusan = $db2->fetchSingleRow('tb_master_jurusan','kode_jur',$_POST['jur_filter']);
      $file_name = str_replace(' ','_',trimmer($jurusan->nama_jur));
    }

    if ($_POST['periode']!='all') {
      $periode = ' and tb_data_kelas.sem_id="'.$_POST['periode'].'"';
      $file_name .= '_'.$_POST['periode'];
    }

    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and tb_data_kelas.id_matkul="'.$_POST['matakuliah'].'"';
    }

if ($_POST['value_nim']!='') {
      $nim = ' and tb_data_kelas_krs.nim="'.$_POST['value_nim'].'"';
      $file_name = $_POST['value_nim'].'_'.$_POST['periode'];
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
                    'color' => 'ff0000'
                    ),
                'cells' => array(
                    'A1',
                    'C1',
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    'J1'
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
                    'color' => '00ff00'
                    ),
                'cells' => array(
                      'B1',
                      'D1'
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
  1 => 19,
  2 => 27,
  3 => 20,
  4 => 20,
  5 => 20,
  6 => 15,
  7 => 15,
  8 => 15,
  9 => 15,
  10 => 15
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Nama'=>'string',
  'Kode Matakuliah'=>'string',
  'Nama Matakuliah'=>'string',
  'Semester'=>'string',
  'Kelas' => 'string',
  'Nilai Huruf'=>'string',
  'Nilai Indeks' => 'string',
  'Nilai Angka' => 'string',
  'Kode Prodi'=>'string'
);

$data_rec = array(); 


        $data = $db2->query("select tb_data_kelas_krs.nim,tb_master_mahasiswa.nama,kode_mk,tb_data_kelas_krs.id_semester,nama_mk,tb_data_kelas.kls_nama,kode_jur,nilai_huruf,nilai_angka,nilai_indeks
         from tb_data_kelas 
inner join tb_data_kelas_krs_detail on tb_data_kelas.kelas_id=tb_data_kelas_krs_detail.kelas_id
inner join tb_data_kelas_krs on tb_data_kelas_krs_detail.krs_id=tb_data_kelas_krs.krs_id
inner join tb_master_mahasiswa on tb_data_kelas_krs.nim=tb_master_mahasiswa.nim
inner join view_matakuliah_kurikulum using(id_matkul)
 where tb_data_kelas.kelas_id is not null $fakultas $periode $jur_kode $matakuliah $nim");



    
                    foreach ($data as $key) {
                     
                                    $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->id_semester,
                                      $key->kls_nama,
                                      $key->nilai_huruf,
                                      $key->nilai_indeks,
                                      $key->nilai_angka,
                                      $key->kode_jur
                        );
                    }

$filename = $file_name.'_Nilai.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data Nilai', $header, $style);
$writer->writeToStdOut();
exit(0);
?>