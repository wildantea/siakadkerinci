<?php
include "../../inc/config.php";

$columns = array(
    'tabel_berita.judul',
    'tabel_berita.tampil',
    'tabel_berita.created_by',
    'tabel_berita.date_created',
    'tabel_berita.id_news',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('tampil','tabel_berita.id_news');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("tabel_berita.id_news");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tabel_berita.id_news";

  $query = $datatable->get_custom("select tabel_berita.judul,tabel_berita.tampil,tabel_berita.date_created,tabel_berita.created_by,tabel_berita.id_news from tabel_berita",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->judul;
    if ($value->tampil=='Y') {
      $ResultData[] = '<span class="label label-success">Ya</span>';
    } else {
      $ResultData[] = '<span class="label label-default">Tidak</span>';
    }
    $ResultData[] = $value->created_by;
    $ResultData[] = tgl_indo($value->date_created);
    $ResultData[] = $value->id_news;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>