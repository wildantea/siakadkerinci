<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'periode_pembayaran.periode_bayar',
    'periode_pembayaran.tgl_mulai',
    'periode_pembayaran.tgl_selesai',
    'periode_pembayaran.id_periode_pembayaran'
  );
  
  //set numbering is true
  $Newtable->setNumberingStatus(1);

  //set order by column
  $Newtable->setOrderBy("periode_pembayaran.periode_bayar desc");

//echo $db->getErrorMessage();

  $query = $Newtable->execQuery("select periode_pembayaran.periode_bayar,tgl_mulai,tgl_selesai,is_active,periode_pembayaran.tgl_mulai,periode_pembayaran.tgl_selesai,periode_pembayaran.id_periode_pembayaran from periode_pembayaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $prodi_jenjang = getProdiJenjang();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = ganjil_genap($value->periode_bayar);
    $ResultData[] = tgl_indo($value->tgl_mulai);
    $ResultData[] = tgl_indo($value->tgl_selesai);
    if (strtotime(date('Y-m-d H:i:s')) >= strtotime($value->tgl_mulai.'00:00:00') && strtotime(date('Y-m-d H:i:s')) <= strtotime($value->tgl_selesai.'23:59:59') && $value->tgl_mulai!="") {
      $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan ini Aktif"><i class="fa fa-check"></i> Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Diluar Periode"><i class="fa fa-close"></i> Tidak</span>';
    }
    $ResultData[] = $value->id_periode_pembayaran;
    $data[] = $ResultData;
    $i++;
  }

//set data
$Newtable->setData($data);
//create our json
$Newtable->createData();

?>