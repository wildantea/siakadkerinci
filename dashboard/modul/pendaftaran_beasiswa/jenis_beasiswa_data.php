<?php
include "../../inc/config.php";

$columns = array(
  'bj.id_beasiswajns',
  'bj.jenis_beasiswajns',
  'bj.keterangan'
);

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ipk_beasiswamhs','beasiswa_mhs.id_beasiswamhs');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("bj.id_beasiswajns");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by beasiswa_mhs.id_beasiswamhs";

  $query = $datatable->get_custom("select bj.jenis_beasiswajns, bj.keterangan,bj.id_beasiswajns from beasiswa_jenis bj",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->jenis_beasiswajns;
    $ResultData[] = $value->keterangan;
    $ResultData[] = $value->id_beasiswajns;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>