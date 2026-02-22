<?php
session_start();
include "../../../../inc/config.php";

$columns = array(
    'nip',
    'nama_gelar',
    'penanggung_jawab',
    'id_hari',
    'id'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable->setNumberingStatus(0);

  $datatable->setOrderBy("dosen_ke desc");

  //$datatable->setGroupBy("nip");

  $query = $datatable->execQuery("select * from view_jadwal_dosen where kelas_id=?",$columns,array('kelas_id' => $_POST['kelas_id']));

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = '';
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->nip;
    if ($_SESSION['group_level']=='root' or $_SESSION['group_level']=='administrator') {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nip.'&adm_id='.$_SESSION['id_user'].'&url=dosen&back_uri=kelas-jadwal" class="btn btn-success btn-xs" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }
    $ResultData[] = $value->nama_gelar.' '.$login_as;
    if ($value->penanggung_jawab=='Y') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Ya</span>';
    } else {
       $ResultData[] = '<span class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Tidak</span>';
    }
    $ResultData[] = $value->nama_hari.', '.$value->jam_mulai.' - '.$value->jam_selesai;

$ResultData[] = '<button class="btn btn-primary btn-xs edit-pengajar-modal" data-toggle="tooltip" title="Edit Dosen Pengajar" data-nip="'.$value->nip.'" data-id="'.$value->id_jadwal_dosen.'"><i class="fa fa-pencil"></i></button> <button class="btn btn-danger btn-xs hapus_dtb_notif_pengajar" data-id="'.$value->id_jadwal_dosen.'" data-uri="'.base_admin().'modul/kelas_jadwal/jadwal/dosen_pengajar/dosen_pengajar_action.php" data-variable="dtb_dosen_pengajar" data-toggle="tooltip" title="Hapus Dosen Pengajar" ><i class="fa fa-trash"></i></button>';

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>