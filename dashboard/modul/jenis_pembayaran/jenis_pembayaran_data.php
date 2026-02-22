<?php
include "../../inc/config.php";

$columns = array(
    'keu_jenis_pembayaran.kode_pembayaran',
    'keu_jenis_pembayaran.nama_pembayaran',
    'keu_jenis_pembayaran.kode_pembayaran',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_pembayaran','keu_jenis_pembayaran.kode_pembayaran');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_jenis_pembayaran.kode_pembayaran");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_jenis_pembayaran.kode_pembayaran";

  $query = $datatable->get_custom("select keu_jenis_pembayaran.kode_pembayaran,keu_jenis_pembayaran.nama_pembayaran from keu_jenis_pembayaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_pembayaran;
    $ResultData[] = $value->nama_pembayaran;
    $ResultData[] = $value->kode_pembayaran;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>