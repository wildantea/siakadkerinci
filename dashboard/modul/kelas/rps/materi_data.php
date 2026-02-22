<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'pertemuan',
    'materi',
    'link_materi',
     'createdAt',
      'nip',
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


$datatable2->setFromQuery("rps_materi_kuliah where id_kelas='".$kelas_id."'");
$query = $datatable2->execQuery("select rps_materi_kuliah.*,(select nama_gelar from view_nama_gelar_dosen where nip=rps_materi_kuliah.createdBy) as nama_dosen,
(select nama_gelar from view_nama_gelar_dosen where nip=rps_materi_kuliah.updatedBy) as nama_dosen_update from rps_materi_kuliah where id_kelas='".$kelas_id."'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->pertemuan;
    $ResultData[] = $value->materi;
    $ResultData[] = '<a target="_blank" href="'.$value->link_materi.'">'.$value->link_materi.'</a>';
    if ($value->updatedAt!='') {
      $ResultData[] = tgl_indo($value->updatedAt);
       $ResultData[] = $value->nama_dosen_update;
    } else {
      $ResultData[] = tgl_indo($value->createdAt);
       $ResultData[] = $value->nama_dosen;
    }
    
    
     $ResultData[] = $value->id_materi;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>