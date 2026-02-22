<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'nama_mk',
    'semester',
    'nama_kurikulum',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("matkul.semester","matkul.id_matkul");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("semester asc");

  $datatable2->setDebug(1);
  $query = $datatable2->execQuery("select nama_kurikulum,matkul_setara.id_matkul_lama as id_matkul,id_matkul_baru,kode_mk,nama_mk,semester from matkul
inner join matkul_setara on matkul.id_matkul=matkul_setara.id_matkul_baru
inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where matkul_setara.id_matkul_lama=?",$columns,array('id_matkul_lama' => $_POST['id_matkul']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = '<span data-toggle="tooltip" data-title="Hapus Matkul" class="btn btn-xs btn-danger hapus-setara" data-id="'.$value->id_matkul.'#'.$value->id_matkul_baru.'"><i class="fa fa-trash"></i></span>';
  
    $ResultData[] = $value->nama_kurikulum;
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk;
    $ResultData[] = $value->semester;
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>