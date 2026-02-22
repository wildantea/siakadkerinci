<?php
include "../../inc/config.php";

$columns = array(
    'kode_mk',
    'nama_mk',
    'id_matkul'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  //$datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("nama_mk");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kecamatan.id_kec";

  $query = $datatable->get_custom("select mk.id_matkul,mk.kode_mk,mk.nama_mk,prasyarat_mk.syarat from matkul
inner join kurikulum on matkul.kur_id=kurikulum.kur_id
inner join prasyarat_mk on matkul.id_matkul=prasyarat_mk.id_mk
inner join matkul mk on prasyarat_mk.id_mk_prasyarat=mk.id_matkul
where matkul.id_matkul=? ",$columns,array('id_matkul' => $_POST['id_mat']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = $value->kode_mk;
    $ResultData[] = $value->nama_mk;
    $ResultData[] = $value->syarat;
    $ResultData[] = $value->id_matkul;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>