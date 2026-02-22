<?php
session_start();
include "../../inc/config.php";
session_check();

$columns = array(
    'kkn.nim',
    'mahasiswa.nama',
    'mahasiswa.jk',
    'fakultas.nama_resmi',
    'jurusan.nama_jur',
    'nama_lokasi',
    'kkn.id_kkn',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('id_lokasi','kkn.id_kkn');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.nama");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by kkn.id_kkn";

  $fakultas="";
  $jurusan="";
  $priode="";
  $lokasi ="";
  $jk = ""; 

  $jur_filter = "";
  $jur_filter2 = "";
//get default akses prodi 
if ($_SESSION['group_level']!='dosen') {
  //echo "string";
  $akses_prodi = get_akses_prodi();
  $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
  if ($akses_jur) {
    $jur_filter = "and jurusan.kode_jur in(".$akses_jur->kode_jur.")";
  } else {
  //jika tidak group tidak punya akses prodi, set in 0
    $jur_filter = "and jurusan.kode_jur in(0)";
  }
  //echo "$jur_filter";
}

  if(isset($_POST['fakultas'])) {
    if($_POST['fakultas']!='all') {
      $fakultas = ' and fakultas.kode_fak="'.$_POST['fakultas'].'"';
    }
  }

  if(isset($_POST['jurusan'])) {
    if($_POST['jurusan']!='all') {
      $jur_filter = ' and jurusan.kode_jur="'.$_POST['jurusan'].'"';
    }
  }

  if(isset($_POST['priode'])) {
    if($_POST['priode']!='all') {
      $priode = ' and priode_kkn.id_priode="'.$_POST['priode'].'"';
    }
  }  

  if(isset($_POST['id_lokasi'])) {
    if($_POST['id_lokasi']!='all') {
      $lokasi = ' and lk.id_lokasi="'.$_POST['id_lokasi'].'"';
    }
  } 

  if(isset($_POST['jk'])) {
    if($_POST['jk']!='all') {
      $jk = ' and mahasiswa.jk="'.$_POST['jk'].'"';
    }
  } 


/*  if($_SESSION['level']=='6'){
    if($_SESSION['id_fak'] != NULL){
      $fakultas = ' and fakultas.kode_fak="'.$_SESSION['id_fak'].'"';
    }
  }*/

  // if($_SESSION['level']=='5') {
  //   if($_SESSION['id_jur'] != NULL){
  //     $jurusan = ' and jurusan.kode_jur="'.$_SESSION['id_jur'].'"';
  //   }
  // }
// echo "select lk.nama_lokasi,mahasiswa.jk, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from ppl kkn 
//     inner join mahasiswa on kkn.nim=mahasiswa.nim 
//     inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
//     inner join jurusan on kkn.kode_jur=jurusan.kode_jur 
//     left join priode_ppl priode_kkn on priode_kkn.id_priode=kkn.id_priode
//     left join lokasi_ppl lk on lk.id_lokasi=kkn.id_lokasi
//     where id_kkn is not null $fakultas $jur_filter $jur_filter2 $priode $lokasi $jk";
 

  $query = $datatable->get_custom("select lk.nama_lokasi,mahasiswa.jk, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from ppl kkn 
    inner join mahasiswa on kkn.nim=mahasiswa.nim 
    inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
    inner join jurusan on kkn.kode_jur=jurusan.kode_jur 
    left join priode_ppl priode_kkn on priode_kkn.id_priode=kkn.id_priode
    left join lokasi_ppl lk on lk.id_lokasi=kkn.id_lokasi
    where id_kkn is not null $fakultas $jur_filter  $priode $lokasi $jk",$columns);
 
  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->jk;
    $ResultData[] = $value->nama_resmi;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->nama_lokasi;
    $ResultData[] = $value->id_kkn;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>