<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'id_jenis_pendaftaran',
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran_jenis.id_jenis_pendaftaran',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran_jenis.nama_directory","tb_data_pendaftaran_jenis.id_jenis_pendaftaran");
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

  //set order by column
  $datatable2->setOrderBy("tb_data_pendaftaran_jenis.id_jenis_pendaftaran asc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran_jenis.id_jenis_pendaftaran");

//$datatable2->setDebug(1);
  
  $query = $datatable2->execQuery("select tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jenis.nama_directory,tb_data_pendaftaran_jenis.id_jenis_pendaftaran from tb_data_pendaftaran_jenis",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->id_jenis_pendaftaran;
    $ResultData[] = $value->nama_jenis_pendaftaran;
    $ResultData[] = $value->id_jenis_pendaftaran;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>