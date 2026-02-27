<?php
session_start();
include "../../inc/config.php";
session_check_json();
$array_hari = array('senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'jum`at' => 5, 'sabtu' => 6, 'minggu' => 7);

$time_start = microtime(true);
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
  case 'import_kelas':
    if (!is_dir("../../../upload/upload_excel")) {
      mkdir("../../../upload/upload_excel");
    }


    if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"])) {

      echo "pastikan file yang anda pilih xls|xlsx";
      exit();

    } else {
      move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/" . $_FILES['semester']['name']);
      $semester = array("semester" => $_FILES["semester"]["name"]);

    }

    $error_count = 0;
    $error = array();
    $sukses = 0;
    $values = "";
    $values2 = "";

    $Reader = new SpreadsheetReader("../../../upload/upload_excel/" . $_FILES['semester']['name']);

    $get_label_kelas = array();
    $get_label_kelas = get_label_kelas();
    foreach ($Reader as $key => $val) {


      if ($key > 0) {

        if ($val[0] != '') {
          //first check kode_mk
          $kode_mk = filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $kode_jur = filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $nama_kelas = filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $sem_id = preg_replace('/[^[:print:]]/', '', filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
          $sem_kur = preg_replace('/[^[:print:]]/', '', filter_var($val[9], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));

          $check_kode_mk = $db->query("select matkul.kode_mk from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=?", array('kode_mk' => $kode_mk, 'kode_jur' => $kode_jur));
          if ($check_kode_mk->rowCount() > 0) {
            $id_matkul = $db->fetch_custom_single("select id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=? and kurikulum.sem_id=?", array(
              'kode_mk' => $kode_mk,
              'kode_jur' => $kode_jur,
              'sem_id' => $sem_kur
            ));
            //check kelas if not exist
            $check_kelas_exist = $db->check_exist('kelas', array(
              'sem_id' => $sem_id,
              'kls_nama' => $nama_kelas,
              'id_matkul' => $id_matkul->id_matkul
            ));
            if ($check_kelas_exist == false) {
              $sukses++;
              $values .= '("' .
                $sem_id . '","' .
                $id_matkul->id_matkul . '","' .
                $nama_kelas . '","' .
                preg_replace('/[^[:print:]]/', '', filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '","' .
                preg_replace('/[^[:print:]]/', '', filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '","' .
                preg_replace('/[^[:print:]]/', '', filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '","' .
                preg_replace('/[^[:print:]]/', '', filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '"),';
            } else {
              $error_count++;
              $error[] = "Kelas " . $kode_kelas . " Mk " . $kode_mk . " Sudah Ada";
            }

          } else {
            $error_count++;
            $error[] = "Kode Mk " . $kode_mk . " Tidak ditemukan";
          }
        }

      }

    }

    if ($values != "") {
      $values = rtrim($values, ",");

      $query = "insert into kelas (sem_id,id_matkul,kls_nama,peserta_max,peserta_min,catatan,id_jenis_kelas) values " . $values;
      //echo $query;
      $db->query($query);
      echo $db->getErrorMessage();
    }

    if ($values2 != "") {
      //$values = rtrim($values,",");
      $values2 = rtrim($values2, ",");

      // $query = "insert into kelas (sem_id,id_matkul,kls_nama,peserta_max,peserta_min,catatan,id_jenis_kelas) values ".$values;
      $query2 = "insert into matkul (kur_id,id_jenjang,kode_mk,id_tipe_matkul,semester,nama_mk,sks_tm,total_sks,a_wajib) values " . $values2;
      //echo "$query2";   
      //echo $query; 
      //$db->query($query); 
      $db->query($query2);
      echo $db->getErrorMessage();
    }

    unlink("../../../upload/upload_excel/" . $_FILES['semester']['name']);
    $msg = '';
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);

    if (($sukses > 0) || ($error_count > 0)) {
      $msg = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">" . $sukses . " data Kelas baru berhasil di import</font><br />
            <font color=\"#ce4844\" >" . $error_count . " data tidak bisa ditambahkan </font>";
      if (!$error_count == 0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
      }
      //echo "<br />Total: ".$i." baris data";
      $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
      $i = 1;
      foreach ($error as $pesan) {
        $msg .= "<div class=\"bs-callout bs-callout-danger\">" . $i . ". " . $pesan . "</div><br />";
        $i++;
      }
      $msg .= "</div>";
      $msg .= "<p>Total Waktu Import : " . waktu_import($execution_time);
      $msg .= "</div>";

    }
    echo $msg;
    break;
  //import jadwal
  case 'import_jadwal':
    if (!is_dir("../../../upload/upload_excel")) {
      mkdir("../../../upload/upload_excel");
    }


    if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"])) {

      echo "pastikan file yang anda pilih xls|xlsx";
      exit();

    } else {
      move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/" . $_FILES['semester']['name']);
      $semester = array("semester" => $_FILES["semester"]["name"]);

    }

    $error_count = 0;
    $error = array();
    $sukses = 0;
    $values = "";

    $Reader = new SpreadsheetReader("../../../upload/upload_excel/" . $_FILES['semester']['name']);

    foreach ($Reader as $key => $val) {


      if ($key > 0) {

        if ($val[0] != '') {
          //first check kode_mk
          $sem_id = preg_replace('/[^[:print:]]/', '', filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
          $kode_mk = filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $nama_kelas = filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $kode_ruang = filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $hari = filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $jam_mulai = filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $jam_selesai = filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $kode_jur = filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $sem_kur = filter_var($val[9], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

          $check_kode_mk = $db->query("select matkul.kode_mk from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=?", array('kode_mk' => $kode_mk, 'kode_jur' => $kode_jur));
          if ($check_kode_mk->rowCount() > 0) {
            $id_matkul = $db->fetch_custom_single("select id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=? and kurikulum.sem_id=?", array(
              'kode_mk' => $kode_mk,
              'kode_jur' => $kode_jur,
              'sem_id' => $sem_kur
            ));
            //check kelas if not exist
            $check_kelas_exist = $db->check_exist('kelas', array(
              'sem_id' => $sem_id,
              'kls_nama' => $nama_kelas,
              'id_matkul' => $id_matkul->id_matkul
            ));
            if ($check_kelas_exist == true) {
              $get_id_kelas = $db->check_exist_data('kelas', array(
                'sem_id' => $sem_id,
                'kls_nama' => $nama_kelas,
                'id_matkul' => $id_matkul->id_matkul
              ));

              $get_ruang_id = $db->check_exist('ruang_ref', array(
                'kode_ruang' => $kode_ruang
              ));
              if ($get_ruang_id == true) {
                $get_id_ruang = $db->check_exist_data('ruang_ref', array(
                  'kode_ruang' => $kode_ruang
                ));
                //check if jadwal exist
                /*    $cek_jadwal_exist = $db->check_exist("jadwal_kuliah",array(
                      'kelas_id' => $get_id_kelas->kelas_id,
                      'hari' => $hari,
                      'ruang_id' => $get_id_ruang->ruang_id,
                      'jam_mulai' => $jam_mulai,
                      'jam_selesai' => $jam_selesai
                    ));*/
                $cek_jadwal_exist = $db->check_exist("jadwal_kuliah", array(
                  'kelas_id' => $get_id_kelas->kelas_id
                ));
                if ($cek_jadwal_exist == false) {
                  $id_haris = NULL;
                  if (in_array($hari, array_values($array_hari))) {
                    $id_haris = $array_hari[$hari];
                  }
                  $sukses++;
                  $values .= '("' .
                    $get_id_kelas->kelas_id . '","' .
                    $hari . '","' .
                    $id_haris . '","' .
                    $get_id_ruang->ruang_id . '","' .
                    $jam_mulai . '","' .
                    $jam_selesai . '"),';
                } else {
                  $sukses++;

                  $data_update_jadwal = array(
                    'hari' => $hari,
                    'ruang_id' => $get_id_ruang->ruang_id,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai
                  );
                  if (in_array($hari, array_values($array_hari))) {
                    $data_update_jadwal['id_hari'] = $array_hari[$hari];
                  }
                  $db->update("jadwal_kuliah", $data_update_jadwal, "kelas_id", $get_id_kelas->kelas_id);
                  //$error_count++;
                  //$error[] = "Kelas ".$nama_kelas." MK $kode_mk di $sem_id sudah ada Jadwalnya";
                }

              } else {
                $error_count++;
                $error[] = "Kode Ruang " . $kode_ruang . " Tidak ditemukan di Sistem";
              }

            } else {
              $error_count++;
              $error[] = "Kelas " . $nama_kelas . " Mk " . $kode_mk . " Semester " . $sem_id . " Tidak ditemukan";
            }

          } else {
            $error_count++;
            $error[] = "Kode Mk " . $kode_mk . " Tidak ditemukan";
          }
        }

      }

    }

    if ($values != "") {
      $values = rtrim($values, ",");

      $query = "insert into jadwal_kuliah (kelas_id,hari,id_hari,ruang_id,jam_mulai,jam_selesai) values " . $values;
      //echo $query;
      $db->query($query);
      echo $db->getErrorMessage();
    }

    unlink("../../../upload/upload_excel/" . $_FILES['semester']['name']);
    $msg = '';
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);

    if (($sukses > 0) || ($error_count > 0)) {
      $msg = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">" . $sukses . " data Jadwal baru berhasil di import</font><br />
            <font color=\"#ce4844\" >" . $error_count . " data tidak bisa ditambahkan </font>";
      if (!$error_count == 0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
      }
      //echo "<br />Total: ".$i." baris data";
      $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
      $i = 1;
      foreach ($error as $pesan) {
        $msg .= "<div class=\"bs-callout bs-callout-danger\">" . $i . ". " . $pesan . "</div><br />";
        $i++;
      }
      $msg .= "</div>";
      $msg .= "<p>Total Waktu Import : " . waktu_import($execution_time);
      $msg .= "</div>";

    }
    echo $msg;
    break;
  case 'import_dosen':
    if (!is_dir("../../../upload/upload_excel")) {
      mkdir("../../../upload/upload_excel");
    }


    if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"])) {

      echo "pastikan file yang anda pilih xls|xlsx";
      exit();

    } else {
      move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/upload_excel/" . $_FILES['semester']['name']);
      $semester = array("semester" => $_FILES["semester"]["name"]);

    }

    $error_count = 0;
    $error = array();
    $sukses = 0;
    $values = "";
    $values2 = "";
    $values_second = "";

    $Reader = new SpreadsheetReader("../../../upload/upload_excel/" . $_FILES['semester']['name']);
    $get_label_kelas = array();
    $get_label_kelas = get_label_kelas();
    foreach ($Reader as $key => $val) {


      if ($key > 0) {

        if ($val[0] != '') {
          //first check kode_mk
          $sem_id = preg_replace('/[^[:print:]]/', '', filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
          $nidn = filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $nama_dosen = filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $kode_mk = preg_replace('/[^[:print:]]/', '', filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
          $nama_kelas = filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $kode_jur = filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          $tatap_real = filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
          if ($tatap_real == "") {
            $tatap_real = 14;
          }

          $check_kode_mk = $db->query("select matkul.kode_mk from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=?", array('kode_mk' => $kode_mk, 'kode_jur' => $kode_jur));
          if ($check_kode_mk->rowCount() > 0) {
            $id_matkul = $db->fetch_custom_single("select id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id
where kode_mk=? and kurikulum.kode_jur=?", array(
              'kode_mk' => $kode_mk,
              'kode_jur' => $kode_jur
            ));
            //check kelas if not exist
            $array_kelas = array(
              'sem_id' => $sem_id,
              'kls_nama' => $nama_kelas,
              'id_matkul' => $id_matkul->id_matkul
            );
            $check_kelas_exist = $db->check_exist('kelas', $array_kelas);
            /*  echo $db->getErrorMessage();
              var_dump($check_kelas_exist);
              print_r($array_kelas);*/
            if ($check_kelas_exist) {
              $get_id_kelas = $db->check_exist_data('kelas', array(
                'sem_id' => $sem_id,
                'kls_nama' => $nama_kelas,
                'id_matkul' => $id_matkul->id_matkul
              ));

              $check_nidn_from_dosen = $db->check_exist("dosen", array('nip' => $nidn));
              if ($check_nidn_from_dosen == false) {
                $error_count++;
                $error[] = "NIDN/NIDK/NUP " . $nidn . " " . $nama_dosen . " tidak ditemukan di Sistem";
              } else {
                $get_nip_dosen = $db->fetch_single_row("dosen", "nip", $nidn);
                $nip = $get_nip_dosen->nip;
                //check dosen kelas
                //check kelas if not exist
                $check_dosen_kelas_exist = $db->check_exist('dosen_kelas', array(
                  'id_kelas' => $get_id_kelas->kelas_id,
                  // 'id_dosen' => $nip
                ));
                if ($check_dosen_kelas_exist == false) {
                  $sukses++;
                  $values .= '("' .
                    $get_id_kelas->kelas_id . '","' .
                    $nip . '","' .
                    '1","' .
                    preg_replace('/[^[:print:]]/', '', filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '","' .
                    $tatap_real . '","' .
                    preg_replace('/[^[:print:]]/', '', filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '"),';

                } else {
                  //delete record dosen kelas if exist before update
                  $sukses++;
                  $db->query("delete from dosen_kelas where id_kelas=?", array('id_kelas' => $get_id_kelas->kelas_id));
                  $values_second .= '("' .
                    $get_id_kelas->kelas_id . '","' .
                    $nip . '","' .
                    '1","' .
                    preg_replace('/[^[:print:]]/', '', filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '","' .
                    $tatap_real . '","' .
                    preg_replace('/[^[:print:]]/', '', filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . '"),';
                }
              }

            } else {
              $error_count++;
              $error[] = "Pastikan Kelas " . $nama_kelas . " Mk " . $kode_mk . " Sudah dibuat";
            }

          } else {
            $error_count++;
            $error[] = "Kode Mk " . $kode_mk . " Tidak ditemukan";
            $qck = $db->query("select kur_id from kurikulum where sem_id='20051' and kode_jur='$kode_jur'  ");
            foreach ($qck as $kc) {
              $kur_id = $kc->kur_id;
              $values2 .= "('$kur_id','30','$kode_mk','A','1',
            '" . str_replace("`", "", filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) . "',
            '3','3','1'),";
            }
          }
        }

      }

    }

    if ($values != "") {
      $values = rtrim($values, ",");

      $query = "insert into dosen_kelas (id_kelas,id_dosen,dosen_ke,jml_tm_renc,jml_tm_real,sks_ajar) values " . $values;
      //echo $query;
      $db->query($query);
      echo $db->getErrorMessage();
    }

    // if ($values2!="") {
