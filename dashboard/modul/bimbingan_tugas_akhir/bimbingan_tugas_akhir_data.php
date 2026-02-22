<?php
session_start();
error_reporting(0);
include "../../inc/config.php";

$columns = array(
    'ta.nim',
    'm.nama',
    'ta.pembimbing_1',
    'ta.pembimbing_2',
    'f.nama_resmi',
    'j.nama_jur',
    'ta.id_ta',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('pembimbing_2','tugas_akhir.id_ta');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("ta.id_ta");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tugas_akhir.id_ta";

  $pem="";
  $fakultas = "";
  $jurusan = "";

  if (isset($_POST['fakultas'])) {

    if ($_POST['fakultas']!='all') {
      $fakultas = ' and f.kode_fak="'.$_POST['fakultas'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }

  }

  if(array_key_exists('id_jur', $_SESSION) && $_SESSION['id_jur'] != NULL){
    $jurusan = ' and j.kode_jur="'.$_SESSION['id_jur'].'"';
  }

  if($_SESSION['level'] == '4'){
      $pem = ' and pem_1.no_hp="'.$_SESSION['dosen'].'"';
  }

  $query = $datatable->get_custom("select ta.nim,m.nama,pem_1.nama_dosen as pem_1,pem_2.nama_dosen as pem_2,f.nama_resmi,j.nama_jur,ta.id_ta from tugas_akhir ta 
    inner join mahasiswa m on ta.nim=m.nim
    inner join dosen pem_1 on pem_1.id_dosen=ta.pembimbing_1
    inner join dosen pem_2 on pem_2.id_dosen=ta.pembimbing_2
    inner join fakultas f on ta.kode_fak=f.kode_fak 
    inner join jurusan j where ta.kode_jurusan=j.kode_jur $fakultas $jurusan $pem group by ta.id_ta",$columns);
  /*echo "select ta.nim,m.nama,pem_1.nama_dosen as pem_1,pem_2.nama_dosen as pem_2,f.nama_resmi,j.nama_jur,ta.id_ta from tugas_akhir ta 
    inner join mahasiswa m on ta.nim=m.nim
    inner join dosen pem_1 on pem_1.id_dosen=ta.pembimbing_1
    inner join dosen pem_2 on pem_2.id_dosen=ta.pembimbing_2
    inner join fakultas f on ta.kode_fak=f.kode_fak 
    inner join jurusan j where ta.kode_jurusan=j.kode_jur $fakultas $jurusan $pem group by ta.id_ta";*/

  //buat inisialisasi array data
  $data = array();

  $i=1;
  if ($query->rowCount()>0) {
      foreach ($query as $value) {

    if($_SESSION['level'] == '4'){
      $ResultData = array();
      $ResultData[] = $datatable->number($i);
      $ResultData[] = $value->nim;
      $ResultData[] = $value->nama;
      $ResultData[] = $value->nama_resmi;
      $ResultData[] = $value->nama_jur;
    } else{
      //array data
      $ResultData = array();
      $ResultData[] = $datatable->number($i);
      $ResultData[] = $value->nim;
      $ResultData[] = $value->nama;
      $ResultData[] = $value->pem_1;
      $ResultData[] = $value->pem_2;
      $ResultData[] = $value->nama_resmi;
      $ResultData[] = $value->nama_jur;
      $ResultData[] = $value->id_ta;
    }

    $data[] = $ResultData;
    $i++;
  }
  }


//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>