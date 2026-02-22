<?php
include "../../inc/config.php";

$columns = array(
    'mr.nim',    
    'mr.no_kwitansi',
    'm.nama',
    'j.nama_jur',
    's.sem_id',
    'mr.id_reg'

  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('no_kwitansi','mhs_registrasi.');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mr.id_reg");

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


  $query = $datatable->get_custom("select *,mr.id_reg,mr.nim,mr.no_kwitansi,m.nama,j.nama_jur from mhs_registrasi mr 
inner join mahasiswa m on mr.nim=m.nim 
inner join jurusan j on m.jur_kode=j.kode_jur 
inner join semester s on mr.sem_id=s.id_semester 
where mr.id_reg is not null $jurusan $semester
group by mr.id_reg",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->no_kwitansi;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->id_reg;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>