<?php
include "../../inc/config.php";
  $semester = get_sem_aktif();
  if ($_GET['semester']!='') {
    $semester = $_GET['semester']; 
  }
  if ($_GET['kode_fak']!='all') {
    $q = $db->query("select nama_resmi from fakultas where kode_fak='".$_GET['kode_fak']."' ");
    foreach ($q as $k) {
      $nama_fak = str_replace(" ", "_", $k->nama_resmi);
    }
     header('Content-Type: text/csv; charset=utf-8'); 
      header('Content-Disposition: attachment; filename=dosen_kelas_'.$nama_fak.'_'.$semester.'.csv'); 
      $output = fopen("php://output", "w"); 
  }



  if ($_GET['kode_jur']!='all' && $_GET['kode_jur']!='') {
    $q = $db->query("select nama_jur from jurusan where kode_jur='".$_GET['kode_jur']."' ");
    foreach ($q as $k) {
      $nama_jur = str_replace(" ", "_", $k->nama_jur);
    }
     header('Content-Type: text/csv; charset=utf-8'); 
     header('Content-Disposition: attachment; filename=dosen_kelas_'.$nama_jur.'_'.$semester.'.csv'); 
     $output = fopen("php://output", "w");   
  }
  fputcsv($output, array('username', 'password', 'firstname','lastname','email','course1','role1','cohort1','group1'),";"); 
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

  $q = $db->query("select * from v_dosen_kelas
    where 1=1 $wh_fak $wh_jur $wh_sem "); 


  foreach ($q as $k) {
     $email = $k->email;
       if ($k->email=='') {
         $email = $k->username."@iainkerinci.ac.id";
       }
       $data = array($k->username,$k->password,$k->firstname,$k->lastname,$email, $semester."-".$k->id_matkul."-".$k->course1,$k->role1,$semester."-".$k->cohort1,$semester."-".$k->group1);
       fputcsv($output, $data,";"); 
  }
      fclose($output); 


?>