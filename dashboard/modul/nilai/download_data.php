<?php
session_start();
include "../../inc/config.php";

require_once '../../inc/lib/Writer.php';


$jur_kode = "";
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jurusan = "and kurikulum.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jurusan = "and kurikulum.kode_jur in(0)";
}

    if ($_POST['jur_filter']!='all') {
      $jurusan = ' and kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
      $nama_jurusan = $db->fetch_custom_single("select jurusan from view_prodi_jenjang where kode_jur='".$_POST['jur_filter']."'");
        $nama_jurusan = $nama_jurusan->jurusan;
        $nama_jurusan = str_replace(" ", "_", $nama_jurusan);
    } elseif ($_POST['jur_filter']=='all') {
      if ($_SESSION['level']!=1) {
              $jurusan = "and kode_jur='".$_SESSION['jurusan']."'";
      }
      $nama_jurusan = "Semua Nilai";
    } 


/*
      $jurusan = trim($_POST['jur_filter']);
      if ($jurusan=='all') {
          $jurusan = "";
          if ($_SESSION['level']!=1) {
              $jurusan = "and kode_jur='".$_SESSION['jurusan']."'";
          }
          $nama_jurusan = "Semua Nilai";
      } else {
        $jurusan = "kode_jur='$jurusan'";

        $nama_jurusan = $db->fetch_custom_single("select jurusan from view_prodi_jenjang where $jurusan");
        $nama_jurusan = $nama_jurusan->jurusan;
        $nama_jurusan = str_replace(" ", "_", $nama_jurusan);

      }*/


$mulai_smt = "";
$mulai_smt_end = "";
$nim = "";
$status_penilaian = '';
    $jur_filter = "";
    $sem_filter = "";
    $matkul_filter = "";

          if ($_POST['jur_filter']!='all') {
            $jur_filter = ' and kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
          }

          if ($_POST['sem_filter']!='all') {
            $sem_filter = ' and (krs_detail.id_semester="'.$_POST['sem_filter'].'" or kelas.sem_id="1")';
          }

          if ($_POST['matkul_filter']!='all') {
            $matkul_filter = ' and matkul.kode_mk="'.$_POST['matkul_filter'].'"';
          }

 if ($_POST['status_penilaian']!='all') {
      if ($_POST['status_penilaian']=='sudah') {
        $status_penilaian = "and krs_detail.nilai_huruf!=''";
      } else {
        $status_penilaian = "and (krs_detail.nilai_huruf is null or krs_detail.nilai_huruf='')";
      }
      
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }

      }
if ($_POST['value_nim']!='') {
      $nim = ' and krs_detail.nim="'.$_POST['value_nim'].'"';
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
  11 => 10
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
  'SKS' => 'string'
);

$data_rec = array(); 

        $order_by = "order by krs_detail.nim asc";

        $data = $db->query("select matkul.kode_mk,matkul.nama_mk,mahasiswa.nim,mahasiswa.nama,krs_detail.sks,kurikulum.kode_jur,
matkul.semester,kelas.kls_nama,krs_detail.nilai_angka,krs_detail.nilai_huruf,
krs_detail.id_semester,krs_detail.bobot,view_prodi_jenjang.nama_jurusan,krs_detail.id_krs_detail 
from krs_detail left join kelas on krs_detail.id_kelas=kelas.kelas_id 
inner join matkul on krs_detail.kode_mk=matkul.id_matkul
inner join kurikulum using(kur_id)
inner join mahasiswa on krs_detail.nim=mahasiswa.nim 
inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur 
where krs_detail.id_krs_detail is not null $sem_filter $jurusan $matkul_filter $nim $status_penilaian $mulai_smt $order_by");

                    foreach ($data as $key) {
                     
                                    $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->id_semester,
                                      $key->kls_nama,
                                      $key->nilai_huruf,
                                      $key->bobot,
                                      $key->nilai_angka,
                                      $key->kode_jur,
                                      $key->sks
                        );
                    }

$filename = $nama_jurusan.'_Nilai.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data Nilai', $header, $style);
$writer->writeToStdOut();
exit(0);
?>