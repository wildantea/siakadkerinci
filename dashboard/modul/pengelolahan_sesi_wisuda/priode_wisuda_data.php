<?php
include "../../inc/config.php";

$columns = array(
    'kw.nama',
    'kw.id_wisuda'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','tugas_akhir.id_ta');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("kw.id_wisuda");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tugas_akhir.id_ta";

  $query = $datatable->get_custom("select *,kw.id_wisuda,kw.nama_wisuda,kw.kuota,kw.tempat,kw.biaya
  from kelola_wisuda kw order by kw.id_wisuda",$columns);

  //buat inisialisasi array data
  $data = array();
  $i=1;
  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->priode;
    $ResultData[] = $value->nama_wisuda;
    $ResultData[] = $value->tempat;
    $ResultData[] = $value->biaya;
    $ResultData[] = $value->kuota;
    $ResultData[] = $value->tanggal;
    $ResultData[] = $value->id_wisuda;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>