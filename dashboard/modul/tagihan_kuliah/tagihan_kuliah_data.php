<?php
include "../../inc/config.php";

$columns = array(
    'keu_tagihan_mahasiswa.nim',
    'keu_tagihan_mahasiswa.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','keu_tagihan_mahasiswa.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

  $query = $datatable->get_custom("select keu_tagihan_mahasiswa.nim,keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>