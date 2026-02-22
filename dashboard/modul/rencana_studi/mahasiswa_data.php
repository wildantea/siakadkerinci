<?php
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.mulai_smt',
    'jurusan.nama_jur',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('npwp','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.mhs_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";



  $query = $datatable->get_custom("select mahasiswa.nim,mahasiswa.nama,mahasiswa.mulai_smt,jurusan.nama_jur,mahasiswa.mhs_id from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur",$columns);



  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    if (substr($value->mulai_smt, 0,4)==false) {
     $angkatan = "";
    }else{
      $angkatan = substr($value->mulai_smt, 0,4);
    }
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $angkatan;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->mhs_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>