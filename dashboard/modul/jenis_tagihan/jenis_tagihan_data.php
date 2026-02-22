<?php
include "../../inc/config.php";

$columns = array(
    'keu_jenis_tagihan.kode_tagihan',
    'keu_jenis_tagihan.nama_tagihan',
    'keu_jenis_pembayaran.nama_pembayaran',
    'keu_jenis_tagihan.syarat_krs',
    'keu_jenis_tagihan.kode_tagihan',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('syarat_krs','keu_jenis_tagihan.kode_tagihan');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_jenis_tagihan.kode_tagihan");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_jenis_tagihan.kode_tagihan";

  $query = $datatable->get_custom("select keu_jenis_tagihan.kode_tagihan,keu_jenis_tagihan.nama_tagihan,keu_jenis_pembayaran.nama_pembayaran,keu_jenis_tagihan.syarat_krs from keu_jenis_tagihan inner join keu_jenis_pembayaran on keu_jenis_tagihan.kode_pembayaran=keu_jenis_pembayaran.kode_pembayaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_tagihan;
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = $value->nama_pembayaran;

    if ($value->syarat_krs=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
    
   $ResultData[] = $value->kode_tagihan;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>