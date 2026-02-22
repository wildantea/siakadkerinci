<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_data_pendaftaran.nim',
    'view_simple_mhs_data.nama',
    'view_simple_mhs_data.no_hp',
    'tb_data_pendaftaran.date_created',
    'judul_proposal',
    'status_accepted',
    'view_simple_mhs_data.jurusan',
    'tb_data_pendaftaran.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('attr_value','tb_data_pendaftaran.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("tb_data_pendaftaran.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tb_data_pendaftaran.id";

$jur_filter = "";
$periode_bulan = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and view_simple_mhs_data.jur_kode in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and view_simple_mhs_data.jur_kode in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and tb_data_pendaftaran.periode_semester='".$semester_aktif->id_semester."'";

$tanggal_pendaftaran = "";
$status_accepted = "";
  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and jur_kode="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and periode_semester="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['status_accepted']!='all') {
    $status_accepted = ' and status_accepted="'.$_POST['status_accepted'].'"';
  }

  if ($_POST['tgl_daftar']=='') {
    $tanggal_pendaftaran = "";
  } else {
    $xpl = explode(" - ", $_POST['tgl_daftar']);
    $awal = $xpl[0];
    $akhir = $xpl[1];
    $tanggal_pendaftaran = "and tb_data_pendaftaran.date_created between '".$awal."' and '$akhir'";
  }
}

  $query = $datatable->get_custom("select judul_proposal,tb_data_pendaftaran.nim,view_simple_mhs_data.nama,view_simple_mhs_data.no_hp,periode_semester,tb_data_pendaftaran.date_created,status_accepted,view_simple_mhs_data.jurusan,tb_data_pendaftaran.id
 from tb_data_pendaftaran 
inner join view_simple_mhs_data on tb_data_pendaftaran.nim=view_simple_mhs_data.nim 
inner join tb_jenis_pendaftaran on kode_pendaftaran=tb_jenis_pendaftaran.kode 
where kode='03' $jur_filter $sem_filter $periode_bulan $status_accepted $tanggal_pendaftaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->no_hp;
    $ResultData[] = tgl_indo($value->date_created);
    $ResultData[] = $value->judul_proposal;
          if ($value->status_accepted=='diterima') {
$ResultData[] = '<div class="btn-group" id="stat_'.$value->id.'"><button type="button" class="btn btn-success btn-xs">Diterima</button>
                      <button type="button" class="btn btn-success dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                       <li><a onclick="change_status(\''.$value->id.'\',\'ditolak\')"><i class="fa fa-times-circle"></i> Tolak</a></li>
                       <li><a onclick="change_status(\''.$value->id.'\',\'menunggu\')"><i class="fa fa-thumb-tack"></i>Menunggu</a></li>
                      </ul></div>';
} elseif($value->status_accepted=='ditolak') {
  $ResultData[] = '<div class="btn-group" id="stat_'.$value->id.'"><button type="button" class="btn btn-danger btn-xs">Ditolak</button>
                      <button type="button" class="btn btn-danger dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a onclick="change_status(\''.$value->id.'\',\'diterima\')"><i class="fa fa-check"></i>Terima</a></li>
                        <li><a onclick="change_status(\''.$value->id.'\',\'menunggu\')"><i class="fa fa-thumb-tack"></i>Menunggu</a></li>
                      </ul></div>';
} elseif($value->status_accepted=='menunggu') {
  $ResultData[] = '<div class="btn-group" id="stat_'.$value->id.'"><button type="button" class="btn btn-warning btn-xs">Menunggu</button>
                      <button type="button" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                      <li><a onclick="change_status(\''.$value->id.'\',\'diterima\')"><i class="fa fa-check"></i>Terima</a></li>
                       <li><a onclick="change_status(\''.$value->id.'\',\'ditolak\')"><i class="fa fa-times-circle"></i> Tolak</a></li>
                      </ul></div>';
}
    $ResultData[] = $value->jurusan;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>