<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'nip',
    'nama_gelar',
    'penanggung_jawab',
    'id_hari'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

  $datatable2->setOrderBy("dosen_ke asc");

  //$datatable2->setGroupBy("nip");
$datatable2->setDebug(1);
  $query = $datatable2->execQuery("select nip,dosen_ke,nama_gelar,nm_ruang,hari as nama_hari,jam_mulai,jam_selesai from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip
inner join ruang_ref on view_jadwal_dosen_kelas.id_ruang=ruang_ref.ruang_id
 where id_kelas=?",$columns,array('id_kelas' => $_POST['kelas_id']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = '';
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->nip;
    $ResultData[] = $value->nama_gelar;
    $ResultData[] = ucwords($value->nama_hari).', '.substr($value->jam_mulai,0,5).' - '.substr($value->jam_selesai,0,5);
    $ResultData[] = $value->nm_ruang;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>