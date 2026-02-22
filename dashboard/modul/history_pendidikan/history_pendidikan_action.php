<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
  case 'import':
     if (!is_dir("../../../upload/upload_excel")) {
              mkdir("../../../upload/upload_excel");
            }


   if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

              echo "pastikan file yang anda pilih xls|xlsx";
              exit();

            } else {
              move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/".$_FILES['semester']['name']);
              $semester = array("semester"=>$_FILES["semester"]["name"]);

            }




  $error_count = 0;
  $error = array();
  $sukses = 0;
  $values = "";
  $value_add = array();
    $array_jur_lokal = array();
  $array_jur_query = $db->query("select kode_dikti, kode_jur from jurusan");
  foreach ($array_jur_query as $jur_lokal) {
    $array_jur_lokal[$jur_lokal->kode_dikti] = $jur_lokal->kode_jur;
  }

//echo "<pre>";
  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);
    //update history
    foreach ($Reader as $key => $val)
  {
    if ($key>0) {
      if ($val[0]!='') {
            $jenis_pendaftaran = filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
            //first check nim on main table mahasiswa
            $check_nim = $db->check_exist('mahasiswa',array(
                'nim'=>filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)
                ));

            if ($check_nim==true) {
              $sukses++;
               //then check whether jenis daftar 1 or else
              $jenis_daftar = filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $kode_jurusan = preg_replace( '/[^[:print:]]/', '',filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
              $kode_jurusan = trim($kode_jurusan);
              $kode_jurusan = $array_jur_lokal[$kode_jurusan];
              $nipd = filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $mulai_smt = filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $jenis_daftar = filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $jalur_daftar = filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $tgl_masuk_sp = filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $sks_diakui = filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $kode_pt_asal = filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $kode_prodi_asal = filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
              $data_mhs = $db->check_exist_data('mahasiswa',array('nim' => $nipd));
                      //create update array
                        $updates_mulai_smt[] = "WHEN '$data_mhs->mhs_id' THEN '$mulai_smt' ";
                        $updates_jenis_daftar[]= "WHEN '$data_mhs->mhs_id' THEN '$jenis_daftar' ";
                        $updates_jalur_daftar[]= "WHEN '$data_mhs->mhs_id' THEN '$jalur_daftar' ";
                        $updates_tgl_masuk_sp[]= "WHEN '$data_mhs->mhs_id' THEN '$tgl_masuk_sp' ";
                        $updates_sks_diakui[]= "WHEN '$data_mhs->mhs_id' THEN '$sks_diakui' ";
                        $updates_kode_pt_asal[]= "WHEN '$data_mhs->mhs_id' THEN '$kode_pt_asal' ";
                        $updates_kode_prodi_asal[]= "WHEN '$data_mhs->mhs_id' THEN '$kode_prodi_asal' ";
                        $updates_kode_jurusan[]= "WHEN '$data_mhs->mhs_id' THEN '$kode_jurusan' ";
                        $nipds[] = $nipd;

              
            } else {
              $error_count++;
              $error[] = 'Nim ini '.$val[0]." tidak Ditemukan di Sistem";
            }
      }

    }

  }

if (!empty($updates_mulai_smt)) {

   $update_mulai_smt = implode(" ", $updates_mulai_smt);
   $update_jenis_daftar = implode(" ", $updates_jenis_daftar);
   $update_jalur_daftar = implode(" ", $updates_jalur_daftar);
   $update_tgl_masuk_sp = implode(" ", $updates_tgl_masuk_sp);
   $update_sks_diakui = implode(" ", $updates_sks_diakui);
   $update_kode_pt_asal = implode(" ", $updates_kode_pt_asal);
   $update_kode_prodi_asal = implode(" ", $updates_kode_prodi_asal);
   $update_kode_jurusan = implode(" ", $updates_kode_jurusan);
   $nipd = implode(",", $nipds);

$bulk_update = "UPDATE mahasiswa 
    SET mulai_smt = (CASE mhs_id 
      $update_mulai_smt
      END),id_jns_daftar = (CASE mhs_id 
      $update_jenis_daftar
      END),id_jalur_masuk = (CASE mhs_id 
      $update_jalur_daftar
      END),tgl_masuk_sp = (CASE mhs_id 
      $update_tgl_masuk_sp
      END),sks_diakui = (CASE mhs_id 
      $update_sks_diakui
      END),kode_pt_asal= (CASE mhs_id 
      $update_kode_pt_asal
      END),kode_prodi_asal = (CASE mhs_id 
      $update_kode_prodi_asal
      END),kode_jurusan = (CASE mhs_id 
      $update_kode_jurusan
      END)
    WHERE mhs_id IN($nipd);";
    echo $bulk_update;
    $db->query($bulk_update);
  echo $db->getErrorMessage();

}

    unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
    $msg = '';
if (($sukses>0) || ($error_count>0)) {
  $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" >
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
      <font color=\"#3c763d\">".$sukses." data History Pendidikan baru berhasil di import</font><br />
      <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
      if (!$error_count==0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
      }
      //echo "<br />Total: ".$i." baris data";
      $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
          $i=1;
          foreach ($error as $pesan) {
              $msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div><br />";
            $i++;
            }
      $msg .= "</div>
    </div>";
     $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
}
  echo $msg;
    break;
  case "in":
    
  
  
  
  $data = array(
      "mulai_smt" => $_POST["mulai_smt"],
      "nipd" => $_POST["nipd"],
  );
  
  
  
   
    $in = $db->insert("history_pendidikan",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("history_pendidikan","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("history_pendidikan","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "mulai_smt" => $_POST["mulai_smt"],
      "nipd" => $_POST["nipd"],
   );
   
   
   

    
    
    $up = $db->update("history_pendidikan",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>