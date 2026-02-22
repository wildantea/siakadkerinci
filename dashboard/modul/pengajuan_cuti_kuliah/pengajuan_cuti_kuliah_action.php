<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
  
  $periode_implode = implode(",", $_POST['periode']);
  //check periode ajuan if sudah ada
  $check_exist = $db->fetch_custom_single('select nim,periode from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=? and status_acc!="rejected" and periode in('.$periode_implode.')',array('nim' => $_SESSION['username']));
  if ($check_exist) {
    action_response('Anda Sudah Mengajukan Cuti pada Periode ini');
  }
  $data = array(
      "nim" => $_SESSION["username"],
      "status_acc" => 'waiting',
      "alasan_cuti" => $_POST["alasan_cuti"],
      "date_created" => date('Y-m-d')
  );
  
  
  
   
    $in = $db->insert("tb_data_cuti_mahasiswa",$data);
    $last_id = $db->last_insert_id();
    foreach ($_POST['periode'] as $periode) {
          $data_periode = array(
            'id_cuti' => $last_id,
            'periode' => $periode
          );
          $db->insert('tb_data_cuti_mahasiswa_periode',$data_periode);
    }

    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_data_cuti_mahasiswa","id_cuti",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_cuti_mahasiswa","id_cuti",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
$periode_implode = implode(",", $_POST['periode']);
  //check periode ajuan if sudah ada
  $check_exist = $db->fetch_custom_single('select nim,periode from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode using(id_cuti) where nim=? and tb_data_cuti_mahasiswa.id_cuti!=? and status_acc!="rejected" and periode in('.$periode_implode.')',array('nim' => $_SESSION['username'],'id_cuti' => $_POST['id']));
  if ($check_exist) {
    action_response('Anda Sudah Mengajukan Cuti pada Periode ini');
  }
  $data = array(
      "alasan_cuti" => $_POST["alasan_cuti"],
      "date_created" => date('Y-m-d')
  );
  
    $in = $db->update("tb_data_cuti_mahasiswa",$data,'id_cuti',$_POST['id']);
    $db->query('delete from tb_data_cuti_mahasiswa_periode where id_cuti=?',array('id_cuti' => $_POST['id']));
    foreach ($_POST['periode'] as $periode) {
          $data_periode = array(
            'id_cuti' => $_POST['id'],
            'periode' => $periode
          );
      $db->insert('tb_data_cuti_mahasiswa_periode',$data_periode);
    }

    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>