<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'nip',
    'nama_gelar',
    'nama_jurusan'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  //$datatable->set_order_by("dosen");

  //set order by type
  //$datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kecamatan.id_kec";

  $query = $datatable->execQuery("select * from view_dosen ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = '<button class="btn btn-success pilih-dosen" data-nip="'.$value->nip.'" data-toggle="tooltip" title="Pilih Dosen"><i class="fa fa-plus"></i></button>'; 
    $ResultData[] = $value->nip;
    $ResultData[] = $value->nama_gelar;
    $ResultData[] = $value->nama_jurusan;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>