<?php
include "../../inc/config.php";

$columns = array(
    'batas_sks.jlm_sks',
    'batas_sks.ket_batas',
    'batas_sks.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ket_batas','batas_sks.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("batas_sks.id");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by batas_sks.id";

  $query = $datatable->get_custom("select batas_sks.jlm_sks,batas_sks.ket_batas,batas_sks.id from batas_sks",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->jlm_sks;
    $ResultData[] = $value->ket_batas;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>