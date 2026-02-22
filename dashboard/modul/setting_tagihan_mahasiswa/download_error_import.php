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
                    'B1',
                    'C1',
                    'D1'
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
                   'E1'
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
  1 => 18,
  2 => 21,
  3 => 21,
  4 => 20,
  5 => 150
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Kode Jenis Tagihan'=>'string',
  'Periode Tagihan'=>'string',
  'Potongan' => 'string',
  'Keterangan' => 'string'
);


$no=1;
$index = 2;

$data_rec = array();
$filename = '../../../upload/error_import/error_tagihan_mahasiswa.xlsx';
$writer->writeSheet($data_error, 'Data Error', $header, $style);
$writer->writeToFile($filename);
?>