<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tdp.nim',
    'vs.nama',
    'tanggal_seminar',
    'tp.id',
    'vs.jurusan',
    'tp.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('tgl_seminar','jadwal_ruang.id');
  
  //set numbering is true
  //$datatable->set_numbering_status(0);

  //set order by column
  $datatable->set_order_by("tp.id");

  //set order by type
  $datatable->set_order_type("desc");

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
    $jur_filter = ' and vs.jur_kode="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and semester="'.$_POST['sem_filter'].'"';
  }

}

  //set group by column
  //$new_table->group_by = "group by jadwal_ruang.id";

  $query = $datatable->get_custom("select vs.jurusan, vs.jur_kode, tdp.nim,vs.nama,tanggal_seminar,tdp.id 
from tb_data_pendaftaran tdp 
inner join view_simple_mhs_data vs on tdp.nim=vs.nim
inner join tb_jenis_pendaftaran tj on kode_pendaftaran=tj.kode
where tj.kode='03' $jur_filter $sem_filter $periode_bulan ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;


  foreach ($query as $value) {

    $check_penguji = $db->fetch_custom_single("select group_concat(penguji_ke,'. ',nama_dosen separator '<br>') as penguji from tb_penguji tp
inner join dosen on tp.nip_dosen=dosen.nip
where id_pendaftar=?
order by penguji_ke asc",array('id_pendaftar' => $value->id));

    //array data
    $ResultData = array();
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] =  ($value->tanggal_seminar)?hari($value->tanggal_seminar).', '.tgl_indo($value->tanggal_seminar):'';
    if ($check_penguji->penguji!='') {
      $ResultData[] = '<button class="btn btn-sm btn-success" data-html="true"  data-toggle="popover" data-trigger="hover" data-content="'.$check_penguji->penguji.'<br>" data-original-title="Penguji"><i class="fa fa-check"></i></button>';
    } else {
      $ResultData[] = '<span class="btn btn-sm btn-danger" data-toggle="tooltip" title="Penguji Belum Di Atur"><i class="fa fa-warning"></i><span>';
    }
    $ResultData[] = $value->jurusan;

    $ResultData[] = '<a class="btn btn-primary btn-sm edit_penguji" data-id="'.$value->id.'" data-nim="'.$value->nim.'" data-toggle="tooltip" title="Edit Penguji"><i class="fa fa-pencil"></i></a>';

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>

