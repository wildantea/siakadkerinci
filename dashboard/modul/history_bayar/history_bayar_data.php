<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'keu_kwitansi.no_kwitansi',
    'view_simple_mhs_data.nim',
    'view_simple_mhs_data.nama',
    'keu_kwitansi.nominal_bayar',
    'keu_kwitansi.tgl_bayar',
    'keu_kwitansi.date_created',
    'keu_bank.nama_singkat',
    'keu_kwitansi.keterangan',
    'view_simple_mhs_data.jurusan',
    'keu_kwitansi.id_kwitansi',

  );
  
  //set numbering is true
  $Newtable->setNumberingStatus(1);


  //set order by column
  $Newtable->setOrderBy("keu_kwitansi.id_kwitansi desc");

//$Newtable->setGroupBy("keu_kwitansi.id_kwitansi");
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
//c39dbfde32724006ac88c7446c7fca04
  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

//echo $db->getErrorMessage();

  $total_bayar = $db->fetch_custom_single("select sum(nominal_bayar) as total_bayar from keu_kwitansi inner join mahasiswa on nim_mahasiswa=nim left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank where 1=1
$kode_prodi $fakultas $mulai_smt $id_bank $tgl_bayar $validator",$columns);

//$Newtable->setDebug(1);
  $query = $Newtable->execQuery("select metode_bayar,keu_kwitansi.no_kwitansi,view_simple_mhs_data.nim,view_simple_mhs_data.nama,keu_kwitansi.nominal_bayar,keu_kwitansi.tgl_bayar,keu_bank.nama_singkat,keu_kwitansi.date_created,keu_kwitansi.keterangan,view_simple_mhs_data.jurusan,keu_kwitansi.id_kwitansi from keu_kwitansi inner join view_simple_mhs_data on keu_kwitansi.nim_mahasiswa=view_simple_mhs_data.nim left join keu_bank on keu_kwitansi.id_bank=keu_bank.kode_bank where 1=1
$kode_prodi $fakultas $mulai_smt $id_bank $tgl_bayar $validator",$columns);

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
    $ResultData[] = rupiah($value->nominal_bayar);
    $ResultData[] = tgl_indo($value->tgl_bayar);
    $ResultData[] = tgl_indo($value->date_created);
    if ($value->nama_singkat!="") {
      if ($value->metode_bayar==3) {
        $ResultData[] = 'H2H '.$value->nama_singkat;
      } else {
        $ResultData[] = $value->nama_singkat;
      }
      
    } else {
      $ResultData[] = 'CASH';
    }
    $ResultData[] = $value->keterangan;
    $ResultData[] = $value->jurusan;
    $ResultData[] = '<a style="cursor:pointer" data-toggle="tooltip" data-title="Lihat Detail Pembayaran" data-placement="left" class="btn btn-success btn-sm" onclick="showDetilCicilan('.$value->id_kwitansi.')"><i class="fa fa-hourglass-end"></i></a> <button class="btn btn-danger btn-sm hapus_dtb_notif" data-toggle="tooltip" data-title="Hapus" data-uri="'.base_url().'dashboard/modul/history_bayar/history_bayar_action.php" data-variable="dtb_history_bayar" data-id="'.$value->id_kwitansi.'"><i class="fa fa-trash"></i></button>';

    $data[] = $ResultData;
    $i++;
  }

$Newtable->setCallback(array('total_bayar' => rupiah($total_bayar->total_bayar)));
//set data
$Newtable->setData($data);
//create our json
$Newtable->createData();

?>