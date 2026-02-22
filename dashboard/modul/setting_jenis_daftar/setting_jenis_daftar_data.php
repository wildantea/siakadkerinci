<?php
include "../../inc/config.php";

$columns = array(
    'jenis_daftar.id_jenis_daftar',
    'jenis_daftar.nm_jns_daftar',
    'jenis_daftar.id_jenis_daftar',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nm_jns_daftar','jenis_daftar.id_jenis_daftar');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("jenis_daftar.id_jenis_daftar");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by jenis_daftar.id_jenis_daftar";

  $query = $datatable->get_custom("select jenis_daftar.id_jenis_daftar,jenis_daftar.nm_jns_daftar from jenis_daftar",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->id_jenis_daftar;
    $ResultData[] = $value->nm_jns_daftar;
    $ResultData[] = $value->id_jenis_daftar;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>