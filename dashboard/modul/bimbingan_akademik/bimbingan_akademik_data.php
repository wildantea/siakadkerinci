<?php
session_start();
include "../../inc/config.php";
$qd = $db->query("select id_dosen from dosen where nip='".$_SESSION['username']."' "); 
  $wh_dosen = "";
  if ($qd->rowCount()>0) {
     foreach ($qd as $k) {
       $wh_dosen = " or dosen_pemb='$k->id_dosen' "; 
     }
     
  }
$db->query("drop view v_bimbingan_".$_SESSION['username']."");
$db->query("create view v_bimbingan_".$_SESSION['username']." as select *,
(select if((keu_tagihan.nominal_tagihan-ktm.potongan)='0','beasiswa',ktm.nim)  from keu_tagihan_mahasiswa ktm
left join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
inner join keu_tagihan on ktm.id_tagihan_prodi=keu_tagihan.id
inner join keu_jenis_tagihan kjt on keu_tagihan.kode_tagihan=kjt.kode_tagihan
where ktm.periode='".get_sem_aktif()."' 
and kjt.syarat_krs='Y' and ktm.nim=vmhs_bimbingan.nim limit 1) as is_bayar,
(select disetujui from view_krs_single2 where nim=vmhs_bimbingan.nim and id_semester='".get_sem_aktif()."' ) as disetujui,
 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=vmhs_bimbingan.nim 
and akm.sem_id!='".get_sem_aktif()."' and akm.sem_id<='".get_sem_aktif()."'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah,
(select sum(krs_detail.sks) AS sks  from krs_detail where nim=vmhs_bimbingan.nim and 
id_semester='".get_sem_aktif()."' and batal='0') as sks_diambil from vmhs_bimbingan
    where (dosen_pemb='".$_SESSION['username']."' $wh_dosen) group by nim  ");
// echo "create view v_bimbingan_".$_SESSION['username']." as select *,
// (select if((keu_tagihan.nominal_tagihan-ktm.potongan)='0','beasiswa',ktm.nim)  from keu_tagihan_mahasiswa ktm
// left join keu_bayar_mahasiswa kbm on ktm.id=kbm.id_keu_tagihan_mhs
// inner join keu_tagihan on ktm.id_tagihan_prodi=keu_tagihan.id
// inner join keu_jenis_tagihan kjt on keu_tagihan.kode_tagihan=kjt.kode_tagihan
// where ktm.periode='".get_sem_aktif()."' 
// and kjt.syarat_krs='Y' and ktm.nim=vmhs_bimbingan.nim
// ) as is_bayar,
// (select disetujui from view_krs_single2 where nim=vmhs_bimbingan.nim and id_semester='".get_sem_aktif()."' ) as disetujui,
//  (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=vmhs_bimbingan.nim 
// and akm.sem_id!='".get_sem_aktif()."' and akm.sem_id<='".get_sem_aktif()."'
// and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah,
// (select sum(krs_detail.sks) AS sks  from krs_detail where nim=vmhs_bimbingan.nim and 
// id_semester='".get_sem_aktif()."' and batal='0') as sks_diambil from vmhs_bimbingan
//     where (dosen_pemb='".$_SESSION['username']."' $wh_dosen) group by nim ";

$columns = array(
    'nim',
    'nama',
    'mulai_smt',
    'nama_jur',
    'status',
    'disetujui',
    'nim',
  );

//https://salaz.uinsgd.ac.id/dashboard/modul/rencana_studi/krs_dosen/rencana_data.php

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("disetujui");

  //set order by type
  $datatable->set_order_type("asc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";
  $wh = "";
  if ($_POST['status_mahasiswa']!='all') {
    $wh = " and status='".$_POST['status_mahasiswa']."' ";
  } 
  
  $query = $datatable->get_custom("select * from v_bimbingan_".$_SESSION['username']."
    where 1=1 $wh  ",$columns); 
//is_bayar  disetujui jatah sks_diambil
  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $disetujui = '<span class="btn btn-danger btn-xs">Belum Disetujui</span>';
    $jatah = $value->jatah;
    $diambil = 0;
    $bayar = '<span class="btn btn-danger btn-xs">Belum</span>';
    if ($value->is_bayar!='') {
      $bayar = '<span class="btn btn-success btn-xs">Sudah</span>';
    }
    if ($value->sks_diambil!='') {
      $diambil = $value->sks_diambil; 
    }
    if ($value->disetujui=='1') {
       $disetujui = '<span class="btn btn-success btn-xs">Sudah Disetujui</span>';
    }
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = substr($value->mulai_smt, 0,4) ;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->status;
    $ResultData[] = $disetujui;
    $ResultData[] = $jatah;
    $ResultData[] = $diambil;
    //$ResultData[] = $bayar;
    $ResultData[] = "<a target='_BLANK' href='".base_url()."dashboard/index.php/bimbingan-akademik/detail/".en($value->nim)."'  class='btn btn-success btn-sm' data-toggle='tooltip' title='Detail'><i class='fa fa-book'></i> KHS</a><a target='_BLANK' href='".base_url()."dashboard/index.php/rencana-studi/detail/?n=".en($value->nim)."&s=".en(get_sem_aktif())."'  style='margin-left:3px' class='btn btn-success btn-sm' data-toggle='tooltip' title='Detail'><i class='fa fa-book' ></i> KRS</a>";
    $ResultData[] = en($value->nim);

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>