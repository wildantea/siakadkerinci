<?php
include "../../inc/config.php";
$columns = array(
    'm.id_matkul',
    'j.kode_jur',
    'f.kode_fak',
    'k.kur_id'
  );
  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('id_jurusan','mhs.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("k.kur_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mhs.id";

$awal = "";
$akhir = "";
  
  if (isset($_POST['awal'])) {

  if ($_POST['awal']!='all') {
    $awal = 'and k.sem_id < "'.$_POST['awal'].'"';
  }

  if ($_POST['akhir']!='all') {
    $akhir = 'and k.sem_id > "'.$_POST['akhir'].'"';
  }

}


  $query = $datatable->get_custom("select *,count(m.id_matkul) as jml, j.nama_jur, k.kur_id, k.sem_id from kurikulum k left join matkul m on k.kur_id=m.kur_id join jurusan j on j.kode_jur=k.kode_jur join fakultas f where f.kode_fak=j.fak_kode $awal $akhir group by k.kur_id", $columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nama_kurikulum;
    $ResultData[] = $value->tahun_mulai_berlaku;
    $ResultData[] = $value->jml_sks_wajib;
    $ResultData[] = $value->jml_sks_pilihan;
    $ResultData[] = $value->total_sks;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->jml;
    $ResultData[] = $value->kur_id;
     
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>