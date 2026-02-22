<?php
session_start();
include "../../inc/config.php";
session_check();
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
    //action
 case 'import':
 error_reporting(0);
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
      $values_error = "";
      $id_error = rand();

  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);


  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
                $nim = filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
        $nim = str_replace(" ", "", $nim);
        $nim = preg_replace( '/[^[:print:]]/', '',$nim);
        $nim = trim($nim);
          $check = $db->check_exist('mahasiswa',array('nim' => $nim,'nama'=>filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)));
          $check_nim_only = $db->check_exist('mahasiswa',array('nim' =>$nim));
          if ($check==true or $check_nim_only==true) {
            $error_count++;
            $error[] = "Nim ".$val[0]." Sudah Ada";
          $values_error .= '("'.$id_error.'","'.
      preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'"),';
          } else {
            $sukses++;
            $kode_jurusan = preg_replace( '/[^[:print:]]/', '',filter_var($val[46], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
      $values .= '("'.$nim.'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[9], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[10], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[11], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[12], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[13], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[14], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[15], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[16], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[17], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[18], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[19], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[20], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[21], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[22], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[23], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[24], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[25], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[26], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[27], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[28], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[29], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[30], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[31], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[32], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[33], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[34], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[35], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[36], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[37], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[38], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[39], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[40], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[41], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[42], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[43], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[44], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[45], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
$kode_jurusan.'","'.
filter_var($val[47], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
filter_var($val[48], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'"),';
        }

      }

    }

      }

if ($values!="") {
  $values = rtrim($values,",");
  $query = "insert into mahasiswa (nim,no_pendaftaran, nama,tmpt_lahir,tgl_lahir,jk,nik,id_agama,nisn,id_jalur_masuk,npwp,kewarganegaraan,id_jns_daftar,tgl_masuk_sp,mulai_smt,jln,rt,rw,nm_dsn,ds_kel,id_wil,kode_pos,id_jns_tinggal,id_alat_transport,no_tel_rmh,no_hp,email,a_terima_kps,no_kps,nik_ayah,nm_ayah,tgl_lahir_ayah,id_jenjang_pendidikan_ayah,id_pekerjaan_ayah,id_penghasilan_ayah,nik_ibu_kandung,nm_ibu_kandung,tgl_lahir_ibu,id_jenjang_pendidikan_ibu,id_pekerjaan_ibu,id_penghasilan_ibu,nm_wali,tgl_lahir_wali,id_jenjang_pendidikan_wali,id_pekerjaan_wali,id_penghasilan_wali,jur_kode,id_pembiayaan,dosen_pemb) values ".$values;
 // echo $query;
  $db->query($query);
  echo $db->getErrorMessage();
}
if ($values_error!="") {
  $values_error = rtrim($values_error,",");
  $query_error = "insert into temp_error_mhs(id_import,nim) values ".$values_error;
  $db->query($query_error);
  echo $db->getErrorMessage();
}

          unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Mahasiswa baru berhasil di import</font><br />
            <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
            if (!$error_count==0) {
              $msg .= "<a href='".base_admin()."modul/mahasiswa/error_import.php?id=$id_error' target='_blank'>Detail error</a>";
            }
            $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
           $msg .= "</div>";

      }
     echo $msg;
    break;
case 'import_pa':
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
      $values_error = array();
      $id_error = rand();

  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);
 

  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {


      if ($val[0]!='') {

        $nim = trimmer($val[0]);
        $nip = trimmer($val[2]);
          $check = $db->check_exist('mahasiswa',array('nim' => $nim));
         // var_dump($check); 
          if ($check==false) {
            $error_count++;
            $error[] = "Nim ".$val[0]." Tidak ditemukan";
            $values_error[] = array('id_import' => $id_error,'nim' => $nim,'nip'=> $nip,'error_data' => 'Nim tidak ditemukan');
          } else {
            $check_nip = $db->check_exist('dosen',array('nip' => $nip));
            //var_dump($check_nip); 
            if ($check_nip==false) {
              $error_count++;
              $error[] = "NIP ".$nip." dosen ".$val[3]." Tidak ditemukan";
              $values_error[] = array('id_import' => $id_error,'nim' => $nim,'nip'=> $nip,'error_data' => 'NIP Dosen tidak ditemukan');
            } else {
              $sukses++;
              $data_update []= array('dosen_pemb' => trimmer($val[2]));
              $key_value[] =  $nim;
            }

        }

      }

    }

      }

