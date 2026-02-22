<?php
include "../../inc/config.php";

$columns = array(
    'ruang_ref.nm_ruang',
    'gedung_ref.nm_gedung',
    'ruang_ref.kapasitas',
    'ruang_ref.ruang_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ket','ruang_ref.ruang_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("ruang_ref.ruang_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by ruang_ref.ruang_id";

  $query = $datatable->get_custom("select ruang_ref.nm_ruang,gedung_ref.nm_gedung,ruang_ref.kapasitas,ruang_ref.ruang_id from ruang_ref inner join gedung_ref on ruang_ref.gedung_id=gedung_ref.gedung_id",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = "<input type='checkbox' name='ruangan[]' value='$value->ruang_id===$value->nm_ruang===$value->nm_gedung' class='minimal'>";
  
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = $value->nm_gedung;
    $ResultData[] = $value->kapasitas;
  //  $ResultData[] = $value->ruang_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>