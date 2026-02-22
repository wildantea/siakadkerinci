<?php
session_start();
include "../../inc/config.php";
session_check_json();
//include on top
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
    //action
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

  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);

  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
  $gedung_id = preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
  $check_kode_ruang = preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));

  $gedung_check_id = $db->check_exist("gedung_ref",array("kode_gedung" => $gedung_id));
  if ($gedung_check_id==true) {
    $gedung_id = $db->check_exist_data("gedung_ref",array("kode_gedung" => $gedung_id)); 
          $check = $db->check_exist('ruang_ref',array('gedung_id' => $gedung_id->gedung_id,'kode_ruang' => $check_kode_ruang));
          if ($check==true) {
            $error_count++;
            $error[] = 'Kode Ruangan '.$val[1]." Sudah Ada di Gedung ini";
          } else {
            $sukses++;
      $array_ruang = array(
        'gedung_id' => $gedung_id->gedung_id,
        'kode_ruang' => $check_kode_ruang,
        'nm_ruang' => preg_replace( '/[^[:print:]]/', '',filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)),
        'ket' => filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)
      );
      $db->insert('ruang_ref',$array_ruang);
      $penggunaan = preg_replace( '/[^[:print:]]/', '',filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
      if ($penggunaan!="") {
          $exp_penggunaan = explode(",", $penggunaan);
      }
    $id_ruang = $db->last_insert_id();
    if (count($exp_penggunaan)>0) {
         foreach ($exp_penggunaan as $kode) {
      $nama_jurusan = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$kode);
      $db->insert('ruang_ref_prodi',array('ruang_id' => $id_ruang,'kode_jur' => $kode,'nama_jurusan' => $nama_jurusan->jurusan));
      }
    }

    echo $db->getErrorMessage();


        }
  } else {
            $error_count++;
            $error[] = 'Kode Gedung '.$val[0]." Tidak Ditemukan";
  }
 

      }

    }

      }

          unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Ruang Kuliah baru berhasil di import</font><br />
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

$check_if_kode_ruang_exist = $db->check_exist('ruang_ref',array(
'gedung_id' => $_POST['gedung_id'],
'kode_ruang' => $_POST['kode_ruang']
));
if ($check_if_kode_ruang_exist) {
  action_response("Maaf, Kode Ruang Sudah Digunakan di Gedung ini");
}
  $data = array(
      "gedung_id" => $_POST["gedung_id"],
      "kode_ruang" => $_POST["kode_ruang"],
      "nm_ruang" => $_POST["nm_ruang"],
      "ket" => $_POST["ket"],
  );
          if(isset($_POST["is_aktif"])=="on")
          {
            $is_aktif = array("is_aktif"=>"Y");
            $data=array_merge($data,$is_aktif);
          } else {
            $is_aktif = array("is_aktif"=>"N");
            $data=array_merge($data,$is_aktif);
          }
          

    $in = $db->insert("ruang_ref",$data);
    $id_ruang = $db->last_insert_id();
    foreach ($_POST['kode_jur'] as $kode) {
      $nama_jurusan = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$kode);
      $db->insert('ruang_ref_prodi',array('ruang_id' => $id_ruang,'kode_jur' => $kode,'nama_jurusan' => $nama_jurusan->jurusan));
    }
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("ruang_ref","ruang_id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("ruang_ref","ruang_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
      $data = array(
      "gedung_id" => $_POST["gedung_id"],
      "kode_ruang" => $_POST["kode_ruang"],
      "nm_ruang" => $_POST["nm_ruang"],
      "ket" => $_POST["ket"],
  );
          if(isset($_POST["is_aktif"])=="on")
          {
            $is_aktif = array("is_aktif"=>"Y");
            $data=array_merge($data,$is_aktif);
          } else {
            $is_aktif = array("is_aktif"=>"N");
            $data=array_merge($data,$is_aktif);
          }
    $up = $db->update("ruang_ref",$data,"ruang_id",$_POST["id"]);
    $db->query("delete from ruang_ref_prodi where ruang_id='".$_POST['id']."'");
    $id_ruang = $_POST['id'];
    foreach ($_POST['kode_jur'] as $kode) {
      $nama_jurusan = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$kode);
      $db->insert('ruang_ref_prodi',array('ruang_id' => $id_ruang,'kode_jur' => $kode,'nama_jurusan' => $nama_jurusan->jurusan));
    }
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>