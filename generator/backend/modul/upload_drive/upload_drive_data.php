<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tes.nama',
    'tes.file',
    'tes.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tes.file","tes.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tes.id desc");


  //set group by column
  //$datatable->setGroupBy("tes.id");

  $query = $datatable->execQuery("select tes.nama,tes.file,tes.id from tes",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nama;
    $ResultData[] = $value->file;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>