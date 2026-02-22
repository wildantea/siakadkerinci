<?php
include "../../inc/config.php";

$columns = array(
    'ppl.nim',
    'ppl.kode_fak',
    'ppl.id_kkn',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('kode_jur','ppl.id_kkn');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("ppl.id_kkn");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by ppl.id_kkn";

  $query = $datatable->get_custom("select ppl.nim,ppl.kode_fak,ppl.id_kkn from ppl",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->kode_fak;
    $ResultData[] = $value->id_kkn;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>