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
                    'D1',
                    'E1',
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
  1 => 16,
  2 => 21,
  3 => 20,
  4 => 20,
  5 => 23,
  6 => 23
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Kode Jenis Tagihan'=>'string',
  'Periode Tagihan'=>'string',
  'Potongan' => 'string',
  'Tanggal Awal Pembayaran' => 'string',
  'Tanggal Akhir Pembayaran' => 'string'
);

$data_rec = array();
$kode_prodi = "";
$periode = "";
$kode_tagihan = "";

  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and keu_tagihan.kode_prodi="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  }

    if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and keu_tagihan.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }

}
        $order_by = "order by berlaku_angkatan ASC";

    
        $temp_rec = $db->query("select keu_tagihan_mahasiswa.nim,keu_tagihan.kode_tagihan,periode,potongan,tanggal_awal,tanggal_akhir from keu_tagihan_mahasiswa
 inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id
 inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
  inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
 inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur  $kode_prodi $periode $kode_tagihan ");
                    foreach ($temp_rec as $key) {

                      $data_rec[] = array(
                                      $key->nim,
                                      $key->kode_tagihan,
                                      $key->periode,
                                      $key->potongan,
                                      substr($key->tanggal_awal, 0,10),
                                      substr($key->tanggal_akhir,0,10)
                        );

            }


$filename = 'Tagihan_Mahasiswa.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Tagihan Mhs', $header, $style);
$writer->writeToStdOut();
exit(0);
?>