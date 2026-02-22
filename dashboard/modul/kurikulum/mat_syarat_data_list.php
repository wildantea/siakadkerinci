<?php
include "../../inc/config.php";

$columns = array(
    'kode_mk',
    'nama_mk',
    'id_matkul'
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array('id_matkul');
  
  //set numbering is true
  //$datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("nama_mk");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kecamatan.id_kec";

/*echo $datatable->get_debug("select id_matkul,matkul.kode_mk,matkul.nama_mk from matkul
inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kurikulum.kur_id=?",$columns,array('kur_id' => $_POST['kur_id']));*/

  $query = $datatable->get_custom("select id_matkul,matkul.kode_mk,matkul.nama_mk from matkul
inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kurikulum.kur_id=?
and matkul.id_matkul !=? and matkul.id_matkul 
not in(select id_mk_prasyarat from prasyarat_mk p where p.id_mk=?)
",$columns,array(
  'kur_id' => $_POST['kur_id'],
  'id_matkul' => $_POST['id_mat'],
  'id_mat' => $_POST['id_mat']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
    $ResultData[] = $value->kode_mk;
    $ResultData[] = $value->nama_mk;
    $ResultData[] = $value->id_matkul;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>