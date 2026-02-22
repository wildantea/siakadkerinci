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
                   'A1','C1'
                   //,'D1','E1','J1'
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
                  'B1'
                  //,'F1','G1','H1','I1','K1'
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
    1 => 18,
    2 => 32,
    3 => 40
/*     4 => 22,
    5 => 22,
    6 => 32,
    7 => 22,
    8 => 22,
    9 => 22,
    10 => 18,
    11 => 40 */
);
$writer->setColWidth($col_width);

$header = array(
    "NIM" => "string",
    "Nama Mahasiswa" => "string",
/*     "Jenis Keluar" => "string",
    "Tanggal Keluar" => "string",
    "Semester Keluar" => "string",
    "Nomor SK" => "string",
    "Tanggal SK" => "string",
    "IPK" => "string",
    "No Seri Ijasah" => "string",
    "Kode Prodi" => "string", */
    "Keterangan" => "string"
);

$rand = strtotime(date('Y-m-d H:i:s'));
$data_rec = array();
$filename = '../../../upload/sample/mahasiswa_lulus/error_mahasiswa_lulus'.$rand.'.xlsx';
$writer->writeSheet($data_error, 'Data Error', $header, $style);
$writer->writeToFile($filename);
