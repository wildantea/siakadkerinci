<?php
include "../../inc/config.php";

$columns = array(
    'gedung_ref.kode_gedung',
    'gedung_ref.nm_gedung',
    'gedung_ref.is_aktif',
    'gedung_ref.gedung_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('is_aktif','gedung_ref.gedung_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("gedung_ref.gedung_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by gedung_ref.gedung_id";

  $query = $datatable->get_custom("select gedung_ref.kode_gedung,gedung_ref.nm_gedung,gedung_ref.is_aktif,gedung_ref.gedung_id from gedung_ref",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_gedung;
    $ResultData[] = $value->nm_gedung;
   
    if ($value->is_aktif=='Y') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Non Aktif</span>';
    }
    $ResultData[] = $value->gedung_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>