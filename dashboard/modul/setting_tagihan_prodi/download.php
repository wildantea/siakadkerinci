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
            );

//column width
$col_width = array(
  1 => 18,
  2 => 21,
  3 => 16,
  4 => 16
  );
$writer->setColWidth($col_width);

$header = array(
  'Kode Prodi'=>'string',
  'Biaya Untuk Angkatan'=>'string',
  'Kode Jenis Biaya'=>'string',
  'Besar Biaya'=>'string'
);

$data_rec = array();
   $kode_prodi = "";
$berlaku_angkatan = "";
$kode_tagihan = "";

  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and keu_tagihan.kode_prodi="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['berlaku_angkatan']!='all') {
    $berlaku_angkatan = ' and berlaku_angkatan="'.$_POST['berlaku_angkatan'].'"';
  }

    if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and keu_tagihan.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }

}
        $order_by = "order by berlaku_angkatan ASC";

    
        $temp_rec = $db->query("select * from keu_tagihan where id is not null $kode_prodi $berlaku_angkatan $kode_tagihan ");
                    foreach ($temp_rec as $key) {

                      $data_rec[] = array(
                                      $key->kode_prodi,
                                      $key->berlaku_angkatan,
                                      $key->kode_tagihan,
                                      $key->nominal_tagihan
                        );

            }


$filename = 'Biaya_Prodi.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Biaya', $header, $style);
$writer->writeToStdOut();
exit(0);
?>