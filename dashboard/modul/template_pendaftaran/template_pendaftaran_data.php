<?php
include "../../inc/config.php";

$columns = array(
    'tb_data_pendaftaran.id_semester',
    'tb_data_pendaftaran.id_pendaftaran',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('kode_jurusan','tb_data_pendaftaran.id_pendaftaran');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("tb_data_pendaftaran.id_pendaftaran");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tb_data_pendaftaran.id_pendaftaran";

  $query = $datatable->get_custom("select tb_data_pendaftaran.id_semester,tb_data_pendaftaran.id_pendaftaran from tb_data_pendaftaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->id_semester;
    $ResultData[] = $value->id_pendaftaran;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>