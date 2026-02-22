<?php
include "../../inc/config.php";

$columns = array(
    'pejabat.jabatan',
    'pejabat.nip',
    'dosen.nama_dosen',
    'pejabat.id_pejabat',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nip','pejabat.id_pejabat');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("pejabat.id_pejabat");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by pejabat.id_pejabat";

  $query = $datatable->get_custom("select pejabat.jabatan,pejabat.nip,dosen.nama_dosen,pejabat.id_pejabat from pejabat inner join dosen on pejabat.nip=dosen.nip",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->jabatan;
    $ResultData[] = $value->nip;
    $ResultData[] = $value->nama_dosen;
    $ResultData[] = $value->id_pejabat;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>