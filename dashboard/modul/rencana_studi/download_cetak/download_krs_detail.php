<?php
session_start();
include "../../../inc/config.php";

require_once '../../../inc/lib/Writer.php';


$jur_kode = aksesProdi('mahasiswa.jur_kode');

$fakultas = "";
$periode = "";
$mulai_smt = "";
$is_bayar = "";
$disetujui = "";
$mulai_smt_end = "";


if (isset($_POST['jurusan'])) {
    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = ' and (left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end.")";
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }
      }

    if ($_POST['status_bayar']!='all') {
      if ($_POST['status_bayar']=='sudah') {
          $is_bayar = "and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0";

      } elseif ($_POST['status_bayar']=='belum') {
          $is_bayar = "and
  IFNULL(
    (SELECT id_affirmasi 
     FROM affirmasi_krs 
     WHERE nim = mahasiswa.nim 
       AND periode = '".$_POST['periode']."' 
     LIMIT 1), 
    0
  ) < 1

          and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) < 1
  and 
    IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0";
      } elseif ($_POST['status_bayar']=='aff') {
        $is_bayar = "and (select id_affirmasi from affirmasi_krs where nim=mahasiswa.nim and periode='".$_POST['periode']."' limit 1) > 0
                  and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) < 1
          ";
      }
    }
    if ($_POST['disetujui']!='all') {
      $disetujui = ' and (select min(disetujui) from krs_detail where nim=mahasiswa.nim and id_semester="'.$_POST['periode'].'")="'.$_POST['disetujui'].'"';
    }

}

$periode = $_POST['periode'];

if ($_POST['status_krs']=='1') {
  $temp_rec = $db->query("
SELECT mahasiswa.nim,nama,krs_detail.id_semester,matkul.kode_mk,nama_mk,kls_nama,mahasiswa.jur_kode
from krs_detail inner join mahasiswa using(nim)
inner join matkul on krs_detail.kode_mk=matkul.id_matkul
inner join kelas on id_kelas=kelas_id

where krs_detail.id_semester='".$_POST['periode']."'
and   IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0
 $fakultas $jur_kode $mulai_smt $is_bayar $disetujui
");
 
} else {
  echo "<h1>Download data haya untuk yang sudah KRS</h1>";
  exit();
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
                    'D1',
                   'F1',
                    'G1'
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
  1 => 19,
  2 => 27,
  3 => 20,
  4 => 20,
  5 => 20,
  6 => 15,
  7 => 19
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Nama'=>'string',
  'Semester'=>'string',
  'Kode Matakuliah' => 'string',
  'Nama Matakuliah' => 'string',
  'Kelas' => 'string',
  'Kode Prodi'=>'string'
);
  

$data_rec = array(); 

                    foreach ($temp_rec as $key) {
                                    $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $periode,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->kls_nama,
                                      $key->jur_kode
                        );

                    }


$filename = 'Data_KRS_Detail.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data KRS', $header, $style);
$writer->writeToStdOut();
exit(0);
?>