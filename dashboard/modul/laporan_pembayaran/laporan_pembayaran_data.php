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
    'kbm.tgl_bayar',
    'kbm.tgl_validasi',
    'nominal_tagihan',
    'nama_singkat',
    'kbm.id_kwitansi'

  );

  //if you want to exclude column for searching, put columns name in array
  $Newtable->setDisableSearchColumn(
    'mulai_smt',
    'jur_kode',
    'nama_pembayaran',
    'periode',
    'kbm.tgl_bayar',
    'kbm.tgl_validasi',
    'nominal_tagihan',
    'nama_singkat'
  );
  
  //set numbering is true
  $Newtable->setNumberingStatus(1);

  //set order by column
  $Newtable->setOrderBy("keu_kwitansi.id_kwitansi desc");

  //$Newtable->setGroupBy("kbm.id_kwitansi");

$Newtable->setDebug(1);
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
    $mulai_smt = " and left(mulai_smt,4) between '".$_POST['mulai_smt']."' and '".$_POST['mulai_smt_end']."'";
  }

  if ($_POST['periode']!='all') {
    $periode = ' and periode="'.$_POST['periode'].'"';
  } else {
    $periode = "";
  }

  //   if ($_POST['kode_pembayaran']!='all') {
  //   $kode_pembayaran = ' and kjp.kode_pembayaran="'.$_POST['kode_pembayaran'].'"';
  // }
      if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and kt.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }
  if ($_POST['id_bank']!='all') {
    $id_bank = ' and keu_kwitansi.id_bank="'.$_POST['id_bank'].'"';
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
//c39dbfde32724006ac88c7446c7fca04
  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

$total_bayar = $db->fetch_custom_single("select sum(kbm.nominal_bayar) as total_bayar from mahasiswa vs
inner join keu_tagihan_mahasiswa ktm on vs.nim=ktm.nim
inner join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
inner join keu_kwitansi on kbm.id_kwitansi=keu_kwitansi.id_kwitansi
left join keu_bank on kbm.id_bank=keu_bank.kode_bank
inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
inner join keu_jenis_tagihan kjt on kt.kode_tagihan=kjt.kode_tagihan
inner join keu_jenis_pembayaran kjp on kjt.kode_pembayaran=kjp.kode_pembayaran
where is_removed=0
$kode_prodi $fakultas $mulai_smt $periode $kode_pembayaran $kode_tagihan $id_bank $tgl_bayar ");

//echo $db->getErrorMessage();

  $query = $Newtable->execQuery("select tgl_validasi,keu_kwitansi.no_kwitansi,vs.nim,vs.nama,vs.mulai_smt,vs.jur_kode,periode,kjt.nama_tagihan,metode_bayar,keterangan,kbm.tgl_bayar,keu_bank.nama_singkat,kbm.id_kwitansi as id_bayar,tgl_validasi,
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
    $ResultData[] = $Newtable->number($i);
  
    $ResultData[] = $value->no_kwitansi;
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = getAngkatan($value->mulai_smt);
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = $value->periode;
    $ResultData[] = tgl_indo($value->tgl_bayar);
    $ResultData[] = tgl_indo($value->tgl_validasi);
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

/*     $ResultData[] = '<a style="cursor:pointer" data-toggle="tooltip" data-title="Lihat Detail Pembayaran" data-placement="left" class="btn btn-success btn-sm" onclick="showDetilCicilan('.$value->id_bayar.')"><i class="fa fa-hourglass-end"></i></a>'; */
    $ResultData[] = '<a style="cursor:pointer" data-toggle="tooltip" data-title="Lihat Detail Pembayaran" data-placement="left" class="btn btn-success btn-sm" onclick="showDetilCicilan('.$value->id_bayar.')"><i class="fa fa-hourglass-end"></i></a> <button class="btn btn-danger btn-sm hapus_dtb_notif" data-toggle="tooltip" data-title="Hapus" data-uri="'.base_url().'dashboard/modul/history_bayar/history_bayar_action.php" data-variable="dtb_setting_tagihan_mahasiswa" data-id="'.$value->id_bayar.'"><i class="fa fa-trash"></i></button>';

    $data[] = $ResultData;
    $i++;
  }

$Newtable->setCallback(array('total_bayar' => rupiah($total_bayar->total_bayar)));
//set data
$Newtable->setData($data);
//create our json
$Newtable->createData();

?>