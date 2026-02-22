<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'periode_bulan',
    'nm_ruang',
    'tanggal_seminar',
    'tb_jadwal_seminar.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('tgl_seminar','jadwal_ruang.id');
  
  //set numbering is true
  //$datatable->set_numbering_status(0);

  //set order by column
  $datatable->set_order_by("tb_jadwal_seminar.id");

  //set order by type
  $datatable->set_order_type("desc");

$jur_filter = "";
$periode_bulan = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and td.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and td.kode_jur in(0)";
}

$periode_bulan = "";
$sem_filter = "";

$jadwal_aktif = "and td.status_aktif='Y'";

if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and td.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and semester="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['periode_bulan']!='all') {
    $periode_bulan = ' and td.id="'.$_POST['periode_bulan'].'"';
  }
  $jadwal_aktif = "";
}

  //set group by column
  //$new_table->group_by = "group by jadwal_ruang.id";

  $query = $datatable->get_custom("select day(tanggal_seminar) as hari,tanggal_seminar,nm_ruang,periode_bulan,vp.jurusan,tb_jadwal_seminar.id from tb_jadwal_seminar
inner join tb_data_jadwal_pendaftaran td on tb_jadwal_seminar.id_jadwal_pendaftaran=td.id
inner join ruang_ref on tb_jadwal_seminar.id_ruang_seminar=ruang_ref.ruang_id
inner join tb_jenis_pendaftaran tj on td.id_pendaftaran=tj.id
inner join view_prodi_jenjang vp on td.kode_jur=vp.kode_jur
where tj.kode='03' $jadwal_aktif $jur_filter $sem_filter $periode_bulan ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;


  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = bulan_tahun($value->periode_bulan);
    $ResultData[] = $value->nm_ruang;
    $ResultData[] =  hari($value->tanggal_seminar).', '.tgl_indo($value->tanggal_seminar);
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