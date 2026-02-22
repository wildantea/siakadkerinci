<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mhs.nm_pd',
    'mhs.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("mhs.nm_pd","mhs.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("mhs.id desc");


  //set group by column
  //$datatable->setGroupBy("mhs.id");

  $query = $datatable->execQuery("select mhs.nm_pd,mhs.id from mhs",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
  
    $ResultData[] = $value->nm_pd;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>