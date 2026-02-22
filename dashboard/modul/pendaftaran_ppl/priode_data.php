<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'pk.priode',
    'pk.nama_periode',
    'pk.tgl_awal',
    'pk.tgl_akhir',
    'pk.id_priode'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','tugas_akhir.id_ta');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("pk.id_priode");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tugas_akhir.id_ta";



  $query = $datatable->get_custom("select pk.tgl_awal_input_nilai,pk.tgl_akhir_input_nilai, pk.nama_periode, pk.tgl_awal_daftar,pk.tgl_akhir_daftar, pk.aktif as status, pk.priode,pk.tgl_awal,pk.tgl_akhir,pk.id_priode,sr.*,j.* from priode_ppl pk join semester_ref sr on pk.priode=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester ",$columns);

  $data = array();
  $i=1;
  foreach ($query as $value) {

    //array data 
    $ResultData   = array(); 
    $ResultData[] = $datatable->number($i);  
    $ResultData[] = $value->jns_semester.' '.$value->tahun.' / '.($value->tahun+1);
    $ResultData[] = $value->nama_periode;
    $ResultData[] = $value->tgl_awal." s/d ".$value->tgl_akhir;
    
    $ResultData[] = $value->tgl_awal_daftar." s/d ".$value->tgl_akhir_daftar;
     $ResultData[] = $value->tgl_awal_input_nilai." s/d ".$value->tgl_akhir_input_nilai;
    if ($value->status==0) {  
      $ResultData[] = "<p style='color:red'>Tidak Aktif</p>";
    }else{
      $ResultData[] = "<p style='color:green'>Aktif<p>";
    }
    
    $ResultData[] = $value->id_priode;
     $ResultData[] = $value->status;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>