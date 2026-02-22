<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'keu_kwitansi.no_kwitansi',
    'vs.nim',
    'nama',
    'mulai_smt',
    'jur_kode',
    'nama_pembayaran',
    'periode',
    'keu_kwitansi.tgl_bayar',
    //'tgl_validasi',
    'nominal_tagihan',
    'nama_singkat'
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array(
    'mulai_smt',
    'jur_kode',
    'nama_pembayaran',
    'periode',
    'tgl_bayar',
    'tgl_validasi',
    'nominal_tagihan',
    'nama_singkat'
  );
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  $datatable->set_order_type("desc");

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
  $jur_filter = "and vs.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vs.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
//$periode = "and periode='".$semester_aktif->id_semester."'";
$fakultas = "";

  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $kode_prodi = ' and vs.jur_kode="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['mulai_smt']!='all') {
    $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
  }

  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  } else {
    $periode = "";
  }

    if ($_POST['kode_pembayaran']!='all') {
    $kode_pembayaran = ' and kjp.kode_pembayaran="'.$_POST['kode_pembayaran'].'"';
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
    $tgl_bayar = "and tgl_bayar between '".$awal."' and '$akhir'";
  }

  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('vs.jur_kode',$_POST['fakultas']);
  }

}
//c39dbfde32724006ac88c7446c7fca04
  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

$total_bayar = $db->fetch_custom_single("select sum(kbm.nominal_bayar) as total_bayar from mahasiswa vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
inner join keu_kwitansi on kbm.id_kwitansi=keu_kwitansi.id_kwitansi
left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank
inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
inner join keu_jenis_tagihan kjt on kt.kode_tagihan=kjt.kode_tagihan
inner join keu_jenis_pembayaran kjp on kjt.kode_pembayaran=kjp.kode_pembayaran
where is_removed=0
$kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $id_bank $tgl_bayar ");

//echo $db->getErrorMessage();

  $query = $datatable->get_custom("select tgl_validasi,keu_kwitansi.no_kwitansi,vs.nim,vs.nama,vs.mulai_smt,vs.jur_kode,periode,kjp.nama_pembayaran,metode_bayar, kbm.tgl_bayar,keu_bank.nama_singkat,kbm.id_kwitansi as id_bayar,
kbm.nominal_bayar from mahasiswa vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
inner join keu_kwitansi on kbm.id_kwitansi=keu_kwitansi.id_kwitansi
left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank
inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
inner join keu_jenis_tagihan kjt on kt.kode_tagihan=kjt.kode_tagihan
inner join keu_jenis_pembayaran kjp on kjt.kode_pembayaran=kjp.kode_pembayaran
where is_removed=0
$kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $id_bank $tgl_bayar ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $prodi_jenjang = getProdiJenjang();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->no_kwitansi;
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = getAngkatan($value->mulai_smt);
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    $ResultData[] = $value->nama_pembayaran;
    $ResultData[] = $value->periode;
    $ResultData[] = tgl_indo($value->tgl_bayar);
   // $ResultData[] = tgl_indo($value->tgl_validasi);
    $ResultData[] = rupiah($value->nominal_bayar);
    if ($value->nama_singkat!="") {
      if ($value->metode_bayar==3) {
        $ResultData[] = 'H2H '.$value->nama_singkat;
      } else {
        $ResultData[] = $value->nama_singkat;
      }
      
    } else {
      $ResultData[] = 'CASH';
    }

    $ResultData[] = '<button class="btn btn-danger btn-sm hapus_dtb_notif" data-toggle="tooltip" data-title="Hapus" data-uri="'.base_url().'dashboard/modul/laporan_pembayaran/laporan_pembayaran_action.php" data-variable="dtb_setting_tagihan_mahasiswa" data-id="'.$value->id_bayar.'"><i class="fa fa-trash"></i></button>';
    $data[] = $ResultData;
    $i++;
  }

$datatable->set_callback(array('total_bayar' => rupiah($total_bayar->total_bayar)));
//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>