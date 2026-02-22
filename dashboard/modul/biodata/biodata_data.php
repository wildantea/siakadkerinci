<?php
include "../../inc/config.php";

$columns = array(
    'mahasiswa.jk',
    'mahasiswa.nisn',
    'mahasiswa.id_penghasilan_ayah',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('dosen_pemb','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.mhs_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";

  $query = $datatable->get_custom("select mahasiswa.jk,mahasiswa.nisn,mahasiswa.id_penghasilan_ayah,mahasiswa.mhs_id from mahasiswa",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->jk;
    $ResultData[] = $value->nisn;
    $ResultData[] = $value->id_penghasilan_ayah;
    $ResultData[] = $value->mhs_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>