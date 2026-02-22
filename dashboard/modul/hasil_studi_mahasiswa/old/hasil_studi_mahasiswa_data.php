<?php
include "../../inc/config.php";

$columns = array(
    'krs_detail.sks',
    'krs_detail.bobot',
    'krs_detail.nilai_huruf',
    'matkul.kode_mk',
    'matkul.nama_mk',
    'krs_detail.id_krs_detail',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nilai_huruf','krs_detail.id_krs_detail');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("krs_detail.id_krs_detail");

  //set order by type
  $datatable->set_order_type("desc");

  $krs = "";
  if(isset($_POST['krs'])){
    if($_POST['krs'] != 'all'){
      $krs = ' where krs_detail.id_krs="'.$_POST['krs'].'"';
    }
  }

  //set group by column
  //$new_table->group_by = "group by krs_detail.id_krs_detail";

  $query = $datatable->get_custom("select krs_detail.id_krs_detail,krs_detail.sks,krs_detail.bobot,krs_detail.nilai_huruf,matkul.kode_mk,matkul.nama_mk from krs_detail 
    inner join matkul on krs_detail.kode_mk=matkul.id_matkul $krs group by krs_detail.id_krs_detail",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_mk;
    $ResultData[] = $value->nama_mk;
    $ResultData[] = $value->sks;
    $ResultData[] = $value->bobot;
    $ResultData[] = $value->nilai_huruf;
    $ResultData[] = $value->id_krs_detail;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>