<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';


$jur_filter = "";
$semester_aktif = "";
$sem_filter = "";
$matkul_filter = "";
$hari = "";
$filter_ket = "";

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vn.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and vn.sem_id="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['matkul_filter']!='all') {
    $matkul_filter = ' and vn.id_matkul="'.$_POST['matkul_filter'].'"';
  }
  if ($_POST['hari_filter']!='all') {
    $hari = ' and view_jadwal.hari="'.$_POST['hari_filter'].'"';
  }

  if ($_POST['keterangan']!='all') {
    if ($_POST['keterangan']=='tunggal') {
      $filter_ket = "and (select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) = 1";
    } elseif ($_POST['keterangan']=='tim') {
      $filter_ket = "and (select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) > 1";
    }
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
                    'K1',
                    'L1',
                    'M1',
                    'N1',
                    'O1',
                    'P1',
                    'Q1'
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
  1 => 30,
  2 => 30,
  3 => 10,
  4 => 25,
  5 => 29,
  6 => 15,
  7 => 15,
  8 => 25,
  9 => 15,
  10 => 15,
  11 => 15,
  12 => 15,
  13 => 15,
  14 => 15,
  15 => 15,
  16 => 15,
  17 => 15
  ); 
$writer->setColWidth($col_width);

$header = array(
   'NIDN'=>'string',
  'Nama Dosen'=>'string',
   'Kelas'=>'string',
    'Semester'=>'string',
  'Nama Matakuliah'=>'string',
  'SKS'=>'string',
  'Ruangan'=>'string',
  'Jurusan' => 'string',
  'Senin' => 'string',
  'Selasa' => 'string',
  'Rabu' => 'string',
  'Kamis' => 'string',
  'Jumat' => 'string',
  'Sabtu' => 'string',
  'Minggu' => 'string',
  'Dosen Ke' => 'string',
  'keterangan' => 'string',
);

$data_rec = array();
   
        $order_by = "order by nama_dosen ASC";

    
        $temp_rec = $db->query("select view_jadwal.sem_id,kls_nama,nidn,nama_dosen,vj.matkul_dosen,sks,dosen_ke,nm_ruang,vn.jurusan,view_jadwal.hari,view_jadwal.jam_mulai,view_jadwal.jam_selesai,
        (select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) as jml_dosen
 from view_jadwal_dosen_kelas vj
inner join dosen on vj.id_dosen=dosen.nip
inner join view_nama_kelas vn on vj.id_kelas=vn.kelas_id
inner join view_jadwal on vj.jadwal_id=view_jadwal.jadwal_id
     where vn.kelas_id is not null $sem_filter $jur_filter $matkul_filter $filter_ket $hari");
                    foreach ($temp_rec as $key) {

                              if(strtolower($key->hari)=='senin') {
                                $senin= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $senin= "";
                              } 

                              if(strtolower($key->hari)=='selasa') {
                                $selasa= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $selasa= "";
                              } 

                              if(strtolower($key->hari)=='rabu') {
                                $rabu= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $rabu= "";
                              } 

                              if(strtolower($key->hari)=='kamis') {
                                $kamis= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $kamis= "";
                              } 

                              if(strtolower($key->hari)=='jumat') {
                                $jumat= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $jumat= "";
                              } 

                              if(strtolower($key->hari)=='sabtu') {
                                $sabtu= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $sabtu= "";
                              } 
                              if(strtolower($key->hari)=='minggu') {
                                $minggu= substr($key->jam_mulai, 0,5)." - ".substr($key->jam_selesai,0,5); 
                              } else {
                                $minggu= "";
                              } 



    if ($key->jml_dosen > 1) {
      $dosen_ket = 'Dosen Tim';
    } else {
      $dosen_ket = 'Dosen Tunggal';
    }

                      $data_rec[] = array( 
                                  $key->nidn,
                                  $key->nama_dosen,
                                  $key->kls_nama,
                                  $key->sem_id,
                                  $key->matkul_dosen,
                                  $key->sks,
                                  $key->nm_ruang,
                                  $key->jurusan,
                                  $senin,
                                  $selasa,
                                  $rabu,
                                  $kamis,
                                  $jumat,
                                  $sabtu,
                                  $minggu,
                                  $key->dosen_ke,
                                  $dosen_ket
                        );

            }


$filename = 'Rekap_dosen.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Rekap Dosen', $header, $style);
$writer->writeToStdOut();
exit(0);
?>