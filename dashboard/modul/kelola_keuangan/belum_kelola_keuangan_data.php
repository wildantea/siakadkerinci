<?php
include "../../inc/config.php";

$columns = array(
    'm.nim',    
    'm.nama',
    'j.nama_jur',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('no_kwitansi','mhs_registrasi.');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("m.nim");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mhs_registrasi.";

  $semester = "";
  $jurusan = "";

  if (isset($_POST['jurusan'])) {

    if ($_POST['semester']!='all') {
      $semester = ' and mr.sem_id="'.$_POST['semester'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }
  }


  $query = $datatable->get_custom("select m.nim,m.nama,j.nama_jur from mahasiswa m 
inner join jurusan j on j.kode_jur = m.jur_kode
where not exists(
  select * from mhs_registrasi mr where m.nim = mr.nim $semester
)",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = "Tidak Ada No Kwitansi";
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = "Tidak Bisa Dihapus";

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>