//   //$values = rtrim($values,",");
//   $values2 = rtrim($values2,","); 

    //  // $query = "insert into kelas (sem_id,id_matkul,kls_nama,peserta_max,peserta_min,catatan,id_jenis_kelas) values ".$values;
//   $query2 = "insert into matkul (kur_id,id_jenjang,kode_mk,id_tipe_matkul,semester,nama_mk,sks_tm,bobot_minimal_lulus,a_wajib) values ".$values2;
//   //echo "$query2";   
//   //echo $query; 
//   //$db->query($query); 
//    $db->query($query2); 
//   echo $db->getErrorMessage();
// } 

    if ($values_second != "") {
      $values_second = rtrim($values_second, ",");

      $query = "insert into dosen_kelas (id_kelas,id_dosen,dosen_ke,jml_tm_renc,jml_tm_real,sks_ajar) values " . $values_second;
      //echo $query;
      $db->query($query);
      echo $db->getErrorMessage();
    }


    unlink("../../../upload/upload_excel/" . $_FILES['semester']['name']);
    $msg = '';
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);

    if (($sukses > 0) || ($error_count > 0)) {
      $msg = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">" . $sukses . " data Dosen Kelas baru berhasil di import</font><br />
            <font color=\"#ce4844\" >" . $error_count . " data tidak bisa ditambahkan </font>";
      if (!$error_count == 0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
      }
      //echo "<br />Total: ".$i." baris data";
      $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
      $i = 1;
      foreach ($error as $pesan) {
        $msg .= "<div class=\"bs-callout bs-callout-danger\">" . $i . ". " . $pesan . "</div><br />";
        $i++;
      }
      $msg .= "</div>";
      $msg .= "<p>Total Waktu Import : " . waktu_import($execution_time);
      $msg .= "</div>";

    }
    echo $msg;
    break;
  case 'input_jadwal':
    $check_jadwal_kelas = array(
      'sem_id' => $_POST['sem_id'],
      'ruang_id' => $_POST['ruang_id']
    );

    //check kelas name bentrok
    $kelas_name_check = array(
      'sem_id' => $_POST['sem_id'],
      'kelas_jadwal' => $_POST['kelas_name'],
      'kode_jur' => $_POST['kode_jur'],
      'sem_matkul' => $_POST['semester']
    );


    $check_bentrok_kelas = $db->query("select view_jadwal.*,kode_jur from view_jadwal inner join view_nama_kelas using(kelas_id) where
('" . $_POST['jam_mulai'] . "'>=jam_mulai and '" . $_POST['jam_mulai'] . "'<jam_selesai or '" . $_POST['jam_selesai'] . "'> jam_mulai and '" . $_POST['jam_selesai'] . "'<jam_selesai or jam_mulai 
> '" . $_POST['jam_mulai'] . "' and jam_mulai <'" . $_POST['jam_selesai'] . "' or jam_selesai>'" . $_POST['jam_mulai'] . "' and jam_selesai<'" . $_POST['jam_selesai'] . "' or jam_mulai='" . $_POST['jam_mulai'] . "' and jam_selesai='" . $_POST['jam_selesai'] . "') and hari like '%" . $_POST['hari'] . "' and kelas_id!='" . $_POST['kelas_id'] . "' and view_jadwal.sem_id=? and kelas_jadwal=? and kode_jur=? and sem_matkul=?
", $kelas_name_check);
    //echo $db->getErrorMessage();
    //Untuk  Matakuliah $dt->matkul_dosen
    if ($check_bentrok_kelas->rowCount() > 0) {
      foreach ($check_bentrok_kelas as $dt) {
        $prodi = $db->fetch_single_row("view_nama_kelas", "kelas_id", $dt->kelas_id);
        action_response("Maaf Lokal/Kelas ini di Jam ini sudah di pakai oleh Matakuliah " . $prodi->nm_matkul);
      }
    }


    $check_bentrok_kelas = $db->query("select view_jadwal.* from view_jadwal inner join view_nama_kelas using(kelas_id) where
('" . $_POST['jam_mulai'] . "'>=jam_mulai and '" . $_POST['jam_mulai'] . "'<jam_selesai or '" . $_POST['jam_selesai'] . "'> jam_mulai and '" . $_POST['jam_selesai'] . "'<jam_selesai or jam_mulai 
> '" . $_POST['jam_mulai'] . "' and jam_mulai <'" . $_POST['jam_selesai'] . "' or jam_selesai>'" . $_POST['jam_mulai'] . "' and jam_selesai<'" . $_POST['jam_selesai'] . "' or jam_mulai='" . $_POST['jam_mulai'] . "' and jam_selesai='" . $_POST['jam_selesai'] . "') and hari like '%" . $_POST['hari'] . "' and view_jadwal.kelas_id!='" . $_POST['kelas_id'] . "' and view_jadwal.sem_id=? and ruang_id=?
", $check_jadwal_kelas);
    echo $db->getErrorMessage();
    //Untuk  Matakuliah $dt->matkul_dosen
    if ($check_bentrok_kelas->rowCount() > 0) {
      foreach ($check_bentrok_kelas as $dt) {
        $prodi = $db->fetch_single_row("view_nama_kelas", "kelas_id", $dt->kelas_id);
        action_response("Maaf Ruangan ini Hari $dt->hari Jam " . $dt->jam_mulai . " S/d " . $dt->jam_selesai . " Sudah digunakan $prodi->jurusan Matakuliah $prodi->nm_matkul Kelas $dt->kelas_jadwal");
      }
    }

    if (isset($_POST['dosen'])) {

      $nip_dosen = array();
      foreach ($_POST['dosen'] as $key => $dosen) {
        $get_nip = $db->fetch_single_row("view_dosen", 'id_dosen', $dosen);
        $check_jadwal_dosen = array(
          'sem_id' => $_POST['sem_id'],
          'id_dosen' => $get_nip->nip
        );


        //if ($_SESSION['group_level']!='admin') {
        //check sks dosen lebih dari 16
/*  $id_mat = $db->query("select group_concat(mat_id) as mat_id from view_jadwal_dosen_kelas where sem_id=? and id_dosen=? and id_kelas!='".$_POST['kelas_id']."'",$check_jadwal_dosen);
  if ($id_mat->rowCount()>0) {
  $matkul_id = array();
    foreach ($id_mat as $mat_id) {
      $matkul_id[] = $mat_id->mat_id;
    }

  if (!empty($matkul_id)) {
    $mats_id = implode(",", $matkul_id);
    //coutn sks
    $sks_dosen = $db->fetch_custom_single("select sum(sks_tm+sks_prak+sks_prak_lap+sks_sim) as total_matkul from matkul where id_matkul in($mats_id)");
    dump($sks_dosen->total_matkul);
    if ($sks_dosen->total_matkul>15) {
      action_response("Maaf Dosen ini sudah melebihi 16 SKS");
    }
  }

  }*/



        $check_bentrok_dosen = $db->query("select * from view_jadwal_dosen_kelas where
('" . $_POST['jam_mulai'] . "'>=jam_mulai and '" . $_POST['jam_mulai'] . "'<jam_selesai or '" . $_POST['jam_selesai'] . "'> jam_mulai and '" . $_POST['jam_selesai'] . "'<jam_selesai or jam_mulai 
> '" . $_POST['jam_mulai'] . "' and jam_mulai <'" . $_POST['jam_selesai'] . "' or jam_selesai>'" . $_POST['jam_mulai'] . "' and jam_selesai<'" . $_POST['jam_selesai'] . "') and hari like '%" . $_POST['hari'] . "' and id_kelas!='" . $_POST['kelas_id'] . "' and sem_id=? and id_dosen=?
", $check_jadwal_dosen);
        if ($check_bentrok_dosen->rowCount() > 0) {
          foreach ($check_bentrok_dosen as $dt) {
            $prodi = $db->fetch_single_row("view_nama_kelas", "kelas_id", $dt->id_kelas);
            action_response("Maaf Dosen $get_nip->dosen punya jadwal mengajar di Prodi $prodi->jurusan  Hari $dt->hari Jam " . $dt->jam_mulai . " S/d " . $dt->jam_selesai . " Matakuliah $dt->matkul_dosen Kelas $dt->kelas_dosen");
          }
        }

        $nip_dosen[] = $get_nip->nip;
      }

    }

    //========================================================
// AMAN: Jangan hapus jadwal_kuliah (jadwal_id dipakai di tb_data_kelas_pertemuan)
// Cukup UPDATE jadwal yang sudah ada, atau INSERT jika belum ada
//========================================================
    $existing_jadwal = $db->fetchCustomSingle(
      "SELECT jadwal_id FROM jadwal_kuliah WHERE kelas_id=?",
      ['kelas_id' => $_POST['kelas_id']]
    );

    $data_jadwal = [
      'kelas_id' => $_POST['kelas_id'],
      'hari' => $_POST['hari'],
      'ruang_id' => $_POST['ruang_id'],
      'jam_mulai' => $_POST['jam_mulai'],
      'jam_selesai' => $_POST['jam_selesai'],
    ];
    if (in_array($_POST['hari'], array_values($array_hari))) {
      $data_jadwal['id_hari'] = $array_hari[$_POST['hari']];
    }

    dump($data_jadwal);

    exit();

    if ($existing_jadwal) {
      // UPDATE: jadwal_id tetap sama, referensi di tb_data_kelas_pertemuan aman
      unset($data_jadwal['kelas_id']); // jangan update kolom kelas_id
      $db->update('jadwal_kuliah', $data_jadwal, 'jadwal_id', $existing_jadwal->jadwal_id);
    } else {
      // INSERT baru jika belum ada jadwal sama sekali
      $db->insert('jadwal_kuliah', $data_jadwal);
    }

    //========================================================
// AMAN: Sync dosen_kelas tanpa hapus semua
// - Hapus HANYA dosen yang tidak ada di form terbaru
// - Insert dosen baru yang belum ada
//========================================================
    if (isset($_POST['dosen']) && !empty($nip_dosen)) {

      // NIP dosen dari form (sudah diproses di loop atas)
      $nip_baru = $nip_dosen;

      // Dosen yang saat ini terdaftar di kelas ini
      $existing_dosen = $db->query(
        "SELECT id_dosen FROM dosen_kelas WHERE id_kelas=?",
        ['id_kelas' => $_POST['kelas_id']]
      );
      $nip_lama = [];
      foreach ($existing_dosen as $ed) {
        $nip_lama[] = $ed->id_dosen;
      }

      // Hapus dosen yang sudah tidak ada di form (dihapus user dari edit)
      $nip_dihapus = array_diff($nip_lama, $nip_baru);
      foreach ($nip_dihapus as $nip_del) {
        $db->query(
          "DELETE FROM dosen_kelas WHERE id_kelas=? AND id_dosen=?",
          ['id_kelas' => $_POST['kelas_id'], 'id_dosen' => $nip_del]
        );
      }

      // Insert atau update dosen yang ada di form
      for ($i = 0; $i < count($_POST['dosen']); $i++) {
        $nip_i = $nip_dosen[$i];
        if (in_array($nip_i, $nip_lama)) {
          // Sudah ada: UPDATE saja (dosen_ke, jml_tm_renc, dll)
          $db->query(
            "UPDATE dosen_kelas SET dosen_ke=?, jml_tm_renc=?, jml_tm_real=? WHERE id_kelas=? AND id_dosen=?",
            [
              $_POST['dosen_ke'][$i],
              $_POST['jml_tm_renc'][$i],
              $_POST['jml_tm_renc'][$i],
              $_POST['kelas_id'],
              $nip_i
            ]
          );
        } else {
          // Belum ada: INSERT baru
          $data_insert_dosen = [
            'id_kelas' => $_POST['kelas_id'],
            'id_dosen' => $nip_i,
            'dosen_ke' => $_POST['dosen_ke'][$i],
            'jml_tm_renc' => $_POST['jml_tm_renc'][$i],
            'jml_tm_real' => $_POST['jml_tm_renc'][$i],
          ];
          $db->insert('dosen_kelas', $data_insert_dosen);
        }
      }

    } else {
      // Kalau POST dosen kosong, tidak hapus apa-apa (biarkan dosen lama)
    }

    action_response($db->getErrorMessage());

    //check pengajar

    break;
  case "in":

    //check is current jadwal
    $is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $_POST["sem_id"]));

    $current_data = strtotime(date("Y-m-d H:i:s"));
    $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
    $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

    if ($current_data > $contractDateBegin && $current_data < $contractDateEnd) {
      $is_edit = 1;
    } else {
      $is_edit = 0;
    }

    if ($is_edit == 0) {
      $akses = get_akses_prodi();
      $is_jadwal_edit = $db->fetch_custom_single("select * from semester $akses and id_semester=?", array('id_semester' => $_POST["sem_id"]));

      $current_data = strtotime(date("Y-m-d H:i:s"));

      $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
      $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

      if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
        $is_edit = 1;
      } else {
        $is_edit = 0;
      }
    }

    if ($is_edit == 1 or $_SESSION['level'] == '1') {
      //check if Jika Kelas dengan kode mk tersebut sudah ada is exist
      $check_kelas = $db->check_exist("kelas", array('sem_id' => $_POST['sem_id'], 'id_matkul' => $_POST['id_matkul'], 'kls_nama' => $_POST['kls_nama']));

      if ($check_kelas == true) {
        action_response("Maaf Kelas Kuliah dengan Kode MK dan Nama Kelas di Periode Semester ini Sudah Ada");
      }

      $kode_mk = $db->fetch_single_row("matkul", 'id_matkul', $_POST['id_matkul']);
      $kode = $kode_mk->kode_mk;
      $data = array(
        "kls_nama" => $_POST["kls_nama"],
        "id_matkul" => $_POST["id_matkul"],
        "sem_id" => $_POST["sem_id"],
        "id_jenis_kelas" => $_POST["id_jenis_kelas"],
        "peserta_max" => $_POST["peserta_max"],
        "peserta_min" => $_POST["peserta_min"],
        "date_created" => date('Y-m-d H:i:s'),
        "catatan" => $_POST["catatan"]
      );

      if (isset($_POST["is_open"]) == "on") {
        $is_open = array("is_open" => "Y");
        $data = array_merge($data, $is_open);
      } else {
        $is_open = array("is_open" => "N");
        $data = array_merge($data, $is_open);
      }
      $in = $db->insert("kelas", $data);
      $id_kelas = $db->last_insert_id();

      foreach ($db->query("select * from komponen_nilai  where isShow='1' ") as $kp) {
        if (isset($_POST['komponen_' . $kp->id])) {
          $data_komponen = array(
            'id_kelas' => $id_kelas,
            'id_komponen' => $kp->id,
            'nilai' => $_POST['komponen_' . $kp->id]
          );
          $in_kelas = $db->insert('kelas_penilaian', $data_komponen);
        }
      }
      /*    $data_kelas = array('kelas_id' => $id_kelas );
          $db->insert("jadwal_kuliah",$data_kelas);      
      */
    } else {
      action_response("Tidak Menambah Kelas di Periode ini diluar periode Input Jadwal");
    }
    action_response($db->getErrorMessage());
    break;
  case "delete":
    $data_kelas = $db->fetch_single_row("kelas", "kelas_id", $_GET['id']);

    //check is current jadwal
    $is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $data_kelas->sem_id));

    $current_data = strtotime(date("Y-m-d H:i:s"));
    $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
    $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

    if ($current_data > $contractDateBegin && $current_data < $contractDateEnd) {
      $is_edit = 1;
    } else {
      $is_edit = 0;
    }

    if ($is_edit == 0) {
      $akses = get_akses_prodi();
      $is_jadwal_edit = $db->fetch_custom_single("select * from semester $akses and id_semester=?", array('id_semester' => $data_kelas->sem_id));

      $current_data = strtotime(date("Y-m-d H:i:s"));

      $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
      $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

      if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
        $is_edit = 1;
      } else {
        $is_edit = 0;
      }
    }


    if ($is_edit == 1 or $_SESSION['level'] == '1') {

      $cek_jumlah_krs = $db->fetch_custom_single("select fungsi_get_jml_krs(" . $_GET['id'] . ") as jml_approve,fungsi_get_jml_krs_belum_disetujui(" . $_GET['id'] . ") as batal");



      if ($cek_jumlah_krs->jml_approve != 0 && $cek_jumlah_krs->jml_approve - $cek_jumlah_krs->batal != 0) {
        action_response("Kelas tidak bisa dihapus karena sudah memiliki peserta kelas");
      } else {
        $db->query("delete from jadwal_kuliah where kelas_id=?", array('kelas_id' => $_GET['id']));
        $db->query("delete from dosen_kelas where id_kelas=?", array('kelas_id' => $_GET['id']));
        $db->query("delete from krs_detail where id_kelas=?", array('kelas_id' => $_GET['id']));
        $db->query("delete from kelas where kelas_id=?", array('kelas_id' => $_GET['id']));

      }
      $db->delete("kelas", "kelas_id", $_GET["id"]);
    } else {
      action_response("Tidak bisa hapus karena diluar periode Input Jadwal");
    }

    action_response($db->getErrorMessage());


    break;
  case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);


    $id_kelas = $data_id_array[0];

    $data_kelas = $db->fetch_single_row("kelas", "kelas_id", $id_kelas);



    //check is current jadwal
    $is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $data_kelas->sem_id));

    $current_data = strtotime(date("Y-m-d"));
    $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal);
    $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal);

    if ($current_data > $contractDateBegin && $current_data < $contractDateEnd) {
      $is_edit = 1;
    } else {
      $is_edit = 0;
    }

    if ($is_edit == 0) {
      $akses = get_akses_prodi();
      $is_jadwal_edit = $db->fetch_custom_single("select * from semester $akses and id_semester=?", array('id_semester' => $data_kelas->sem_id));

      $current_data = strtotime(date("Y-m-d H:i:s"));

      $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
      $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

      if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
        $is_edit = 1;
      } else {
        $is_edit = 0;
      }
    }

    if ($is_edit == 1 or $_SESSION['level'] == '1') {

      if (!empty($data_id_array)) {
        foreach ($data_id_array as $id) {
          $cek_jumlah_krs = $db->fetch_custom_single("select fungsi_get_jml_krs(" . $id . ") as jml_approve,fungsi_get_jml_krs_belum_disetujui(" . $id . ") as batal");
          if ($cek_jumlah_krs->jml_approve != 0 && $cek_jumlah_krs->jml_approve - $cek_jumlah_krs->batal != 0) {
            action_response("Kelas tidak bisa dihapus karena sudah memiliki peserta kelas");
          } else {
            $db->query("delete from jadwal_kuliah where kelas_id=?", array('kelas_id' => $id));
            $db->query("delete from dosen_kelas where id_kelas=?", array('kelas_id' => $id));
            $db->query("delete from krs_detail where id_kelas=?", array('kelas_id' => $id));
            $db->query("delete from kelas where kelas_id=?", array('kelas_id' => $id));

          }
          $db->delete("kelas", "kelas_id", $id);
        }
      }
    } else {
      action_response("Tidak bisa hapus karena diluar periode Input Jadwal");
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    //check is current jadwal
    $is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $_POST["sem_id"]));

    $current_data = strtotime(date("Y-m-d H:i:s"));
    $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
    $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

    if ($current_data > $contractDateBegin && $current_data < $contractDateEnd) {
      $is_edit = 1;
    } else {
      $is_edit = 0;
    }

    if ($is_edit == 0) {
      $akses = get_akses_prodi();
      $is_jadwal_edit = $db->fetch_custom_single("select * from semester $akses and id_semester=?", array('id_semester' => $_POST["sem_id"]));

      $current_data = strtotime(date("Y-m-d H:i:s"));

      $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
      $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

      if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
        $is_edit = 1;
      } else {
        $is_edit = 0;
      }
    }

    if ($is_edit == 1 or $_SESSION['level'] == '1') {

      //check if Jika Kelas dengan kode mk tersebut sudah ada is exist
      $check_kelas = $db->query("select * from kelas where sem_id=? and id_matkul=? and kls_nama=? and kelas_id!='" . $_POST["kelas_id"] . "'", array('sem_id' => $_POST['sem_id'], 'id_matkul' => $_POST['id_matkul'], 'kls_nama' => $_POST['kls_nama']));
      if ($check_kelas->rowCount() > 0) {
        action_response("Maaf Kelas Kuliah dengan Kode MK dan Nama kelas di Periode Semester ini Sudah Ada");
      }
      $data = array(
        "kls_nama" => $_POST["kls_nama"],
        "peserta_max" => $_POST["peserta_max"],
        "peserta_min" => $_POST["peserta_min"],
        "id_jenis_kelas" => $_POST["id_jenis_kelas"],
        "catatan" => $_POST["catatan"]
      );

      if (isset($_POST["is_open"]) == "on") {
        $is_open = array("is_open" => "Y");
        $data = array_merge($data, $is_open);
      } else {
        $is_open = array("is_open" => "N");
        $data = array_merge($data, $is_open);
      }

      $data = array_filter($data);

      $up = $db->update("kelas", $data, "kelas_id", $_POST["kelas_id"]);
      $db->query("delete from kelas_penilaian where id_kelas='" . $_POST["kelas_id"] . "' ");
      foreach ($db->query("select * from komponen_nilai  where isShow='1' ") as $kp) {
        if (isset($_POST['komponen_' . $kp->id])) {
          $data_komponen = array(
            'id_kelas' => $_POST["kelas_id"],
            'id_komponen' => $kp->id,
            'nilai' => $_POST['komponen_' . $kp->id]
          );
          $in_kelas = $db->insert('kelas_penilaian', $data_komponen);
        }
      }

    } else {
      action_response("Tidak Menambah Kelas di Periode ini diluar periode Input Jadwal");
    }
    action_response($db->getErrorMessage());
    break;
  case "gen_jadwal":
    /* echo "<pre>";
     echo "sesi";
     print_r($_POST['sesi']);
     echo "hari";
     print_r($_POST['hari']);
     echo "ruang";
     print_r($_POST['ruangan']); */
    $jur = $_POST['jur'];
    $sem = $_POST['sem'];
    $break = false;
    $jml = 0;
    $kelasSukses = "<table class='table'>
                      <thead>
                        <tr>
                         <th>No</th>
                         <th>Mata Kuliah</th>
                         <th>Kelas</th>
                         <th>Dosen Pengampu</th>
                         <th>Hari</th>
                         <th>Jam</th>
                         <th>Ruang</th>
                        </tr>
                      </thead>
                      <tbody>";
    $no = 1;
    foreach ($_POST['hari'] as $k => $v) { //pengulangan hari
      //echo "$v<br>";
      if ($break == false) {
        foreach ($_POST['ruangan'] as $kk => $vv) { //pengulangan ruangan
          if ($break == false) {
            foreach ($_POST['sesi'] as $s => $ss) { //pengulangan sesi
              if ($break == false) {
                // echo de($_GET['j'])."<br>".de($_GET['s']);
                $q = $db->query("select vnk.kelas_id,vj.jadwal_id,vj.jadwal_id,vnk.nm_matkul,vnk.nama_kelas,hari,jam_mulai,jam_selesai,concat(dosen.gelar_depan,' ',dosen.nama_dosen,' ',dosen.gelar_belakang) as nama_dosen from view_nama_kelas vnk
left join jadwal_kuliah vj on vnk.kelas_id=vj.kelas_id
left join dosen_kelas vd on vnk.kelas_id=vd.id_kelas
left join dosen on vd.id_dosen=dosen.nip
                            where vnk.kode_jur=$jur and vnk.sem_id=$sem
                            and vj.hari is null and vj.jam_mulai is null
                            and vj.jam_selesai is null and vj.ruang_id is null order by rand() limit 1");
                // echo $q->rowCount();
                if ($q->rowCount() > 0) {
                  // echo "string";
                  foreach ($q as $k) {
                    $jam = explode("===", $ss);
                    $ru = explode("===", $vv);
                    $check_bentrok_kelas = $db->query("select * from view_jadwal where
('" . $jam[0] . "'>jam_mulai and '" . $jam[0] . "'<jam_selesai or '" . $jam[1] . "'> jam_mulai and '" . $jam[1] . "'<jam_selesai or jam_mulai 
> '" . $jam[0] . "' and jam_mulai <'" . $jam[1] . "' or jam_selesai>'" . $jam[0] . "' and jam_selesai<'" . $jam[1] . "') and hari like '%" . strtolower($v) . "' and kelas_id!='" . $k->kelas_id . "' and sem_id='" . $sem . "' and ruang_id='" . $ru[0] . "' 
");

                    if ($check_bentrok_kelas->rowCount() < 1) {

                      $data_jadwal = array(
                        'hari' => strtolower($v),
                        'ruang_id' => $ru[0],
                        'jam_mulai' => $jam[0],
                        'jam_selesai' => $jam[1]
                      );
                      if (in_array(strtolower($v), array_values($array_hari))) {
                        $data_jadwal['id_hari'] = $array_hari[strtolower($v)];
                      }
                      if ($k->jadwal_id == "") {

                        $data_jadwal_in = array(
                          'kelas_id' => $k->kelas_id,
                          'hari' => strtolower($v),
                          'ruang_id' => $ru[0],
                          'jam_mulai' => $jam[0],
                          'jam_selesai' => $jam[1]
                        );
                        if (in_array(strtolower($v), array_values($array_hari))) {
                          $data_jadwal_in['id_hari'] = $array_hari[strtolower($v)];
                        }
                        $up = $db->insert("jadwal_kuliah", $data_jadwal_in);
                      } else {
                        $up = $db->update("jadwal_kuliah", $data_jadwal, "jadwal_id", $k->jadwal_id);
                      }


                      if ($up == true) {
                        $jml++;

                        $kelasSukses .= "<tr>
                                        <td>$no</td>
                                        <td>$k->nm_matkul</td>
                                        <td>$k->nama_kelas</td>
                                        <td>$k->nama_dosen</td>
                                        <td>" . strtolower($v) . "</td>
                                        <td>" . $jam[0] . " - " . $jam[1] . "</td>
                                        <td>" . $ru[1] . "</td>
                                       </tr>";
                        $no++;

                      }
                    }


                  }
                } else {
                  // echo "string";
                  $break = true;
                  break;
                }
              } else {
                break;
              }

            }
          } else {
            break;
          }

        }
      } else {
        break;
      }

    }
    $kelasSukses .= "</tbody></table>";
    $q = $db->query("select vnk.nm_matkul,vnk.nama_kelas,hari,jam_mulai,jam_selesai,concat(dosen.gelar_depan,' ',dosen.nama_dosen,' ',dosen.gelar_belakang) as nama_dosen from view_nama_kelas vnk
left join jadwal_kuliah vj on vnk.kelas_id=vj.kelas_id
left join dosen_kelas vd on vnk.kelas_id=vd.id_kelas
left join dosen on vd.id_dosen=dosen.nip
                            where vnk.kode_jur=$jur and vnk.sem_id=$sem
                            and vj.hari is null and vj.jam_mulai is null
                            and vj.jam_selesai is null and vj.ruang_id is null order by rand() limit 1");
    $gagal = $q->rowCount();
    // echo "good";
    echo '<div  class="alert alert-success" role="alert" style="font-size:15px"> ' . $jml . ' Kelas ! Berhasil dibuatkan jadwal  <a  data-toggle="collapse" href="#kelasSukses" aria-expanded="false" aria-controls="collapseExample">
       Lihat Kelas Sukses
      </a><div class="collapse" id="kelasSukses">
        <div class="well" style="background:#00a65a">
          ' . $kelasSukses . '
        </div>
      </div></div></div>';
    if ($gagal > 0) {
      $kelasGagal = "<table class='table'>
                      <thead>
                        <tr>
                         <th>No</th>
                         <th>Mata Kuliah</th>
                         <th>Kelas</th>
                         <th>Dosen Pengampu</th>
                        </tr>
                      </thead>
                      <tbody>";
      $no = 1;
      foreach ($q as $k) {
        $kelasGagal .= "<tr>
                      <td>$no</td>
                      <td>$k->nm_matkul</td>
                      <td>$k->nama_kelas</td>
                      <td>$k->nama_dosen</td>
                     </tr>";
        $no++;
      }
      $kelasGagal .= "</tbody></table>";
      echo ' <div class="alert alert-warning" role="alert" style="font-size:15px"> ' . $gagal . ' Kelas  Belum tergenerate. 
     <a  data-toggle="collapse" href="#kelasGagal" aria-expanded="false" aria-controls="collapseExample">
       Lihat Kelas yang belum terjadwalkan
      </a>
      <div class="collapse" id="kelasGagal">
        <div class="well" style="background:#88130d">
          ' . $kelasGagal . '
        </div>
      </div></div>';






    }
    break;
  case 'reset-jadwal':
    $jur = $_POST['jur'];
    $sem = $_POST['sem'];
    $id_kelas = $db->fetch_custom_single("select group_concat(kelas_id) as kelas_id from view_nama_kelas vnk 
where vnk.kode_jur='$jur' and vnk.sem_id='$sem'");
    $db->query("delete from dosen_kelas where id_kelas in(
select kelas_id from view_nama_kelas vnk where vnk.kode_jur=$jur and vnk.sem_id=$sem)");
    echo $db->getErrorMessage();
    $db->query("delete from jadwal_kuliah where kelas_id in(
select kelas_id from view_nama_kelas vnk where vnk.kode_jur=$jur and vnk.sem_id=$sem)");
    echo $db->getErrorMessage();
    break;
  default:
    # code...
    break;
}

?>