<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'view_simple_mhs_data.nim',
    'view_simple_mhs_data.nama',
    'view_simple_mhs_data.angkatan',
    'view_simple_mhs_data.jurusan',
    'krs_detail.id_krs_detail',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_krs.nim","tb_data_kelas_krs.id_krs_detail");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("tb_data_kelas_krs.id_krs_detail desc");

  $id_kelas = $_POST['kelas_id'];


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_krs.id_krs_detail");
$datatable2->setDebug(1);
  $query = $datatable2->execQuery("select view_simple_mhs_data.nim,view_simple_mhs_data.nama,view_simple_mhs_data.angkatan,view_simple_mhs_data.jurusan,krs_detail.id_krs_detail from krs_detail inner join view_simple_mhs_data on krs_detail.nim=view_simple_mhs_data.nim where id_kelas='$id_kelas' and disetujui='1'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable2->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    $ResultData[] = $value->jurusan;
   // $ResultData[] = $value->id_krs_detail;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>