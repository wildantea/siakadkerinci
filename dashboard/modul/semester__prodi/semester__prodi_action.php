<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
    $check_sem_prodi = $db->check_exist("semester",array('kode_jur' => $_POST['kode_jur'],'id_semester'=>$_POST['id_semester']));
  if ($check_sem_prodi==true) {
    action_response("Maaf Periode Semester di Program Studi ini Sudah Ada");
  }

  $data = array(
      "kode_jur" => $_POST["kode_jur"],
      "id_semester" => $_POST["id_semester"],
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
      "tgl_mulai_krs" => $_POST["tgl_mulai_krs"],
      "tgl_selesai_krs" => $_POST["tgl_selesai_krs"],
      "tgl_mulai_pkrs" => $_POST["tgl_mulai_pkrs"],
      "tgl_selesai_pkrs" => $_POST["tgl_selesai_pkrs"],
      "tgl_mulai_input_nilai" => $_POST["tgl_mulai_input_nilai"],
      "tgl_selesai_input_nilai" => $_POST["tgl_selesai_input_nilai"],
         "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"]

  );
  
    $in = $db->insert("semester",$data);
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("semester","sem_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("semester","sem_id",$id);
         }
    }
    break;
  case "up":
    
   $data = array(
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
      "tgl_mulai_krs" => $_POST["tgl_mulai_krs"],
      "tgl_selesai_krs" => $_POST["tgl_selesai_krs"],
      "tgl_mulai_pkrs" => $_POST["tgl_mulai_pkrs"],
      "tgl_selesai_pkrs" => $_POST["tgl_selesai_pkrs"],
      "tgl_mulai_input_nilai" => $_POST["tgl_mulai_input_nilai"],
      "tgl_selesai_input_nilai" => $_POST["tgl_selesai_input_nilai"],
      "tgl_mulai_input_kelas" => $_POST["tgl_mulai_input_kelas"],
        "tgl_selesai_input_kelas" => $_POST["tgl_selesai_input_kelas"],
        "tgl_mulai_input_jadwal" => $_POST["tgl_mulai_input_jadwal"],
        "tgl_selesai_input_jadwal" => $_POST["tgl_selesai_input_jadwal"],
           "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"]
   );
   
    $up = $db->update("semester",$data,"sem_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>