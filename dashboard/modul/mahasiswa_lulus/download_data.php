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
                   'A1','C1','D1','E1','J1'
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
                  'B1','F1','G1','H1','I1'
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
  2 => 32,
  3 => 22,
  4 => 22,
  5 => 22,
  6 => 32,
  7 => 22,
  8 => 22,
  9 => 22,
  10 => 18
);
$writer->setColWidth($col_width);

$header = array(
    "NIM" => "string",
  "Nama Mahasiswa" => "string",
  "Jenis Keluar" => "string",
  "Tanggal Keluar" => "string",
  "Semester Keluar" => "string",
  "Nomor SK" => "string",
  "Tanggal SK" => "string",
  "IPK" => "string",
  "No Seri Ijasah" => "string",
  "Kode Prodi" => "string"
);

$data_rec = array();

$jur_kode = "";
//get default akses prodi 
$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "and jur_kode in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and jur_kode in(0)";
}

$sem_filter = "";
$fakultas = "";
$mulai_smt = "";
$jenis_keluar = "";
$mulai_smt_end = "";
$semester = "";
$ipk = "";
  
if (isset($_POST['jur_kode'])) {
    if ($_POST['fakultas']!='all') {
      $fakultas = ' and view_prodi_jenjang.id_fakultas="'.$_POST['fakultas'].'"';
    }
    if ($_POST['sem_filter']!='all') {
      $sem_filter = ' and tb_data_kelulusan.semester="'.$_POST['sem_filter'].'"';
    }
      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and tb_data_kelulusan.kode_jurusan="'.$_POST['jur_kode'].'"';
      }
  
      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and mulai_smt between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
        }

      }
  
      if ($_POST['jenis_keluar']!='all') {
        $jenis_keluar = " and tb_data_kelulusan.id_jenis_keluar='".$_POST['jenis_keluar']."'";
      }
      if ($_POST['semester']!='' && $_POST['control_semester']!='all') {
        $semester = ' and ((left(tb_data_kelulusan.semester,4)-left(mulai_smt,4))*2)+right(tb_data_kelulusan.semester,1)-(floor(right(mulai_smt,1)/2))'.$_POST['control_semester'].'"'.$_POST['semester'].'"';
      }
      if ($_POST['ipk']!='' && $_POST['control_ipk']!='all') {
        $ipk = ' and ipk'.$_POST['control_ipk'].'"'.$_POST['ipk'].'"';
      }
}
       // $order_by = "order by your order here";

    
        $temp_rec = $db2->query("select tb_data_kelulusan.nim,mahasiswa.nama,mahasiswa.mulai_smt,jenis_keluar.ket_keluar,tb_data_kelulusan.tanggal_keluar,tb_data_kelulusan.semester,tb_data_kelulusan.keterangan_kelulusan,view_prodi_jenjang.nama_jurusan,id_jenis_keluar,sk_yudisium,tgl_sk_yudisium,ipk,no_seri_ijasah,kode_jurusan,
    tb_data_kelulusan.id from tb_data_kelulusan inner join jenis_keluar on tb_data_kelulusan.id_jenis_keluar=jenis_keluar.id_jns_keluar inner join mahasiswa on tb_data_kelulusan.nim=mahasiswa.nim inner join view_prodi_jenjang on tb_data_kelulusan.kode_jurusan=view_prodi_jenjang.kode_jur where tb_data_kelulusan.id is not null $fakultas $sem_filter $jur_kode $mulai_smt $jenis_keluar $semester $ipk");
                    foreach ($temp_rec as $key) {

                    $data_rec[] = array(
                        $key->nim,
                        $key->nama,
                        $key->id_jenis_keluar,
                        $key->tanggal_keluar,
                        $key->semester,
                        $key->sk_yudisium,
                        $key->tgl_sk_yudisium,
                        $key->ipk,
                        $key->no_seri_ijasah,
                        $key->kode_jurusan
                    );

            }


$filename = 'mahasiswa_lulus.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data mahasiswa lulus', $header, $style);
$writer->writeToStdOut();
exit(0);
?>