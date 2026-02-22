<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
    case 'aktif':
    $db->query('update semester_ref set aktif="0"');
    $db->query("update semester_ref set aktif='1' where id_semester='".$_POST['id']."'");

    $db->query('update semester set is_aktif="0"');
    $db->query("update semester set is_aktif='1' where id_semester='".$_POST['id']."'");
    break;

  case "in":
  //check if tahun akademik is exist
  $check_tahun = $db->check_exist("semester_ref",array('tahun' => $_POST['tahun'],'id_jns_semester'=>$_POST['id_jns_semester']));
  $array_semester = array(1 => 'Ganjil',2 => 'Genap',3 => 'Perbaikan');
  if ($check_tahun==true) {
    action_response("Maaf Periode Semester Tahun ".$_POST['tahun']." Pada Semester ".$array_semester[$_POST['id_jns_semester']]." Sudah Ada");
  }
  

  $id_semester = $_POST["tahun"].$_POST["id_jns_semester"];

        if(isset($_POST["is_aktif"])=="on")
    {
      $is_aktif = array("is_aktif"=>"1");
      $db->query("update semester set is_aktif='0' where is_aktif='1'");
      $db->query("update semester_ref set aktif='0' where aktif='1'");
      $is_aktif = 1;
    } else {
      $is_aktif = 0;
    }

    $data = array(
      "id_semester" => $id_semester,
      "id_jns_semester" => $_POST["id_jns_semester"],
      "semester" => $id_semester,
      "tahun" => $_POST["tahun"],
      "aktif" => $is_aktif,
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
         "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"],
             "konsul_awal_mulai" => $_POST["konsul_awal_mulai"],
    "konsul_awal_selesai" => $_POST["konsul_awal_selesai"],

    "konsul_tengah_mulai" => $_POST["konsul_tengah_mulai"],
    "konsul_tengah_selesai" => $_POST["konsul_tengah_selesai"],

    "konsul_akhir_mulai" => $_POST["konsul_akhir_mulai"],
    "konsul_akhir_selesai" => $_POST["konsul_akhir_selesai"]
    );

    //input semester_ref
    $in = $db->insert("semester_ref",$data);

    //let's loop jurusan and bulk insert all semester for all prodi
    foreach ($db->fetch_all('jurusan') as $jur) {
      $data_in = array(
        "kode_jur" => $jur->kode_jur,
        "id_semester" => $id_semester,
        "is_aktif" => $is_aktif,
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
         "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"],
        "tgl_selesai_input_jadwal" => $_POST["tgl_selesai_input_jadwal"]

      );
      $data_in = array_filter($data_in);
      $db->insert('semester',$data_in);
    }

    action_response($db->getErrorMessage());

    break;
  case "delete":
    
   
    
    $db->delete("semester_ref","id_semester",$_GET["id"]);
    $db->delete("semester","id_semester",$_GET["id"]);
    break;
/* 
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("semester_ref","id_semester",$id);
         }
    }*/
    break;
  case "up":
      $id_semester = $_POST["id_semester"];
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
         "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"],
             "konsul_awal_mulai" => $_POST["konsul_awal_mulai"],
    "konsul_awal_selesai" => $_POST["konsul_awal_selesai"],

    "konsul_tengah_mulai" => $_POST["konsul_tengah_mulai"],
    "konsul_tengah_selesai" => $_POST["konsul_tengah_selesai"],

    "konsul_akhir_mulai" => $_POST["konsul_akhir_mulai"],
    "konsul_akhir_selesai" => $_POST["konsul_akhir_selesai"]
        
      );

  //let's loop jurusan and bulk insert all semester for all prodi
    foreach ($db->query('select * from semester where id_semester=?',array('id_semester'=>$id_semester)) as $sem) {
      $data_up = array(
        "tgl_mulai" => $_POST["tgl_mulai"],
        "tgl_selesai" => $_POST["tgl_selesai"],
        "tgl_mulai_krs" => $_POST["tgl_mulai_krs"],
        "tgl_selesai_krs" => $_POST["tgl_selesai_krs"],
        "tgl_mulai_pkrs" => $_POST["tgl_mulai_pkrs"],
        "tgl_selesai_pkrs" => $_POST["tgl_selesai_pkrs"],
        "tgl_mulai_input_kelas" => $_POST["tgl_mulai_input_kelas"],
        "tgl_selesai_input_kelas" => $_POST["tgl_selesai_input_kelas"],
        "tgl_mulai_input_jadwal" => $_POST["tgl_mulai_input_jadwal"],
         "tgl_mulai_perkuliahan" => $_POST["tgl_mulai_perkuliahan"],
        "tgl_selesai_input_jadwal" => $_POST["tgl_selesai_input_jadwal"]
      );
      $data_up = array_filter($data_up);
      $db->update('semester',$data_up,'sem_id',$sem->sem_id);
    }
    $db->update('semester_ref',$data,'id_semester',$id_semester);
    echo $db->getErrorMessage();
    action_response($db->getErrorMessage());

    break;
  default:
    # code...
    break;
}

?>