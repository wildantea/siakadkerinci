<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'id_muna',
    'priode_muna',
    'batas_awal',
    'batas_akhir',
    'tanggal_sidang'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('penguji_2','ta.id_ta');

  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("id_muna");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by ta.id_ta";


  $query = $datatable->get_custom("select * from jadwal_muna join semester_ref s on s.id_semester= jadwal_muna.priode_muna join jenis_semester j on s.id_jns_semester=j.id_jns_semester order by s.id_semester",$columns);

  //buat inisialisasi array data
  $data = array();
  $i=1;
  $jenis = "";
  foreach ($query as $value) {
    $jenis = $value->jns_semester." ".$value->tahun. " / ".($value->tahun+1);
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $jenis;
    $ResultData[] = $value->batas_awal;
    $ResultData[] = $value->batas_akhir;
    $ResultData[] = $value->tanggal_sidang;
    $ResultData[] = $value->id_muna;
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>
