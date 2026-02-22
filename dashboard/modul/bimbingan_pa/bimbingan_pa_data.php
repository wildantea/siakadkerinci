<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'view_simple_mhs_data.nim',
    'view_simple_mhs_data.nama',
    'view_simple_mhs_data.angkatan',
    'view_simple_mhs_data.jurusan',
    'ip',
    'ipk',
    'id_stat_mhs',
    '(select message from chat_message where chat_message.nim=view_simple_mhs_data.nim order by created_at desc limit 1)',
    'view_simple_mhs_data.nim',
  );
  
$datatable2->setDisableSearchColumn(
    'is_photo_drived',
    'ip',
    'ipk',
    'id_stat_mhs');
  //set numbering is true
  $datatable2->setNumberingStatus(1);

 $datatable2->setDebug(1);
  //set group by column
  //$new_table->group_by = "group by bimbingan_dosen_pa.id";
 $datatable2->setOrderBy("mulai_smt desc");
$datatable2->setFromQuery("view_simple_mhs_data where nip_dosen_pa='".$_SESSION['username']."' and nim not in(select nim from tb_data_kelulusan)");

$semester = get_sem_aktif();
  $query = $datatable2->execQuery("select *,
(SELECT is_photo_drived FROM sys_users WHERE username=view_simple_mhs_data.nim) AS is_photo_drived,
(select ip from akm where mhs_nim=view_simple_mhs_data.nim and sem_id='$semester') as ip,
(select ipk from akm where mhs_nim=view_simple_mhs_data.nim and sem_id='$semester') as ipk,
(select id_stat_mhs from akm where mhs_nim=view_simple_mhs_data.nim and sem_id='$semester') as id_stat_mhs,
    (SELECT foto_user FROM sys_users WHERE username=view_simple_mhs_data.nim) AS foto_mhs,
(select akm_id from akm where mhs_nim=view_simple_mhs_data.nim and sem_id='$semester') as krs,
  (select message from chat_message where chat_message.nim=view_simple_mhs_data.nim order by created_at desc limit 1) as pesan from view_simple_mhs_data where nip_dosen_pa='".$_SESSION['username']."' and nim not in(select nim from tb_data_kelulusan)",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    if ($value->is_photo_drived=='Y') {
       $ResultData[] = $datatable2->number($i)."<img src='$value->foto_mhs=w50' style='width:50px' />";
    } else {
       $ResultData[] = $datatable2->number($i)."<img src='".base_url()."upload/back_profil_foto/$value->foto_mhs' style='width:50px' />";
    }
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    $ResultData[] = $value->jurusan;
    
     $ResultData[] = $value->ip;
      $ResultData[] = $value->ipk;

    if ($value->id_stat_mhs=='A') {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Aktif"><i class="fa fa-check"></i> Aktif</span>';
    } elseif ($value->id_stat_mhs=='C') {
       $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Sedang Cuti"><i class="fa fa-warning"></i> Cuti</span>';
    } elseif ($value->id_stat_mhs=='N') {
       $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Non-aktif"><i class="fa fa-warning"></i> Non-Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-xs btn-info btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Non-aktif"><i class="fa fa-info"></i> '.(isset($stat_mahasiswa[$value->id_stat_mhs])?$stat_mahasiswa[$value->id_stat_mhs]:'').'</span>';
    }
    if ($value->krs!="") {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Ambil KRS di Semester Sekarang"><i class="fa fa-check"></i> KRS</span>';
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini tidak Ambil KRS di Semester Sekarang"><i class="fa fa-warning"></i> Tidak</span>';
    }
    if ($value->pesan!='') {
      if (strlen($value->pesan)<20) {
         $ResultData[] = substr($value->pesan, 0,20);
      } else {
         $ResultData[] = substr($value->pesan, 0,20).' ...';
      }
    } else {
      $ResultData[] = '';
    }
    
    $ResultData[] = '<a href="'.base_index_new().'konsultasi/lihat/'.$value->nim.'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Klik untuk Melihat Detail"><i class="fa fa-eye"></i> Detail </a>';


    $data[] = $ResultData;
    $i++;
  }
      

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>