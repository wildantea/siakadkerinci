<?php
session_start();
include "../../inc/config.php";
session_check();

$columns = array(
    'k.nim',
    'm.nama',
    'f.nama_resmi',
    'j.nama_jur',
    'k.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','kompre.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("k.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by kompre.id";

  $fakultas="";
  $jurusan="";
  $priode="";

  if(isset($_POST['fakultas'])){
    
    if ($_POST['fakultas']!='all') {
      $fakultas = ' and f.kode_fak="'.$_POST['fakultas'].'"';
    }
  }

  if(isset($_POST['jurusan'])) {
    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }
  }

  if(isset($_POST['priode'])) {
    if ($_POST['priode']!='all') {
      $priode = ' and p.id_kompre="'.$_POST['priode'].'"';
    }
  }

  if($_SESSION['id_fak'] != NULL){
    $fakultas = ' and f.kode_fak="'.$_SESSION['id_fak'].'"';
  }

  if($_SESSION['id_jur'] != NULL){
    $jurusan = ' and j.kode_jur="'.$_SESSION['id_jur'].'"';
  }

  $query = $datatable->get_custom("select k.nim,m.nama,f.nama_resmi,j.nama_jur,k.id from kompre k
    inner join mahasiswa m on k.nim=m.nim
    inner join jadwal_kompre p on p.id_kompre=k.priode_kompre
    inner join fakultas f on k.kode_fak=f.kode_fak 
    inner join jurusan j on k.kode_jurusan=j.kode_jur 
    WHERE k.id is not null $fakultas $jurusan $priode ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_resmi;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>