<?php
include "../../inc/config.php";

$columns = array(
    'history_pendidikan.new_nipd',
    'history_pendidikan.id_jns_daftar',
    'history_pendidikan.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nipd','history_pendidikan.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("history_pendidikan.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by history_pendidikan.id";

  $query = $datatable->get_custom("select history_pendidikan.new_nipd,history_pendidikan.id_jns_daftar,history_pendidikan.id from history_pendidikan",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->new_nipd;
    $ResultData[] = $value->id_jns_daftar;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>