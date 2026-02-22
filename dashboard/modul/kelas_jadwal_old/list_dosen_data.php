<?php
include "../../inc/config.php";

$columns = array(
    'nip',
    'dosen',
    'jurusan_dosen'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("dosen");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kecamatan.id_kec";

  $query = $datatable->get_custom("select * from view_dosen ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = '<button class="btn btn-success" data-toggle="tooltip" title="Pilih Dosen" onclick="pilih_dosen('.$value->id_dosen.')"><i class="fa fa-plus"></i></button>'; 
    $ResultData[] = $value->nip;
    $ResultData[] = $value->dosen;
    $ResultData[] = $value->jurusan_dosen;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>