<?php
require_once '../Writer.php';

$jur_kode = "";

//get default akses prodi 
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_kode = "and mhs_pindah.jurusan_baru in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and mhs_pindah.jurusan_baru in(0)";
}

$mulai_smt = '';
$jenis_pindah = '';


      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and jurusan_baru="'.$_POST['jur_kode'].'"';
      }

      if ($_POST['mulai_smt']!='all') {
          $mulai_smt = ' and angkatan_baru="'.$_POST['mulai_smt'].'"';
      }

      if ($_POST['jenis_pindah']!='') {
        $jenis_pindah = ' and jenis_pindah="'.$_POST['jenis_pindah'].'"';
      }


        $filter_nilai = "where 1=1 $jur_kode $mulai_smt $jenis_pindah";



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
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    'J1',
                    'K1',
                    'L1',
                    
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
                        'M1',
                      'N1',
                      'O1'

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
  1 => 15,
  2 => 23,
  3 => 15,
  4 => 23,
  5 => 10,
  6 => 15,
  7 => 22,
  8 => 25,
  9 => 19,
  10 => 19,
  11 => 19,
  12 => 20,
  13 => 25,
  14 => 27
  );
$writer->setColWidth($col_width);


$header = array(
  'NIM'=>'string',
  'NAMA'=>'string',
  'Kode Mk Asal'=>'string',
  'Nama Mk Asal'=>'string',
  'SKS Asal'=>'string',
  'Nilai Huruf Asal'=>'string',
  'Kode Matakuliah Diakui'=>'string',
  'Nama Matakuliah Diakui'=>'string',
  'Nilai Huruf Diakui' => 'string',
  'Nilai Indeks Diakui' => 'string',
  'Kode Prodi' => 'string',
  'Semester' => 'string',
  'Kode Asal Perguruan Tinggi' => 'string',
  'ID Aktivitas' => 'string'
);

$no=1;
$index = 2;

$data_rec = array();


        $data = $db->query("select nim_baru as nim,nama_mhs,kode_lama as kode_mk_asal,konversi_matkul.nama_mk as nm_mk_asal,sks as sks_asal,nilai as nilai_huruf_asal,matkul.kode_mk,matkul.nama_mk,nilai as nilai_huruf_diakui,bobot as nilai_angka_diakui,jurusan_baru as kode_jurusan,angkatan_baru as semester,kode_pt_asal as kode_pt from konversi_matkul inner join mhs_pindah on id_pindah=mhs_pindah.id inner join matkul on kode_baru=matkul.id_matkul $filter_nilai order by nim_baru asc");



    
                    foreach ($data as $key) {
                     $data_rec[] = array(
                                      $key->nim,
                                      $key->nama_mhs,
                                      $key->kode_mk_asal,
                                      $key->nm_mk_asal,
                                      $key->sks_asal,
                                      $key->nilai_huruf_asal,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->nilai_huruf_diakui,
                                      $key->nilai_angka_diakui,
                                      $key->kode_jurusan,
                                      $key->semester,
                                      $key->kode_pt,
                                      $key->id_aktivitas,
                        );

                         $no++;
                         $index++;
                    }

$comments = array(
'A1' => 'NIM / NIPD Mahasiswa
info : wajib disi',
'C1' => '
info : wajib disi',
'D1' => '
info : wajib disi',
'E1' => '
info : wajib disi',
'F1' => '
info : wajib disi',
'G1' => '
info : wajib disi',
'H1' => '
info : wajib disi',
'I1' => '
info : wajib disi',
'J1' => '
info : wajib disi',
'K1' => '
info : wajib disi',
'L1' => 'Peride Masuk Kuliah/Angkatan
tahun+semester
1 : ganjil, 2 genap, 
20221 berarti angakatan 2022 ganjil
Info : Wajib Diisi',
'M1' => 'info :
Kode Asal Perguruan Tinggi , Bisa lihat referensi kode pt di menu Master Data, Data Prodi Kampus. 

Boleh kosog, baiknya Diisi',
'N1' => 'Id Aktivitas,
anda bisa download dulu data aktivitas di menu tool, download aktivitas, 
pilih jenis yang pertukaran pelajar, 
anda bisa menggunakan isian Id Aktivitas atau ID Aktivitas NEO'
);
$writer->setComment($comments);

$filename = 'nilai_transfer'.$nama_jurusan.$nama_mk.'.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data Nilai Transfer', $header, $style);
$writer->writeToStdOut();
exit(0);
?>