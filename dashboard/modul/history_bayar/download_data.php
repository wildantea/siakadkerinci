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
                  'A1','B1','C1','D1','E1','F1','G1','H1','I1','J1'
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
  1 => 32,
	2 => 22,
	3 => 27,
	4 => 12,
	5 => 27,
	6 => 27,
	7 => 22,
	8 => 12
);
$writer->setColWidth($col_width);

$header = array(
  "No Pembayaran" => "string",
	"NIM" => "string",
	"Nama" => "string",
	"Angkatan" => "string",
	"Program Studi" => "string",
	"Tanggal Bayar" => "string",
	"Nominal" => "string",
	"Bank" => "string",
  "keterangan" => "string"
);

$data_rec = array();

 $kode_prodi = "";
  $mulai_smt = "";
  $periode = ""; 
  $kode_pembayaran = "";
  $kode_tagihan = ""; 
  $id_bank = ""; 
  $tgl_bayar = "";

$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and kode_jur in(0)";
}


//$periode = "and periode='".$semester_aktif->id_semester."'";
$fakultas = "";
$validator = "";

  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and kode_jur="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['mulai_smt']!='all') {
    $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
  }

  if ($_POST['id_bank']!='all') {
    $id_bank = ' and id_bank="'.$_POST['id_bank'].'"';
  }

  if ($_POST['validator']!='all') {
    $validator = ' and keu_kwitansi.validator="'.$_POST['validator'].'"';
  }
  if ($_POST['tgl_bayar']=='') {
    $tgl_bayar = "";
  } else {
    $xpl = explode(" - ", $_POST['tgl_bayar']);
    $awal = $xpl[0];
    $akhir = $xpl[1];
    $tgl_bayar = "and keu_kwitansi.tgl_bayar between '".$awal."' and '$akhir'";
  }

  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('jur_kode',$_POST['fakultas']);
  }

}
    //$prodi_jenjang = getProdiJenjang();
$temp_rec = $db->query("select metode_bayar,keu_kwitansi.no_kwitansi,view_simple_mhs_data.nim,view_simple_mhs_data.nama,view_simple_mhs_data.angkatan,keu_kwitansi.nominal_bayar,keu_kwitansi.tgl_bayar,keu_bank.nama_singkat,keu_kwitansi.date_created,keu_kwitansi.keterangan,view_simple_mhs_data.jurusan,keu_kwitansi.id_kwitansi from keu_kwitansi inner join view_simple_mhs_data on keu_kwitansi.nim_mahasiswa=view_simple_mhs_data.nim left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank where 1=1
$kode_prodi $fakultas $mulai_smt $id_bank $tgl_bayar $validator");

      foreach ($temp_rec as $key) {
            if ($key->nama_singkat!="") {
              if ($key->metode_bayar==3) {
                $nama_singkat = 'H2H '.$key->nama_singkat;
              } else {
                $nama_singkat = $key->nama_singkat;
              }
              
            } else {
              $nama_singkat = 'CASH';
            }

            $data_rec[] = array(
            $key->no_kwitansi,
						$key->nim,
						$key->nama,
						$key->angkatan,
						$key->jurusan,
						tgl_indo($key->tgl_bayar),
						$key->nominal_bayar,
						$nama_singkat,
            $key->keterangan
                    );

            }



$filename = 'history_pembayaran.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Bayar', $header, $style);
$writer->writeToStdOut();
exit(0);
?>