<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nip',
    'dosen',
    'jml_kelas',
    'sks_s1',
    'sks_s2',
    'total'
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
    'jml_kelas',
    'sks_s1',
    'sks_s2',
    'total'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  //$datatable2->set_order_type("desc");

$jur_filter = aksesProdi('view_nama_kelas.kode_jur');

$kode_tagihan = "";
$kode_pembayaran = "";
$fakultas = "";
$mulai_smt = "";
$fakultas = "";

  if (isset($_POST['sem_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and view_nama_kelas.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('view_nama_kelas.kode_jur',$_POST['fakultas']);
  }

}

$sem_id = $_POST['sem_filter'];

$datatable2->setDebug(1);

  //set group by column
//$datatable2->setGroupBy("dosen.nip, dosen.nama_dosen");
//$datatable2->setDebug(1);
$datatable2->setFromQuery("view_dosen

where nip in(select id_dosen from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id' $jur_filter $fakultas)");
$query = $datatable2->execQuery("select view_dosen.*,
(select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id'
 and dosen_kelas.id_dosen=view_dosen.nip and view_nama_kelas.kode_jur in (select kode_jur from jurusan where id_jenjang=30) ) as sks_s1,
(select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id'
 and dosen_kelas.id_dosen=view_dosen.nip and view_nama_kelas.kode_jur in (select kode_jur from jurusan where id_jenjang=35) ) as sks_s2,
(select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id'
 and dosen_kelas.id_dosen=view_dosen.nip) as total,
 (select count(id_kelas) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id' and id_dosen=view_dosen.nip ) as jml_kelas
 from view_dosen

where nip in(select id_dosen from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='$sem_id' $jur_filter $fakultas)",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  foreach ($query as $value) {
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nip;
    $ResultData[] = $value->dosen;
    $ResultData[] = $value->jml_kelas;
    if ($value->sks_s1>0) {
      $ResultData[] = $value->sks_s1;
    } else {
      $ResultData[] = 0;
    }
    if ($value->sks_s2>0) {
      $ResultData[] = $value->sks_s2;
    } else {
      $ResultData[] = 0;
    }
    
    $ResultData[] = $value->total;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();
?>