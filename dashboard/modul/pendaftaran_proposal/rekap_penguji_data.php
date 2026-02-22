<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nama_dosen',
    'count(nip_dosen)',
    'tanggal_seminar',
    'jurusan'
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array('count(nip_dosen)','tanggal_seminar','jurusan');
  
  //set numbering is true
  //$datatable->set_numbering_status(0);

  //set order by column
  $datatable->set_order_by("count(nip_dosen)");

  //set order by type
  $datatable->set_order_type("asc");

$jur_filter = "";
$periode_bulan = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vs.jur_kode in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vs.jur_kode in(0)";
}

$periode_bulan = "";
$sem_filter = "";


if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and tdj.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and semester="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['periode_bulan']!='all') {
    $periode_bulan = ' and tanggal_seminar="'.$_POST['periode_bulan'].'"';
  }
}


  //set group by column
  $datatable->group_by = "group by nip,tanggal_seminar";
  $query = $datatable->get_custom("select tanggal_seminar, nip,nama_dosen,vp.jurusan, count(tp.nip_dosen) as jml_menguji from dosen 
inner join tb_penguji tp on dosen.nip=tp.nip_dosen
inner join tb_data_pendaftaran on tp.id_pendaftar=tb_data_pendaftaran.id
inner join view_simple_mhs_data vs on tb_data_pendaftaran.nim=vs.nim
inner join tb_jenis_pendaftaran on kode_pendaftaran=tb_jenis_pendaftaran.kode
inner join view_prodi_jenjang vp on vs.jur_kode=vp.kode_jur
where kode='03' $jur_filter $sem_filter ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;


  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = $value->nama_dosen;
    $ResultData[] = $value->jml_menguji;
    $ResultData[] = hari($value->tanggal_seminar).', '.tgl_indo($value->tanggal_seminar);
    $ResultData[] = $value->jurusan;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>

