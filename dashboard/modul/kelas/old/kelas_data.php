<?php
include "../../inc/config.php";

$columns = array(
    'kelas.kls_nama',
    'kelas.kode_paralel',
    'kelas.peserta_max',
    'kelas.peserta_min',
    'matkul.nama_mk',
    'j.nama_jur',
    'kelas.kelas_id',
  );


  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('catatan','kelas.kelas_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("kelas.kelas_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by kelas.kelas_id";

  $query = $datatable->get_custom("select j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
    kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
    kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
    join kurikulum k on k.kur_id=matkul.kur_id
    join jurusan j on j.kode_jur=k.kode_jur 
    where j.kode_jur='".$_GET['prodi']."' and kelas.sem_id='".$_GET['semester']."'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kls_nama;
    $ResultData[] = $value->kode_paralel;
    $ResultData[] = $value->peserta_max;
    $ResultData[] = $value->peserta_min;
    $ResultData[] = $value->nama_mk;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->kelas_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();
//echo $_POST['prodi'];

?>