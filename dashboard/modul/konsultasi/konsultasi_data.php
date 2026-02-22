<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'pertanyaan',
    'jawaban',
    'tanggal_bimbingan',
    'id',
  );
  
  $datatable2->setNumberingStatus(0);

 $datatable2->setDebug(1);
  //set group by column
  //$new_table->group_by = "group by bimbingan_dosen_pa.id";
 //$datatable2->setOrderBy("mulai_smt desc");
$nim = $_POST['nim'];
$nip = $_POST['nip'];
$kategori = $_POST['jenis'];
$semester = get_sem_aktif();

$semester = get_sem_aktif();
  $query = $datatable2->execQuery("
select * from bimbingan_dosen_pa where nim='$nim' and nip='".$nip."' and id_semester='$semester' and kategori_konsultasi='$kategori'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->pertanyaan;
    $ResultData[] = $value->jawaban;
    $ResultData[] = tgl_time($value->tanggal_bimbingan);
    

    $ResultData[] = '<a data-id="'.$value->id.'" data-kat="'.$value->kategori_konsultasi.'" data-toggle="tooltip" title="Edit Data" class="btn btn-primary btn-sm edit_data data_selected_id"><i class="fa fa-pencil"></i></a>';

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>