<?php
session_start();
include "../../../../inc/config.php";

$columns = array(
    'createdAt',
    'createdBy',
    'file_rps',
    'id_rps'
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("file_dosen.createdAt","file_dosen.id_file");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("file_dosen.id_file desc");


  //set group by column
  //$datatable2->setGroupBy("file_dosen.id_file");

$datatable2->setDebug(1);
$kelas_id = $_POST['kelas_id'];
$kelas_data = $db2->fetchCustomSingle("SELECT sem_id,id_matkul from view_nama_kelas where kelas_id=?",array('kelas_id' => $kelas_id));
$nips = $db2->fetchCustomSingle("select group_concat(id_dosen) as nip from dosen_kelas where id_kelas='$kelas_id'");

$query = $datatable2->execQuery("select * from rps_file where semester='".$kelas_data->sem_id."' and id_matkul='".$kelas_data->id_matkul."' and nip in($nips->nip)",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = tgl_indo($value->createdAt);
    $ResultData[] = '<a target="_blank" href="'.$value->file_rps.'" class="btn btn-primary"><i class="fa fa-cloud-download"></i> Download File</a>';

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>