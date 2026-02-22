<?php
include "../../inc/config.php";

$columns = array(
    'transaksi_briva.no_briva',
    'transaksi_briva.nama',
    'transaksi_briva.jumlah',
    'transaksi_briva.tgl_bayar',
    'transaksi_briva.teller_id',
    'transaksi_briva.norek',
    'transaksi_briva.id_transaksi',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('norek','transaksi_briva.id_transaksi');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("transaksi_briva.id_transaksi");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by transaksi_briva.id_transaksi";

  $query = $datatable->get_custom("select transaksi_briva.no_briva,transaksi_briva.nama,transaksi_briva.jumlah,transaksi_briva.tgl_bayar,transaksi_briva.teller_id,transaksi_briva.norek,transaksi_briva.id_transaksi from transaksi_briva",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->no_briva;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->jumlah;
    $ResultData[] = $value->tgl_bayar;
    $ResultData[] = $value->teller_id;
    $ResultData[] = $value->norek;
    $ResultData[] = $value->id_transaksi;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>