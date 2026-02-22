<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';

$nama_jurusan = $db->fetch_custom_single("select concat(jenjang_pendidikan.jenjang,' ',jurusan.nama_jur) as jurusan,
kurikulum.nama_kurikulum,jurusan.nama_jur,jurusan.id_jenjang from jurusan
inner join kurikulum on jurusan.kode_jur=kurikulum.kode_jur
inner join jenjang_pendidikan on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
where kurikulum.kur_id=?",array('kur_id' => $_GET['id']));
        $jur = str_replace(" ", "_", $nama_jurusan->jurusan);
        $nama_kurikulum = str_replace(" ", "_", $nama_jurusan->nama_kurikulum);


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
                    'C1',
                    'D1',
                    'E1',
                    'F1',
                    'G1',
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
                      'H1',
                      'I1',
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
  1 => 15,
  2 => 20,
  3 => 10,
  4 => 15,
  5 => 15,
  6 => 15,
  7 => 13,
  8 => 15,
  9 => 15,
  10 => 10
  );
$writer->setColWidth($col_width);

$header = array(
  'Kode MK'=>'string',
  'Nama MK'=>'string',
  'Jenis MK'=>'string',
  'SKS Tatap Muka'=>'string',
  'SKS Praktek' => 'string',
  'SKS Praktek Lapangan' => 'string',
  'SKS Simulasi' => 'string',
  'Tgl Mulai Efektif' => 'string',
  'Tgl Akhir Efektif' => 'string',
  'Semester' => 'string'
);

$data_rec = array();
   
        $order_by = "order by kode_mk,nama_mk ASC";

    
        $temp_rec = $db->query("select * from matkul where kur_id=?",array('kur_id' => $_GET['id']));
                    foreach ($temp_rec as $key) {

                      $data_rec[] = array(
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->id_tipe_matkul,
                                      $key->sks_tm,
                                      $key->sks_prak,
                                      $key->sks_prak_lap,
                                      $key->sks_sim,
                                      $key->tgl_mulai_efektif,
                                      $key->tgl_akhir_efektif,
                                      $key->semester
                        );

            }


$filename = str_replace(" ", "_","Matkul_".$nama_kurikulum.$jur.'.xlsx');
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data MatKurikulum', $header, $style);
$writer->writeToStdOut();
exit(0);
?>