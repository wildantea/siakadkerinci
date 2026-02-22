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
                       'A1','B1','C1','D1','E1'
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
                  'F1'
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
1 => 20,
2 => 20,
3 => 20,
4 => 20,
5 => 20,
6 => 80
  );
$writer->setColWidth($col_width);

$header = array(
    "NIM Mahasiswa" => 'string',
    "ID Jenis Pendaftaran" => "string",
    "Tanggal Daftar" => "string",
    "Periode Daftar" => "string",
    "Status Pendaftaran" => "string",
    "Keterangan Error" => "string"
);


        $filename = '../../../upload/sample/pendaftaran/error_pendaftaran.xlsx';
        $writer->writeSheet($data_error, 'Data Error', $header, $style);
        $writer->writeToFile($filename);
        ?>
