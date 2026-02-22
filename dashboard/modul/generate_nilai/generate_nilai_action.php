<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  
  case "generate_nilai":
  $sem_id = $_POST['sem_id'];
  $kode_jur = $_POST['kode_jur'];
//   $db->query("update `krs_detail` k join matkul m on m.id_matkul=k.kode_mk
// set k.genereate='1',nilai_angka='75',nilai_huruf='B', tgl_generate=now()
// WHERE (m.nama_mk not like '%skrip%' and m.nama_mk not like '%ppl%'
//  and m.nama_mk not like '%kuliah%' and m.nama_mk not like '%kkn%' and m.nama_mk not like '%kuker%'
// and m.nama_mk not like '%kompre%' and m.nama_mk not like '%tesis%' ) 
// and k.id_semester = '$sem_id' and k.nilai_angka is null
// ");
  // echo "select id_krs_detail from `krs_detail` k join matkul m on m.id_matkul=k.kode_mk
  //         WHERE (m.nama_mk not like '%skrip%' and m.nama_mk not like '%ppl%'
  //          and m.nama_mk not like '%kuliah%' and m.nama_mk not like '%kkn%' and m.nama_mk not like '%kuker%'
  //         and m.nama_mk not like '%kompre%' and m.nama_mk not like '%tesis%' ) 
  //         and k.id_semester = '$sem_id' and k.nilai_angka is null";
  $q = $db->query("select id_krs_detail from `krs_detail` k join matkul m on m.id_matkul=k.kode_mk
          WHERE (m.nama_mk not like '%skrip%' and m.nama_mk not like '%ppl%'
           and m.nama_mk not like '%kuliah%' and m.nama_mk not like '%kkn%' and m.nama_mk not like '%kuker%'
          and m.nama_mk not like '%kompre%' and m.nama_mk not like '%tesis%' ) 
          and k.id_semester = '$sem_id' and k.nilai_angka is null "); 
  $jml=0;
  foreach ($q as $k) {
    // echo "update krs_detail set generate='1',nilai_angka='75',nilai_huruf='B',pengubah_nilai='ADMIN SISTEM', 
    //   tgl_generate='".date("Y-m-d H:i:s")."',tgl_perubahan_nilai='".date("Y-m-d H:i:s")."' where id_krs_detail='$k->id_krs_detail' ";
    $db->query("update krs_detail set generate='1', bobot='3.00', nilai_angka='75',nilai_huruf='B',pengubah_nilai='ADMIN SISTEM', 
      tgl_generate='".date("Y-m-d H:i:s")."',tgl_perubahan_nilai='".date("Y-m-d H:i:s")."' where id_krs_detail='$k->id_krs_detail' ");
    $jml++;
  }
  echo "<div class='alert alert-success'>Total Generate $jml mahasiswa</div>";
    break;

  case "in":
    
  
  
  
  $data = array(
      "sem_id" => $_POST["sem_id"],
      "kode_jur" => $_POST["kode_jur"],
      "total" => $_POST["total"],
  );
  
  
  
   
    $in = $db->insert("generate_nilai",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("generate_nilai","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("generate_nilai","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "sem_id" => $_POST["sem_id"],
      "kode_jur" => $_POST["kode_jur"],
      "total" => $_POST["total"],
   );
   
   
   

    
    
    $up = $db->update("generate_nilai",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>