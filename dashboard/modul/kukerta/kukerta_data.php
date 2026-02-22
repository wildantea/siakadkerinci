<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'v_dpl.priode',
    'v_dpl.nama_periode',
    'v_dpl.nama_lokasi',
    'v_dpl.nm_dpl1',
    'v_dpl.nm_dpl2',
    'v_dpl.id_lokasi',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nip2','v_dpl.');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("v_dpl.priode");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by v_dpl.";
  $wh = ""; 
  if ($_SESSION['group_level']=='dosen') { 
    $wh = " where nip='".$_SESSION['username']."' or nip2='".$_SESSION['username']."'   ";
  }

  $query = $datatable->get_custom("select tgl_awal_input_nilai,tgl_akhir_input_nilai,v_dpl.priode,v_dpl.nama_periode,v_dpl.nama_lokasi,v_dpl.nm_dpl1,v_dpl.nm_dpl2,v_dpl.id_lokasi from v_dpl $wh",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->priode;
    $ResultData[] = $value->nama_periode;
    $ResultData[] = $value->nama_lokasi;
    $ResultData[] = "1. $value->nm_dpl1 <br>2. $value->nm_dpl2";
   // $ResultData[] = $value->nm_dpl2;

    if (date('Y-m-d') >= $value->tgl_awal_input_nilai && date('Y-m-d') <= $value->tgl_akhir_input_nilai) {
       $ResultData[] =  '<a href="'.base_index_new().'kukerta/input_nilai/'.en($value->id_lokasi).'" class="btn btn-primary btn-sm edit_data" data-toggle="tooltip" title="Edit"><i class="fa fa-book"></i> Input Nilai</a> <a target="_BLANK" href="'.base_url().'dashboard/modul/kukerta/cetak.php?id='.en($value->id_lokasi).'" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';
    } else {
       $ResultData[] = '<span class="btn btn-xs btn-danger"><i class="icon fa fa-warning"></i> Waktu Input Nilai Berakhir '.tgl_indo($value->tgl_akhir_input_nilai).'</span> <a target="_BLANK" href="'.base_url().'dashboard/modul/kukerta/cetak.php?id='.en($value->id_lokasi).'" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';
    }

   

    //$ResultData[] = en($value->id_lokasi);

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>