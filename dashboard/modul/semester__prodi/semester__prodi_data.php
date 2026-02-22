<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tahun_akademik',
    'tgl_mulai_krs',
    'tgl_selesai_krs',
    'tgl_mulai_pkrs',
    'tgl_selesai_pkrs',
    'tgl_mulai_input_nilai',
    'tgl_selesai_input_nilai',
    'jurusan',
    'sem_id',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array('sem_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by_custom("order by vpj.kode_jur asc");


  //set group by column
  //$new_table->group_by = "group by kelas.kelas_id";
$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vpj.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vpj.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and semester.id_semester='".$semester_aktif->id_semester."'";
$matkul_filter = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vpj.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and semester.id_semester="'.$_POST['sem_filter'].'"';
  }


}


  $query = $datatable->get_custom("select semester.*,s.tahun_akademik,jurusan from semester inner join view_semester s 
on semester.id_semester=s.id_semester
inner join view_prodi_jenjang vpj on semester.kode_jur=vpj.kode_jur
     where sem_id is not null $sem_filter $jur_filter ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $dos = array();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->tahun_akademik;
    $ResultData[] = tgl_indo($value->tgl_mulai_krs);
    $ResultData[] = tgl_indo($value->tgl_selesai_krs);
    $ResultData[] = tgl_indo($value->tgl_mulai_pkrs);
    $ResultData[] = tgl_indo($value->tgl_selesai_pkrs);
    $ResultData[] = tgl_indo($value->tgl_mulai_input_nilai);
    $ResultData[] = tgl_indo($value->tgl_selesai_input_nilai);
    $ResultData[] = $value->jurusan;
    $ResultData[] = '<a href="semester--prodi/edit/'.$value->sem_id.'" data-id="'.$value->sem_id.'" class="btn edit_data btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit</a>';
    $data[] = $ResultData;
     $dos = array();
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>