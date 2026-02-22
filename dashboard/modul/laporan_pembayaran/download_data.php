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
                  'A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1'
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
	6 => 17,
	7 => 27,
	8 => 27,
	9 => 22,
	10 => 12,
  11 => 50
);
$writer->setColWidth($col_width);

$header = array(
    "No Pembayaran" => "string",
	"NIM" => "string",
	"Nama" => "string",
	"Angkatan" => "string",
	"Program Studi" => "string",
	"Periode" => "string",
	"Jenis Tagihan" => "string",
	"Tanggal Bayar" => "string",
	"Nominal" => "string",
	"Bank" => "string",
  "Keterangan" => "string"
);

$data_rec = array();

  $kode_prodi = "";
  $mulai_smt = "";
  $mulai_smt_end = "";
  $periode = ""; 
  $kode_pembayaran = "";
  $kode_tagihan = ""; 
  $id_bank = ""; 
  $tgl_bayar = "";
  $jenjang = "";

$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vs.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vs.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
//$periode = "and periode='".$semester_aktif->id_semester."'";
$fakultas = "";
$prodi_jenjang = getProdiJenjang();
  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and vs.jur_kode="'.$_POST['kode_prodi'].'"';
  }

    if ($_POST['mulai_smt']!='all') {
      $mulai_smt = " and left(mulai_smt,4) between '".$_POST['mulai_smt']."' and '".$_POST['mulai_smt_end']."'";
    }

    if ($_POST['jenjang']!='all') {
      $jenjang = getProdiJenjangFilter('vs.jur_kode',$_POST['jenjang']);
    }
  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  } else {
    $periode = "";
  }

      if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and kt.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }
  if ($_POST['id_bank']!='all') {
    $id_bank = ' and id_bank="'.$_POST['id_bank'].'"';
  }


  if ($_POST['tgl_bayar']=='') {
    $tgl_bayar = "";
  } else {
    $xpl = explode(" - ", $_POST['tgl_bayar']);
    $awal = $xpl[0];
    $akhir = $xpl[1];
    $tgl_bayar = "and date(kbm.tgl_bayar) between '".$awal."' and '$akhir'";
  }

  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('vs.jur_kode',$_POST['fakultas']);
  }

}

        $temp_rec = $db->query("select tgl_validasi,keu_kwitansi.no_kwitansi,vs.nim,vs.nama,left(vs.mulai_smt,4) as angkatan,vs.jur_kode,periode,kjt.nama_tagihan,kbm.tgl_bayar,keu_bank.nama_singkat,kbm.id_kwitansi as id_bayar,metode_bayar,keu_kwitansi.keterangan,
kbm.nominal_bayar from mahasiswa vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
inner join keu_kwitansi on kbm.id_kwitansi=keu_kwitansi.id_kwitansi
left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank
inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
inner join keu_jenis_tagihan kjt on kt.kode_tagihan=kjt.kode_tagihan
where is_removed=0
$kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $id_bank $tgl_bayar $jenjang");

        echo $db->getErrorMessage();
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
						$prodi_jenjang[$key->jur_kode],
						$key->periode,
						$key->nama_tagihan,
						tgl_indo($key->tgl_bayar),
						$key->nominal_bayar,
						$nama_singkat,
            $key->keterangan
                    );

            }

$filename = 'laporan_pembayaran_per_tagihan.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Bayar', $header, $style);
$writer->writeToStdOut();
exit(0);
?>