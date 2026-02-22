<?php
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
                      'D1',
                      'K1'
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
  10 => 15,
  11 => 39
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
  'Kode Prodi'=>'string',
  'Keterangan'=>'string'
);
$no=1;
$index = 2;

$data_rec = array();

$filename = '../../../upload/sample/nilai/error_nilai.xlsx';
$writer->writeSheet($data_error, 'Data Error', $header, $style);
$writer->writeToFile($filename);
?>