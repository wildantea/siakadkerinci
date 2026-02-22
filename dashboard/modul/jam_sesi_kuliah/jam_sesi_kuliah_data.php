<?php
include "../../inc/config.php";

$columns = array(
    'sesi_waktu.sesi',
    'sesi_waktu.jam_mulai',
    'sesi_waktu.jam_selesai',
    'sesi_waktu.id_sesi',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('jam_selesai','sesi_waktu.id_sesi');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("sesi_waktu.id_sesi");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by sesi_waktu.id_sesi";

  $query = $datatable->get_custom("select sesi_waktu.sesi,sesi_waktu.jam_mulai,sesi_waktu.jam_selesai,sesi_waktu.id_sesi from sesi_waktu",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->sesi;
    $ResultData[] = $value->jam_mulai;
    $ResultData[] = $value->jam_selesai;
    $ResultData[] = $value->id_sesi;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>