<?php
require_once '../Writer.php';

$jur_filter = "";


   $fakultas="";
  $priode="";
  $lokasi ="";
  $jk = ""; 
  


//get default akses prodi 
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and jurusan.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and jurusan.kode_jur in(0)";
}



    if($_POST['fakultas_filter']!='all') {
      $fakultas = ' and fakultas.kode_fak="'.$_POST['fakultas_filter'].'"';
    }

    if($_POST['jurusan_filter']!='all') {
      $jur_filter = ' and jurusan.kode_jur="'.$_POST['jurusan_filter'].'"';
    }

    if($_POST['priode_filter']!='all') {
      $priode = ' and priode_ppl.id_priode="'.$_POST['priode_filter'].'"';
    }

    if($_POST['id_lokasi']!='all') {
      $lokasi = ' and lk.id_lokasi="'.$_POST['id_lokasi'].'"';
    }

if($_POST['jk']!='all') {
      $jk = ' and mahasiswa.jk="'.$_POST['jk'].'"';
  }  



        $filter_nilai = "where 1=1 $fakultas $jur_filter $priode $lokasi $jk";



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
                    'color' => '00ff00'
                    ),
                'cells' => array(
                       'A1',
                        'B1',
                    'C1',
                     'D1',
                    'E1',
                    'F1',
                    'G1',
                    'H1',
                    'I1',
                    'J1',
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
  1 => 15,
  2 => 31,
  3 => 15,
  4 => 28,
  5 => 25,
  6 => 30,
  7 => 22,
  8 => 30,
  9 => 10,
  10 => 10,
  );
$writer->setColWidth($col_width);


$header = array(
  'NIM'=>'string',
  'NAMA'=>'string',
  'Kelamin'=>'string',
  'Fakultas'=>'string',
  'Jurusan'=>'string',
  'Lokasi PPL'=>'string',
  'Kode MK'=>'string',
  'Nama MK'=>'string',
  'Nilai Angka'=>'string',
   'Nilai Huruf'=>'string',
);

$no=1;
$index = 2;

$data_rec = array();


        $data = $db->query("select lk.nama_lokasi,mahasiswa.jk, ppl.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,ppl.id_kkn, 
    (select ifnull(matkul.kode_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as kode_mk,  
(select ifnull(matkul.nama_mk,'-') from krs_detail join matkul on matkul.id_matkul=krs_detail.kode_mk
 where krs_detail.kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nama_mk, 
(select ifnull(nilai_angka,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_angka, 
(select ifnull(nilai_huruf,'-') from krs_detail where kode_mk in (select id_matkul from v_matkul_ppl) and krs_detail.nim=ppl.nim and nilai_huruf is not null order by nilai_huruf asc limit 1) as nilai_huruf from ppl 
inner join mahasiswa on ppl.nim=mahasiswa.nim inner join fakultas on ppl.kode_fak=fakultas.kode_fak inner join jurusan on ppl.kode_jur=jurusan.kode_jur left join priode_ppl on priode_ppl.id_priode=ppl.id_priode 
left join lokasi_ppl lk on lk.id_lokasi=ppl.id_lokasi  $filter_nilai order by mahasiswa.nim,mahasiswa.jur_kode asc");

        echo $db->getErrorMessage();

    
                    foreach ($data as $key) {
                     $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $key->jk,
                                      $key->nama_resmi,
                                      $key->nama_jur,
                                      $key->nama_lokasi,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->nilai_angka,
                                      $key->nilai_huruf
                        );

                         $no++;
                         $index++;
                    }

$filename = 'Data_PPL.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data PPL', $header, $style);
$writer->writeToStdOut();
exit(0);
?>