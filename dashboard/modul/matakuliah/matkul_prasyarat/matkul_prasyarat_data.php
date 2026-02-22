<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'nama_mk',
    'semester',
    'syarat'
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("matkul.semester","matkul.id_matkul");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("semester asc");

  $datatable2->setDebug(1);
  $query = $datatable2->execQuery("select matkul.id_matkul,prasyarat_mk.id_mk,kode_mk,nama_mk,syarat,id_mk_prasyarat,semester from matkul
inner join prasyarat_mk on matkul.id_matkul=prasyarat_mk.id_mk_prasyarat
where prasyarat_mk.id_mk=?",$columns,array('id_matkul' => $_POST['id_matkul']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = '<span data-toggle="tooltip" data-title="Hapus Syarat" class="btn btn-xs btn-danger hapus-syarat" data-id="'.$value->id_mk.'#'.$value->id_mk_prasyarat.'"><i class="fa fa-trash"></i></span>';
  
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk;
    $ResultData[] = $value->semester;
    if ($value->syarat=='L') {
      $ResultData[] = "Harus Sudah lulus";
    } else {
      $ResultData[] = "Boleh Diambil Saja";
    }

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>