if (!empty($data_update)) {
 //  print_r($data_update);
  $db->updateMulti('mahasiswa',$data_update,'nim',$key_value);
  echo $db->getErrorMessage();
}
if (!empty($values_error)) {
  $db->insertMulti('temp_error_pa',$values_error);
  echo $db->getErrorMessage();
}

          unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Dosen Pembimbing baru berhasil di import</font><br />
            <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
            if (!$error_count==0) {
              $msg .= "<a href='".base_admin()."modul/mahasiswa/error_import_pa.php?id=$id_error' target='_blank'>Detail error</a>";
            }
            $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
           $msg .= "</div>";

      }
     echo $msg;
  break;
  case "in":
    
  $data = array('username'=>$_POST['nim']);
    $check = $db->check_exist('sys_users',$data);
    if ($check==true) {
      echo "false";
      exit();
    }




  $data = array("first_name"=>$_POST["nama"],
                "last_name" => "",
                "username"=>$_POST["nim"],
                "password"=>md5($_POST["password_baru"]),
                "email"=>$_POST["email"],
                "group_level"=>'3',
                "date_created"=>date('Y-m-d'),
                "aktif" => "Y"
                );



  if(isset($_FILES["foto_user"]["name"])) {
                      if (!preg_match("/.(png|jpg|jpeg|gif|bmp)$/i", $_FILES["foto_user"]["name"]) ) {

                echo "pastikan file yang anda pilih png|jpg|jpeg|gif";

                exit();

              } else {

  $db->compressImage($_FILES["foto_user"]["type"],$_FILES["foto_user"]["tmp_name"],"../../../upload/back_profil_foto/",$_FILES["foto_user"]["name"],200,200);
  $foto_user = array("foto_user"=>$_FILES["foto_user"]["name"]);
                $data = array_merge($data,$foto_user);

              }
}

    $in = $db->insert("sys_users",$data);
  
  unset($data);
  $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "jur_kode" => $_POST["jur_kode"],
      "id_jns_daftar" => $_POST["id_jns_daftar"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
      "id_agama" => $_POST["id_agama"],
      "mulai_smt" => $_POST["mulai_smt"],
      "tgl_masuk_sp" => $_POST["tgl_masuk_sp"],
      "jk" => $_POST["jk"],
      "nisn" => $_POST["nisn"],
      "nik" => $_POST["nik"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
      "jln" => $_POST["jln"],
      "rt" => $_POST["rt"],
      "rw" => $_POST["rw"],
      "nm_dsn" => $_POST["nm_dsn"],
      "ds_kel" => $_POST["ds_kel"],
      "kode_pos" => $_POST["kode_pos"],
      "no_tel_rmh" => $_POST["telepon_rumah"],
      "no_hp" => $_POST["telepon_seluler"],
      "email" => $_POST["email"],
      "no_kps" => $_POST["no_kps"],
       "id_wil" => $_POST["id_wil"],
      "stat_pd" => $_POST["stat_pd"],
      "nm_ayah" => $_POST["nm_ayah"],
      "nik_ayah" => $_POST["nik_ayah"],
      "tgl_lahir_ayah" => $_POST["tgl_lahir_ayah"],
      "id_jenjang_pendidikan_ayah" => $_POST["id_jenjang_pendidikan_ayah"],
      "id_pekerjaan_ayah" => $_POST["id_pekerjaan_ayah"],
      "id_penghasilan_ayah" => $_POST["id_penghasilan_ayah"],
      "nm_ibu_kandung" => $_POST["nm_ibu_kandung"],
      "nik_ibu_kandung" => $_POST["nik_ibu_kandung"],
      "tgl_lahir_ibu" => $_POST["tgl_lahir_ibu"],
      "id_jenjang_pendidikan_ibu" => $_POST["id_jenjang_pendidikan_ibu"],
      "id_pekerjaan_ibu" => $_POST["id_pekerjaan_ibu"],
      "id_penghasilan_ibu" => $_POST["id_penghasilan_ibu"],
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $_POST["tgl_lahir_wali"],
      "nik_wali" => $_POST["nik_wali"],
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
      "id_jns_tinggal" => $_POST["id_jns_tinggal"],
      "kur_id" => $_POST["kur_id"],
      "status" => $_POST["status"],
      "dosen_pemb" => $_POST["dosen_pemb"],
      "kewarganegaraan" => '1',
      "npwp" => $_POST["npwp"],
  );
  
     
    foreach ($data as $key => $value) {
       if ($value!='') {
        $datax[$key]=$value;
       }
    }
     if(isset($_POST["a_terima_kps"])=="on")
          {
            $a_terima_kps = array("a_terima_kps"=>"1");
            $datax=array_merge($datax,$a_terima_kps);
          } else {
            $a_terima_kps = array("a_terima_kps"=>"0");
            $datax=array_merge($datax,$a_terima_kps);
          }

    $in = $db->insert("mahasiswa",$datax);
  
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":    
    $mhs = $db->fetch_single_row("mahasiswa","mhs_id",$_GET["id"]);

    //delete users
    $db->delete("sys_users","username",$mhs->nim);
    //delete mhs
    $db->delete("mahasiswa","mhs_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    break;
  case 'up_dosen':
      $array_data = array('dosen_pemb' => $_POST['dosen_pemb']);
     $up = $db->update("mahasiswa",$array_data,"mhs_id",$_POST["id"]);
       action_response($db->getErrorMessage());
    break;
  case "up":
    $nim = $_POST['nim'];
    if ($_POST['nim']=='') {
      $nim = NULL;
    }
   $data = array(
      "nim" => $nim, 
      "nama" => $_POST["nama"],
      "jur_kode" => $_POST["jur_kode"],
      "id_jns_daftar" => $_POST["id_jns_daftar"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
      "id_agama" => $_POST["id_agama"],
      "mulai_smt" => $_POST["mulai_smt"],
      "tgl_masuk_sp" => $_POST["tgl_masuk_sp"],
      "jk" => $_POST["jk"],
      "nisn" => $_POST["nisn"],
      "nik" => $_POST["nik"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
      "jln" => $_POST["jln"],
      "rt" => $_POST["rt"],
      "rw" => $_POST["rw"],
      "nm_dsn" => $_POST["nm_dsn"],
      "ds_kel" => $_POST["ds_kel"],
      "kode_pos" => $_POST["kode_pos"],
      "no_tel_rmh" => $_POST["telepon_rumah"],
      "no_hp" => $_POST["telepon_seluler"],
      "email" => $_POST["email"],
      "no_kps" => $_POST["no_kps"],
      "id_wil" => $_POST["id_wil"],
      "stat_pd" => $_POST["stat_pd"],
      "nm_ayah" => $_POST["nm_ayah"],
      "nik_ayah" => $_POST["nik_ayah"],
      "tgl_lahir_ayah" => $_POST["tgl_lahir_ayah"],
      "id_jenjang_pendidikan_ayah" => $_POST["id_jenjang_pendidikan_ayah"],
      "id_pekerjaan_ayah" => $_POST["id_pekerjaan_ayah"],
      "id_penghasilan_ayah" => $_POST["id_penghasilan_ayah"],
      "nm_ibu_kandung" => $_POST["nm_ibu_kandung"],
      "nik_ibu_kandung" => $_POST["nik_ibu_kandung"],
      "tgl_lahir_ibu" => $_POST["tgl_lahir_ibu"],
      "id_jenjang_pendidikan_ibu" => $_POST["id_jenjang_pendidikan_ibu"],
      "id_pekerjaan_ibu" => $_POST["id_pekerjaan_ibu"],
      "id_penghasilan_ibu" => $_POST["id_penghasilan_ibu"],
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $_POST["tgl_lahir_wali"],
      "nik_wali" => $_POST["nik_wali"],
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
      "id_jns_tinggal" => $_POST["id_jns_tinggal"],
      "kur_id" => $_POST["kur_id"],
      "status" => $_POST["status"],
    //  "dosen_pemb" => $_POST["dosen_pemb"],
      "kewarganegaraan" => $_POST["kewarganegaraan"],
      "npwp" => $_POST["npwp"],
   );
   
    foreach ($data as $key => $value) {
       if ($value!='') {
        $datax[$key]=$value;
       }
    }
     if(isset($_POST["a_terima_kps"])=="on")
          {
            $a_terima_kps = array("a_terima_kps"=>"1");
            $datax=array_merge($datax,$a_terima_kps);
          } else {
            $a_terima_kps = array("a_terima_kps"=>"0");
            $datax=array_merge($datax,$a_terima_kps);
          }
    $up = $db->update("mahasiswa",$datax,"mhs_id",$_POST["id"]);
    
    if ($up=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  default:
    # code...
    break;
}

?>