<?php
include "../../inc/config.php";

$columns = array(
    'jk.id_jns_keluar',
    'jk.ket_keluar',
    'jk.id_jns_keluar',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ket_keluar','jenis_keluar.id_jns_keluar');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("jk.id_jns_keluar");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by jenis_keluar.id_jns_keluar";

  $query = $datatable->get_custom("select jk.id_jns_keluar,jk.ket_keluar from jenis_keluar jk",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->id_jns_keluar;
    $ResultData[] = $value->ket_keluar;
    $ResultData[] = $value->id_jns_keluar;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>