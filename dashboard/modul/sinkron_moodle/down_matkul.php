<?php
include "../../inc/config.php";
  header('Content-Type: text/csv; charset=utf-8'); 
  header('Content-Disposition: attachment; filename=data_matkul.csv'); 
  $output = fopen("php://output", "w"); 
  fputcsv($output, array('shortname', 'fullname', 'category','summary'),";"); 
  $semester = get_sem_aktif();
  $wh_sem =" and sem_id='$semester' ";
  $wh_fak = "";
  $wh_jur = "";
  if ($_GET['kode_fak']!='' && $_GET['kode_fak']!='all') { 
     $wh_fak = " and kode_fak='".$_GET['kode_fak']."' ";
  }

  if ($_GET['kode_jur']!='' && $_GET['kode_jur']!='all') {
     $wh_jur = " and kode_jur='".$_GET['kode_jur']."' ";
  }

  if ($_GET['semester']!='') {
     $wh_sem = " and sem_id='".$_GET['semester']."' ";
  }

  $q = $db->query("select * from v_matkul_salam
    where 1=1 $wh_fak $wh_jur $wh_sem "); 


  foreach ($q as $k) {
       $data = array($semester."-".$k->id_matkul."-".$k->shortname,$semester."-".$k->id_matkul."-".$k->nama_mk,$k->category,'');
       fputcsv($output, $data,";");  
  }
      fclose($output); 


?>