<?php
include "../../inc/config.php";

$columns = array(
    'v_matkul_salam.shortname',
    'v_matkul_salam.nama_mk',
    'v_matkul_salam.category',
    'v_matkul_salam.shortname',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('sumary','v_matkul_salam.');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("v_matkul_salam.shortname");
  $datatable->set_group_by("group by shortname");

  //set order by type
  $datatable->set_order_type("desc");
  // $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by v_matkul_salam.";
  $semester = get_sem_aktif();
  $wh_sem =" and sem_id='$semester' ";
  $wh_fak = "";
  $wh_jur = "";
  if ($_POST['kode_fak']!='' && $_POST['kode_fak']!='all') {
     $wh_fak = " and kode_fak='".$_POST['kode_fak']."' ";
  }

  if ($_POST['kode_jur']!='' && $_POST['kode_jur']!='all') {
     $wh_jur = " and kode_jur='".$_POST['kode_jur']."' ";
  }

  if ($_POST['semester']!='') {
     $wh_sem = " and sem_id='".$_POST['semester']."' ";
  }

  $query = $datatable->get_custom("select id_matkul,v_matkul_salam.shortname,v_matkul_salam.nama_mk,v_matkul_salam.category,v_matkul_salam.shortname from v_matkul_salam
    where 1=1 $wh_fak $wh_jur $wh_sem ",$columns);
  // echo "select v_matkul_salam.shortname,v_matkul_salam.nama_mk,v_matkul_salam.category,v_matkul_salam.shortname from v_matkul_salam
  //   where 1=1 $wh_fak $wh_jur $wh_sem ";

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  //  $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $semester."-".$value->id_matkul."-".$value->shortname; 
    $ResultData[] = $semester."-".$value->id_matkul."-".$value->nama_mk; 
    $ResultData[] = $value->category;
    $ResultData[] = "";
    $ResultData[] = "";

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>