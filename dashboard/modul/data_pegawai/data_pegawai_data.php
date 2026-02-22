<?php
include "../../inc/config.php";

$columns = array(
    'pegawai.nip',
    'pegawai.nama_pegawai',
    'pegawai.no_hp',
    'pegawai.email',
    'pegawai.alamat',
    'pegawai.jk',
    'pegawai.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('foto','pegawai.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("pegawai.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by pegawai.id";

  $query = $datatable->get_custom("select pegawai.nip,pegawai.nama_pegawai,pegawai.no_hp,pegawai.email,pegawai.alamat,pegawai.jk,pegawai.id from pegawai",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nip;
    $ResultData[] = $value->nama_pegawai;
    $ResultData[] = $value->no_hp;
    $ResultData[] = $value->email;
    $ResultData[] = $value->alamat;
    $ResultData[] = $value->jk;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>