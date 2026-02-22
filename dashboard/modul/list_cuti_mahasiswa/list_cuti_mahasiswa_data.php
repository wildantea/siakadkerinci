<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'cm.nim',
    'm.nama',
    'f.nama_resmi',
    'j.nama_jur',
    'jk.ket_keluar',
    'cm.file_sk',
    'cm.id_cuti',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('keterangan','cuti_mahasiswa.id_cuti');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("cm.id_cuti");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by cuti_mahasiswa.id_cuti";

  $fakultas="";
  $jurusan="";

  if(isset($_POST['jurusan'])){
    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }
  }

  if($_SESSION['id_fak'] != NULL){
    $fakultas = ' and f.kode_fak="'.$_SESSION['id_fak'].'"';
  }

  if($_SESSION['id_jur'] != NULL){
    $jurusan = ' and j.kode_jur="'.$_SESSION['id_jur'].'"';
  }

  $query = $datatable->get_custom("select cm.nim,m.nama,f.nama_resmi,j.nama_jur,jk.ket_keluar,cm.file_sk,cm.id_cuti from cuti_mahasiswa cm inner join mahasiswa m on cm.nim=m.nim 
    inner join jenis_keluar jk on cm.jenis_keluar=jk.id_jns_keluar 
    inner join fakultas f on cm.kode_fak=f.kode_fak 
    inner join jurusan j where cm.kode_jur=j.kode_jur $fakultas $jurusan order by cm.id_cuti",$columns);

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
    if($value->file_sk != "") {
      $ResultData[] = $value->file_sk;
    }else{
      $ResultData[] = "File tidak tersedia";
    }
    $ResultData[] = $value->id_cuti;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>