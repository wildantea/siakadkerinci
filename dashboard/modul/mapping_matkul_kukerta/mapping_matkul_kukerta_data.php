<?php
include "../../inc/config.php";

$columns = array(
    'matkul_kukerta.kode_mk',
    'matkul_kukerta.mk',
    'matkul_kukerta.kode_mk',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('jurusan','matkul_kukerta.');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("matkul_kukerta.kode_mk");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by matkul_kukerta.";

  $query = $datatable->get_custom("select matkul_kukerta.kode_mk,matkul_kukerta.mk,matkul_kukerta.kode_mk from matkul_kukerta",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_mk;
    $ResultData[] = $value->mk;
    $ResultData[] = $value->kode_mk;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>