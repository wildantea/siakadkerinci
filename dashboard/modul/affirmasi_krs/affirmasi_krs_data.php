<?php
include "../../inc/config.php";

$columns = array(
    'affirmasi_krs.nim',
    'view_simple_mhs_data.nama',
    'affirmasi_krs.periode',
    'affirmasi_krs.ket_affirmasi',
    'view_simple_mhs_data.jurusan',
    'affirmasi_krs.id_affirmasi',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('periode','affirmasi_krs.id_affirmasi');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("affirmasi_krs.id_affirmasi");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by affirmasi_krs.id_affirmasi";

  $query = $datatable->get_custom("select affirmasi_krs.nim,ket_affirmasi,view_simple_mhs_data.nama,affirmasi_krs.periode,view_simple_mhs_data.jurusan,affirmasi_krs.id_affirmasi from affirmasi_krs inner join view_simple_mhs_data on affirmasi_krs.nim=view_simple_mhs_data.nim",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->periode;
    $ResultData[] = $value->ket_affirmasi;
    $ResultData[] = $value->jurusan;
    $ResultData[] = $value->id_affirmasi;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>