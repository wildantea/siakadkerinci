<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nama_kurikulum',
    'jurusan',
    'tahun_akademik',
    'jml_sks_wajib',
    'jml_sks_pilihan',
    'sks_total',
    'count(matkul.id_matkul)',
    'kur_id'
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array('jml_sks_wajib','count(matkul.id_matkul)','jml_sks_pilihan','sks_total','jml_matkul','kur_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("kur_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  $datatable->group_by = "group by kurikulum.kur_id";


$jurusan = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jurusan = "and view_prodi_jenjang.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jurusan = "and view_prodi_jenjang.kode_jur in(0)";
}

$fakultas = "";
$sem_id = "";
  
  if (isset($_POST['jurusan'])) {

/*  if ($_POST['fakultas']!='all') {
    $fakultas = ' and jurusan.fak_kode="'.$_POST['fakultas'].'"';
  }
*/
  if ($_POST['jurusan']!='all') {
    $jurusan = ' and view_prodi_jenjang.kode_jur="'.$_POST['jurusan'].'"';
  }

    if ($_POST['sem_id']!='all') {
    $sem_id = ' and kurikulum.sem_id="'.$_POST['sem_id'].'"';
  }

}

$data_query = "select kurikulum.nama_kurikulum,
view_prodi_jenjang.jurusan,view_semester.tahun_akademik,
kurikulum.kur_id,kurikulum.jml_sks_wajib,kurikulum.jml_sks_pilihan,
(jml_sks_wajib+kurikulum.jml_sks_pilihan) as sks_total,count(matkul.id_matkul) as jml_matkul
 from kurikulum
 left join matkul on kurikulum.kur_id=matkul.kur_id
 inner join view_prodi_jenjang on kurikulum.kode_jur=view_prodi_jenjang.kode_jur
 inner join view_semester on kurikulum.sem_id=view_semester.id_semester where kurikulum.kur_id is not null
 $jurusan $sem_id ";
 /*echo "select kurikulum.nama_kurikulum,
view_prodi_jenjang.jurusan,view_semester.tahun_akademik,
kurikulum.kur_id,kurikulum.jml_sks_wajib,kurikulum.jml_sks_pilihan,
(jml_sks_wajib+kurikulum.jml_sks_pilihan) as sks_total,count(matkul.id_matkul) as jml_matkul
 from kurikulum
 left join matkul on kurikulum.kur_id=matkul.kur_id
 inner join view_prodi_jenjang on kurikulum.kode_jur=view_prodi_jenjang.kode_jur
 inner join view_semester on kurikulum.sem_id=view_semester.id_semester where kurikulum.kur_id is not null
 $jurusan $sem_id";*/

  
  //$datatable->set_debug(1);
  $query = $datatable->get_custom($data_query, $columns);


  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nama_kurikulum;
    $ResultData[] = ucwords(strtolower($value->jurusan));
    $ResultData[] = $value->tahun_akademik;
    $ResultData[] = $value->jml_sks_wajib;
    $ResultData[] = $value->jml_sks_pilihan;
    $ResultData[] = $value->sks_total;
    $ResultData[] = $value->jml_matkul;
    $ResultData[] = $value->kur_id;
    
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>