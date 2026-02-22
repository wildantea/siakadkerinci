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
                    'color' => '00ff00'
                    ),
                'cells' => array(
                  'A1','B1','C1','D1','E1','F1','G1','H1','I1'
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
  1 => 17,
	2 => 22,
	3 => 32,
	4 => 22,
	5 => 32,
	6 => 32,
	7 => 17,
	8 => 17,
	9 => 32
);
$writer->setColWidth($col_width);

$header = array(
  "NIM" => "string",
	"Nama" => "string",
	"Periode Cuti" => "string",
	"Status Disetujui" => "string",
	"Alasan Cuti" => "string",
	"Keterangan Lain" => "string",
	"Tanggal Diajukan" => "string",
	"Tanggal Disetujui" => "string",
	"Program Studi" => "string"
);

$data_rec = array();


$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and view_simple_mhs_data.jur_kode in(".$akses_jur->kode_jur.")";
} else {
//jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and view_simple_mhs_data.jur_kode in(0)";
}

$sem_filter = "";
$disetujui = "";
$angkatan_filter = "";

if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and view_simple_mhs_data.jur_kode="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and (select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti) like "%'.$_POST['sem_filter'].'%"';
  }

  if ($_POST['angkatan_filter']!='all') {
    $angkatan_filter = ' and mulai_smt="'.$_POST['angkatan_filter'].'"';
  }

  if ($_POST['disetujui']!='all') {
      $disetujui = "and status_acc='".$_POST['disetujui']."'";
  }

}
        //$order_by = "order by your order here";
    
        $temp_rec = $db->query("select keterangan,status_acc,tb_data_cuti_mahasiswa.nim,view_simple_mhs_data.nama,view_simple_mhs_data.mulai_smt,view_simple_mhs_data.jk,view_simple_mhs_data.jurusan,tb_data_cuti_mahasiswa.id_cuti,date_created,date_approved,alasan_cuti,
  (select group_concat(periode) from tb_data_cuti_mahasiswa_periode where id_cuti=tb_data_cuti_mahasiswa.id_cuti) as periode from tb_data_cuti_mahasiswa inner join view_simple_mhs_data on tb_data_cuti_mahasiswa.nim=view_simple_mhs_data.nim where tb_data_cuti_mahasiswa.id_cuti is not null $jur_filter $sem_filter $angkatan_filter $disetujui");
                    foreach ($temp_rec as $key) {

            $data_rec[] = array(
                                $key->nim,
                    						$key->nama,
                    						$key->periode,
                    						$key->status_acc,
                    						$key->alasan_cuti,
                    						$key->keterangan,
                    						$key->date_created,
                    						$key->date_approved,
                    						$key->jurusan
                    );

            }


$filename = 'data_cuti_kuliah.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data cuti kuliah', $header, $style);
$writer->writeToStdOut();
exit(0);
?>