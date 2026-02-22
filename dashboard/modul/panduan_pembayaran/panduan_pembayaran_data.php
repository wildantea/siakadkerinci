<?php
include "../../inc/config.php";

$columns = array(
    'keu_bank.nama_singkat',
    'panduan_pembayaran.judul',
    'urutan',
    'panduan_pembayaran.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('updator','panduan_pembayaran.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("panduan_pembayaran.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by panduan_pembayaran.id";

  $query = $datatable->get_custom("select keu_bank.nama_singkat,panduan_pembayaran.judul,urutan,panduan_pembayaran.id from panduan_pembayaran inner join keu_bank on panduan_pembayaran.id_bank=keu_bank.kode_bank",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nama_singkat;
    $ResultData[] = $value->judul;
    $ResultData[] = $value->urutan;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>