<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';


$nim = "";
$periode = "";
$fakultas = "";
$mulai_smt = "";
$status_mahasiswa = "";
$jur_kode = "";

$jur_kode = aksesProdi('mahasiswa.jur_kode');



if ($_POST['nim']=='all') {
    if ($_POST['fakultas']!='all') {
      $fakultas = ' and view_prodi_jenjang.id_fakultas="'.$_POST['fakultas'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and akm.sem_id="'.$_POST['periode'].'"';
    }

    if ($_POST['jur_filter']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jur_filter'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = " and left(mulai_smt,4) between '".$_POST['mulai_smt']."' and '".$mulai_smt_end."'";
        } else {
            $mulai_smt = " and left(mulai_smt,4)='".$_POST['mulai_smt']."'";
        }

      }
    if ($_POST['status_mahasiswa']!='all') {
      $status_mahasiswa = ' and akm.id_stat_mhs="'.$_POST['status_mahasiswa'].'"';
    }
} else {
  $nim = "and mahasiswa.nim='".$_POST['value_nim']."'";
}



$stat_mahasiswa = getStatusMahasiswa();
$prodi_jenjang = getProdiJenjang();

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
                   'A1','C1','D1','E1','F1','G1','H1','I1','J1'
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
    1 => 22,
	2 => 29,
	3 => 13,
	4 => 12,
	5 => 17,
	6 => 17,
	7 => 17,
	8 => 12,
	9 => 17,
	10 => 27
);
$writer->setColWidth($col_width);

$header = array(
    "NIM" => "string",
	"Nama Mahasiswa" => "string",
	"Semester" => "string",
	"SKS" => "string",
	"IP Semester" => "string",
	"SKS Kumulatif" => "string",
	"IP Kumulatif" => "string",
	"Status" => "string",
	"Kode Prodi" => "string",
	"Biaya Kuliah Semester" => "string"
);

$data_rec = array();

    
        $temp_rec = $db->query("select mahasiswa.nim,sem_id,mahasiswa.nama,mahasiswa.mulai_smt,akm.id_stat_mhs,
(select sum(nominal_tagihan) from keu_tagihan inner join keu_tagihan_mahasiswa on id_tagihan_prodi=keu_tagihan.id
where nim=akm.mhs_nim and periode=akm.sem_id
) as biaya_semester,
akm.ip,akm.ipk,akm.sks_diambil,akm.total_sks,jur_kode,akm.akm_id 
from akm inner join mahasiswa on akm.mhs_nim=mahasiswa.nim
inner join view_prodi_jenjang on jur_kode=kode_jur
 where akm.akm_id is not null $periode $fakultas $jur_kode $mulai_smt $status_mahasiswa $nim");


                    foreach ($temp_rec as $key) {
             
                        if ($key->biaya_semester=="") {
                            $biaya = '0';
                        } else {
                            $biaya = $key->biaya_semester;
                        }
                    $data_rec[] = array(
                        $key->nim,
						trimmer($key->nama),
						$key->sem_id,
						$key->sks_diambil,
						$key->ip,
						$key->total_sks,
						$key->ipk,
						$key->id_stat_mhs,
						$key->jur_kode,
						$biaya
                    );

            }

/*
echo '<pre>';
echo $db->getErrorMessage();
print_r($data_rec);
exit();*/
$filename = 'aktivitas_kuliah_mahasiswa.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data aktivitas kuliah mahasiswa', $header, $style);


$writer->writeToStdOut();
exit(0);
?>