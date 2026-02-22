<?php
session_start();
include "../../inc/config.php";
session_check();
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {

 case 'import':
      if (!is_dir("../../../upload/upload_excel")) {
              mkdir("../../../upload/upload_excel");
            }


   if (!preg_match("/.(xls|xlsx)$/i", $_FILES["excel_file"]["name"]) ) {

              echo "pastikan file yang anda pilih xls|xlsx";
              exit();

            } else {
              move_uploaded_file($_FILES["excel_file"]["tmp_name"], "../../../upload/upload_excel/".$_FILES['excel_file']['name']);
              $excel_file = array("excel_file"=>$_FILES["excel_file"]["name"]);

            }

      $error_count = 0;
      $error = array();
      $sukses = 0;
      $values = "";
      $data_insert = array();
      $data_error = array();

  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['excel_file']['name']);

  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
          $check_nim = $db2->query("select nim,jur_kode from mahasiswa where nim='".trimmer($val[0])."'");
          foreach ($check_nim as $nim ) {
            $nim_mhs = $nim;
            $kode_jur = $nim->jur_kode;
          }
          if ($check_nim->rowCount()<1) {
            $error_count++;
            $error[] = $val[0]." NIM tidak ditemukan di sistem";
            $data_error[] = array(
                $val[0],
                $val[1],
                "NIM tidak ditemukan di sistem"
            );
            //include "download_error_import.php";
          } else {
            //check exist
            $check = $db2->query("select nim from tb_data_kelulusan where nim='".trimmer($val[0])."'");
            if ($check->rowCount()>0) {
              $error_count++;
              $error[] = $val[0]." Sudah Ada di Kelulusan";
              $data_error[] = array(
                  $val[0],
                  $val[1],
                  "NIM Sudah Ada di Kelulusan"
              );
              include "download_error_import.php";
            } else {
              $sukses++;
              $data_insert[] = array(
                  "nim" => trimmer($val[0]),
                  "nama" => trimmer($val[1]),
                  "id_jenis_keluar" => trimmer($val[2]),
                  "tanggal_keluar" => trimmer($val[3]),
                  "semester" => trimmer($val[4]),
                  "sk_yudisium" => trimmer($val[5]),
                  "tgl_sk_yudisium" => trimmer($val[6]),
                  "ipk" => trimmer($val[7]),
                  "no_seri_ijasah" => trimmer($val[8]),
                  "kode_jurusan" => $kode_jur,
                  "created_date" => date('Y-m-d'),
                  "created_by" => $_SESSION['nama']
              );
            }

        }

      }

    }

      }

if (!empty($data_insert)) {
    $insert = $db2->insertMulti('tb_data_kelulusan',$data_insert);
    echo $db2->getErrorMessage();
}

unlink("../../../upload/upload_excel/".$_FILES['excel_file']['name']);
$msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

if (($sukses>0) || ($error_count>0)) {
    $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <font color=\"#3c763d\">".$sukses." data mahasiswa lulus berhasil di import</font><br />
    <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
    if (!$error_count==0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
    }
    
    if ($error_count>0) {
        $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
        $i=1;
        foreach ($error as $pesan) {
            $msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div><br />";
            $i++;
        }
        $msg .= "</div><br><a href='".base_url()."upload/sample/mahasiswa_lulus/".$filename."' class='btn btn-sm btn-primary style='text-decoration:none;'>Download Data Error</a><br>";
    }

    $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
    $msg .= "</div>";

}
echo $msg;
    break;
  case "in":
  //check kelulusan exist
   $kode_jurusan = $db2->fetchSingleRow("mahasiswa","nim",$_POST['nim']);
  $check_exist = $db2->checkExist("tb_data_kelulusan",array('nim' => $_POST['nim'],'kode_jurusan' => $kode_jurusan->jur_kode));
  if ($check_exist) {
    action_response("Mahasiswa ini sudah ada di data kelulusan");
  }
  $data = array(
      "nim" => $_POST["nim"],
      "kode_jurusan" => $kode_jurusan->jur_kode,
      "id_jenis_keluar" => $_POST["id_jenis_keluar"],
      "tanggal_keluar" => $_POST["tanggal_keluar"],
      "semester" => $_POST["semester"],
      "keterangan_kelulusan" => $_POST["keterangan_kelulusan"],
      "nomor_sk" => $_POST["nomor_sk"],
      "tanggal_sk" => $_POST["tanggal_sk"],
      "ipk" => $_POST["ipk"],
      "no_seri_ijasah" => $_POST["no_seri_ijasah"],
  );
  
  
  
   
    $in = $db2->insert("tb_data_kelulusan",$data);
    
    
    action_response($db2->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db2->delete("tb_data_kelulusan","id",$_GET["id"]);
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_kelulusan","id",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
      "id_jenis_keluar" => $_POST["id_jenis_keluar"],
      "tanggal_keluar" => $_POST["tanggal_keluar"],
      "semester" => $_POST["semester"],
      "keterangan_kelulusan"=>$_POST["keterangan_kelulusan"],
      "nomor_sk" => $_POST["nomor_sk"],
      "tanggal_sk" => $_POST["tanggal_sk"],
      "ipk" => $_POST["ipk"],
      "no_seri_ijasah" => $_POST["no_seri_ijasah"],
   );
   
   
   

    
    
    $up = $db2->update("tb_data_kelulusan",$data,"id",$_POST["id"]);
    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>