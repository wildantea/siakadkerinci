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
                   'A1','B1','C1','D1','E1','F1','G1','H1'
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
    1 => 14,
	2 => 14,
	3 => 17,
	4 => 17,
	5 => 21,
	6 => 21,
	7 => 14,
	8 => 17
);
$writer->setColWidth($col_width);

$header = array(
    "Nilai Huruf" => "string",
	"Nilai Indeks" => "string",
	"Bobot Minimum" => "string",
	"Bobot Maksimum" => "string",
	"Tanggal Mulai Efektif" => "string",
	"Tanggal Akhir Efektif" => "string",
	"Kode Prodi" => "string",
	"Untuk Angkatan" => "string"
);

$data_rec = array();

$fakultas = "";
$jurusan = "";
$jenjang = "";
$angkatan = "";

if (isset($_POST['fakultas'])) {

  if ($_POST['fakultas']!='all') {
    $fakultas = ' and view_prodi_jenjang.kode_fak="'.$_POST['fakultas'].'"';
  }

    if ($_POST['jurusan']!='all') {
    $jurusan = ' and view_prodi_jenjang.kode_jur="'.$_POST['jurusan'].'"';
  }

      if ($_POST['jenjang']!='all') {
    $jenjang = ' and view_prodi_jenjang.id_jenjang="'.$_POST['jenjang'].'"';
  }

  if ($_POST['angkatan']!='all') {
    $angkatan = ' and berlaku_angkatan="'.$_POST['angkatan'].'"';
  }
}

    
        $temp_rec = $db->query("select skala_nilai.nilai_huruf,skala_nilai.kode_jurusan,skala_nilai.nilai_indeks,skala_nilai.bobot_nilai_min,skala_nilai.bobot_nilai_maks,skala_nilai.tgl_mulai_efektif,skala_nilai.tgl_akhir_efektif,view_prodi_jenjang.jurusan,berlaku_angkatan,skala_nilai.id from skala_nilai inner join view_prodi_jenjang on skala_nilai.kode_jurusan=view_prodi_jenjang.kode_jur  where view_prodi_jenjang.kode_jur is not null $fakultas $jurusan $jenjang $angkatan");
                    foreach ($temp_rec as $key) {

                    $data_rec[] = array(
                      	$key->nilai_huruf,
						$key->nilai_indeks,
						$key->bobot_nilai_min,
						$key->bobot_nilai_maks,
						$key->tgl_mulai_efektif,
						$key->tgl_akhir_efektif,
						$key->kode_jurusan,
						$key->berlaku_angkatan
                    );

            }


$filename = 'skala_nilai.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data skala nilai', $header, $style);
$writer->writeToStdOut();
exit(0);
?>