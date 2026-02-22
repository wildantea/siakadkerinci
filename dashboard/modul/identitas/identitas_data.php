<?php
include "../../inc/config.php";

$columns = array(
    'identitas.id_identitas',
    'identitas.ket',
    'identitas.id_identitas',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('isi','identitas.id_identitas');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("identitas.id_identitas");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by identitas.id_identitas";

  $query = $datatable->get_custom("select identitas.id_identitas,identitas.ket from identitas",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->id_identitas;
    $ResultData[] = $value->ket;
    $ResultData[] = $value->id_identitas;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>