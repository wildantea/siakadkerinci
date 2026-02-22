<?php
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.stat_pd',
    'akm.sem_id',
    'akm.jatah_sks',
    'akm.ip',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('stat_pd','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.mhs_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";

  $query = $datatable->get_custom("select mahasiswa.nim,mahasiswa.nama,mahasiswa.stat_pd,akm.sem_id,akm.jatah_sks,akm.ip, akm.ipk, mahasiswa.mhs_id from mahasiswa inner join akm on mahasiswa.nim=akm.mhs_nim",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->stat_pd;
    $ResultData[] = $value->sem_id;
    $ResultData[] = $value->jatah_sks;
    $ResultData[] = $value->ip;
    $ResultData[] = $value->ipk;
    $ResultData[] = $value->mhs_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>