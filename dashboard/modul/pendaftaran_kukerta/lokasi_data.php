<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'lk.nama_lokasi',
    'lk.nama_dosen',
    'lk.kuota',
    'lk.id_lokasi'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','tugas_akhir.id_ta');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("lk.id_lokasi");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tugas_akhir.id_ta";
  $wh = "";
  if ($_POST['priode']!='all') {
    $wh = " where id_periode='".$_POST['priode']."' ";
  }


  $query = $datatable->get_custom("select * from vlokasikkn lk $wh ",$columns);

  $data = array();
  $i=1;
  foreach ($query as $value) { 
    $tabel = "<ul>";
                $qj = $db->query("select  l.id_lokasi, l.kuota, l.kode_jur,jurusan.nama_jur 
                  from jurusan join kuota_jurusan_kkn l  on l.kode_jur=jurusan.kode_jur where id_lokasi='$value->id_lokasi' ");
                foreach ($qj as $kj) { 
                  $tabel .= "
                          <li style='list-style-type:none'>$kj->nama_jur <label class='label label-primary'>$kj->kuota</label></li>";
                }
    $tabel .="</ul>";  

    //array data
    $ResultData = array(); 
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nama_lokasi; 
    $ResultData[] = $value->nama_periode;
    //$ResultData[] = $value->jk;
    $ResultData[] = $value->nama_dosen;
     $ResultData[] = $value->nama_dosen2;
    $ResultData[] = $value->kuota;
    $ResultData[] = $value->kuota_l;
    $ResultData[] = $value->kuota_p;
    $ResultData[] = $tabel;
    $ResultData[] = $value->jml;
    $ResultData[] = $value->id_lokasi;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>