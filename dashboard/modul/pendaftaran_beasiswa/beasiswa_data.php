<?php
include "../../inc/config.php";

$columns = array(
  "b.nama_beasiswa",
  "b.priode_beasiswa",
  "b.jns_beasiswa",
  "b.syarat",
  "b.id_beasiswa"
);

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ipk_beasiswamhs','beasiswa_mhs.id_beasiswamhs');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("b.id_beasiswa");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by beasiswa_mhs.id_beasiswamhs";

  $query = $datatable->get_custom("select b.nama_beasiswa,b.priode_beasiswa,b.jns_beasiswa,b.syarat,b.id_beasiswa,bj.jenis_beasiswajns from beasiswa b
    inner join beasiswa_jenis bj on b.jns_beasiswa=bj.id_beasiswajns",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nama_beasiswa;
    $ResultData[] = $value->jenis_beasiswajns;
    $ResultData[] = nl2br($value->syarat);
    $ResultData[] = $value->id_beasiswa;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>