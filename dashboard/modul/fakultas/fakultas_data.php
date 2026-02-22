<?php
include "../../inc/config.php";

$columns = array(
    'fakultas.kode_fak',
    'fakultas.nama_resmi',
    'fakultas.nama_singkat',
    'dosen.nama_dosen',
    'fakultas.kode_fak',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('dekan','fakultas.kode_fak');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("fakultas.kode_fak");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by fakultas.kode_fak";

  $query = $datatable->get_custom("select fakultas.kode_fak,fakultas.nama_resmi,fakultas.nama_singkat,if(dekan is not null,dosen.nama_dosen,'') as dekan from fakultas left join dosen on fakultas.dekan=dosen.id_dosen",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_fak;
    $ResultData[] = $value->nama_resmi;
    $ResultData[] = $value->nama_singkat;
    $ResultData[] = $value->dekan;
    $ResultData[] = $value->kode_fak;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>