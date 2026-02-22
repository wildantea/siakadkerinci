<?php
include "../../inc/config.php";

$columns = array(
  'k.sks',
  'm.nama_mk',
  'm.kode_mk',
  'k.bobot',
  'k.nilai_huruf',
  'k.id_krs_detail'
);

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('id_alat_transport','mahasiswa.mhs_id');

  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("k.id_krs_detail");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";

  $krs = "";
  if(isset($_POST['krs'])){
    if($_POST['krs'] != 'all'){
      $krs = ' where k.id_krs="'.$_POST['krs'].'"';
    }
  }


  $query = $datatable->get_custom("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                                  join matkul m on m.id_matkul=k.kode_mk $krs group by k.id_krs_detail",$columns);

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
