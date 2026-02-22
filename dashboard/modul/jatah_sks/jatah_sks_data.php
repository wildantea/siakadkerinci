<?php
include "../../inc/config.php";

$columns = array(
    'jatah_sks.ip_min',
    'jatah_sks.ip_mak',
    'jatah_sks.sks_mak',
    'jatah_sks.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('sks_mak','jatah_sks.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("jatah_sks.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by jatah_sks.id";

  $query = $datatable->get_custom("select jatah_sks.ip_min,jatah_sks.ip_mak,jatah_sks.sks_mak,jatah_sks.id from jatah_sks",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->ip_min;
    $ResultData[] = $value->ip_mak;
    $ResultData[] = $value->sks_mak;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>