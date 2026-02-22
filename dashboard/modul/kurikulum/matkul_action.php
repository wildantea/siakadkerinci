<?php
session_start();
include "../../inc/config.php";
session_check();
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
  case 'import':
      if (!is_dir("../../../upload/matakuliah")) {
              mkdir("../../../upload/matakuliah");
            }


   if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

              echo "pastikan file yang anda pilih xls|xlsx";
              exit();

            } else {
              move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/matakuliah/".$_FILES['semester']['name']);
              $semester = array("semester"=>$_FILES["semester"]["name"]);

            }

      $error_count = 0;
      $error = array();
      $sukses = 0;
$values = "";

  $Reader = new SpreadsheetReader("../../../upload/matakuliah/".$_FILES['semester']['name']);

$jenjang = $db->fetch_custom_single("select jurusan.id_jenjang from jurusan
inner join kurikulum on jurusan.kode_jur=kurikulum.kode_jur
where kurikulum.kur_id=?",array('kur_id' => $_POST['id_kur']));
  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
          $check = $db->check_exist('matkul',array('kode_mk' => filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH),'kur_id'=>$_POST['id_kur']));
          if ($check==true) {
            $error_count++;
            $error[] = $val[0]." Sudah Ada";
          } else {
            $sukses++;
        if (filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)=='A' or filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)=='W') {
          $wajib_mat = 1;
        } else {
          $wajib_mat = 0;
        }
      $values .= '("'.
      $_POST['id_kur'].'","'.
      $jenjang->id_jenjang.'","'.
      preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
      preg_replace( '/[^[:print:]]/', '',filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
            preg_replace( '/[^[:print:]]/', '',filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
            preg_replace( '/[^[:print:]]/', '',filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
      filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      filter_var($val[7], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      filter_var($val[8], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      filter_var($val[9], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'","'.
      $wajib_mat.'"),';
        }

      }

    }

      }

if ($values!="") {
  $values = rtrim($values,",");

  $query = "insert into matkul (kur_id,id_jenjang,kode_mk,nama_mk,id_tipe_matkul,sks_tm,sks_prak,sks_prak_lap,sks_sim,tgl_mulai_efektif,tgl_akhir_efektif,semester,a_wajib) values ".$values;
  $db->query($query);
  $db->query("UPDATE matkul SET bobot_minimal_lulus=(sks_tm+ sks_prak+ sks_prak_lap+ sks_sim)");
  echo $db->getErrorMessage();
}

          unlink("../../../upload/matakuliah/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Matakuliah baru berhasil di import</font><br />
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
            $msg .= "</div>";
            $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
           $msg .= "</div>";

      }
     echo $msg;
    break;

  case "in":
    
    if(isset($_POST["a_wajib"])=="on")
    {
      $a_wajib = 1;
    } else {
      $a_wajib = 0;
    }

  $data = array(
      "kur_id" => $_POST["kur_id"],
      "id_jenjang" => $_POST["id_jenjang"],
      "kode_mk" => $_POST["kode_mk"],
      "nama_mk" => $_POST["nama_mk"],
      "id_tipe_matkul" => $_POST["id_tipe_matkul"],
      "semester" => $_POST["semester"],
      "a_wajib" => $a_wajib,
      "sks_tm" => $_POST["sks_tm"],
      "sks_prak" => $_POST["sks_prak"],
      "sks_prak_lap" => $_POST["sks_prak_lap"],
      "sks_sim" => $_POST["sks_sim"],
      "total_sks" =>  ($_POST["sks_tm"]+ $_POST["sks_prak"]+ $_POST["sks_prak_lap"]+ $_POST["sks_sim"]),
      "metode_pelaksanaan_kuliah" => $_POST["metode_pelaksanaan_kuliah"],
      "tgl_mulai_efektif" => $_POST["tgl_mulai_efektif"],
      "tgl_akhir_efektif" => $_POST["tgl_akhir_efektif"],
  );
  $data = array_filter($data);

    $in = $db->insert("matkul",$data);

    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("matkul","id_matkul",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("matkul","id_matkul",$id);
         }
    }
    break;
  case "up":
    
    if(isset($_POST["a_wajib"])=="on")
    {
      $a_wajib = 1;
    } else {
      $a_wajib = 0;
    }

  $data = array(
      "kur_id" => $_POST["kur_id"],
      "id_jenjang" => $_POST["id_jenjang"],
      "kode_mk" => $_POST["kode_mk"],
      "nama_mk" => $_POST["nama_mk"],
      "id_tipe_matkul" => $_POST["id_tipe_matkul"],
      "semester" => $_POST["semester"],
      "a_wajib" => $a_wajib,
      "sks_tm" => $_POST["sks_tm"],
      "sks_prak" => $_POST["sks_prak"],
      "sks_prak_lap" => $_POST["sks_prak_lap"],
      "sks_sim" => $_POST["sks_sim"],
      "total_sks" =>  ($_POST["sks_tm"]+ $_POST["sks_prak"]+ $_POST["sks_prak_lap"]+ $_POST["sks_sim"]),
      "metode_pelaksanaan_kuliah" => $_POST["metode_pelaksanaan_kuliah"],
      "tgl_mulai_efektif" => $_POST["tgl_mulai_efektif"],
      "tgl_akhir_efektif" => $_POST["tgl_akhir_efektif"],
  );
   //$data = array_filter($data);

    
    $up = $db->update("matkul",$data,"id_matkul",$_POST["id"]);
    $array_sks = array(
      'sks' => $_POST['total_sks']
    );
    
    //update krs detail
    $up_krs = $db->update('krs_detail',$array_sks,'kode_mk',$_POST["id"]);

    echo $db->getErrorMessage();
    
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