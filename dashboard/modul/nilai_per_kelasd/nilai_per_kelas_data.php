<?php
include "../../inc/config.php";

$columns = array(
    'krs_detail.nim',
    'krs_detail.id_krs_detail',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('kode_mk','krs_detail.id_krs_detail');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("krs_detail.id_krs_detail");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by krs_detail.id_krs_detail";

  $query = $datatable->get_custom("select krs_detail.nim,krs_detail.id_krs_detail from krs_detail",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->id_krs_detail;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>