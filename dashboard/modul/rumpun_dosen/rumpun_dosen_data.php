<?php
include "../../inc/config.php";

$columns = array(
    'dwc.kode',
    'data_rumpun_dosen.nama_rumpun',
    'dw.nama_rumpun',
    'dwc.nama_rumpun',
    'dwc.kode',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_rumpun','data_rumpun_dosen.kode');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("dwc.kode");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by data_rumpun_dosen.kode";

  $query = $datatable->get_custom("select dwc.kode as kode, data_rumpun_dosen.nama_rumpun as rumpun,dw.nama_rumpun as sub_rumpun,dwc.nama_rumpun as bidang_ilmu 
from data_rumpun_dosen
inner join data_rumpun_dosen dw on data_rumpun_dosen.kode=dw.id_induk
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where data_rumpun_dosen.id_level='1'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode;
    $ResultData[] = $value->rumpun;
    $ResultData[] = $value->sub_rumpun;
    $ResultData[] = $value->bidang_ilmu;
    $ResultData[] = $value->kode;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>