<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true); 
require('../../inc/lib/simplexlsx.class.php');
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
$array_insert = array();

  $Reader = new SimpleXLSX("../../../upload/upload_excel/".$_FILES['semester']['name']);
  foreach( $Reader->rows() as $key => $val ) {
      if ($key>0) {
           if ($val[0]!="") {

    if ($val[6]!='') {
        $check = $db->check_exist('skala_nilai',array(
          'nilai_huruf'=> trimmer($val[0]),
          'kode_jurusan' => trimmer($val[6]),
          'berlaku_angkatan' => trimmer($val[7])
        ));
          if ($check==true) {
            $error_count++;
            $error[] = "Skala Nilai ".$val[0]." di Prodi ".$val[6]." Angkatan ".$val[7]."Sudah Ada";
          } else {
            $sukses++;
            $array_insert[] = array(
              'nilai_huruf' => trimmer($val[0]),
              'nilai_indeks' => trimmer($val[1]),
              'bobot_nilai_min' => trimmer($val[2]),
              'bobot_nilai_maks' => trimmer($val[3]),
              'tgl_mulai_efektif' => trimmer($val[4]),
              'tgl_akhir_efektif' => trimmer($val[5]),
              'kode_jurusan' => trimmer($val[6]),
              'berlaku_angkatan' => trimmer($val[7])
            );
        }
    }

}
      
    }
   
}

if (!empty($array_insert)) {
  $db->insertMulti('skala_nilai',$array_insert);
}
    unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
        $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
if (($sukses>0) || ($error_count>0)) {
  $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" >
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
      <font color=\"#3c763d\">".$sukses." Data Skala Nilai  berhasil di import</font><br />
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
      "nilai_huruf" => $_POST["nilai_huruf"],
      "nilai_indeks" => $_POST["nilai_indeks"],
      "bobot_nilai_min" => $_POST["bobot_nilai_min"],
      "bobot_nilai_maks" => $_POST["bobot_nilai_maks"],
      "tgl_mulai_efektif" => $_POST["tgl_mulai_efektif"],
       "berlaku_angkatan" => $_POST["berlaku_angkatan"],
      
      "tgl_akhir_efektif" => $_POST["tgl_akhir_efektif"],
      "kode_jurusan" => $_POST["kode_jurusan"],
  );
  
  
  
   
    $in = $db->insert("skala_nilai",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("skala_nilai","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("skala_nilai","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nilai_huruf" => $_POST["nilai_huruf"],
      "nilai_indeks" => $_POST["nilai_indeks"],
      "bobot_nilai_min" => $_POST["bobot_nilai_min"],
      "bobot_nilai_maks" => $_POST["bobot_nilai_maks"],
      "tgl_mulai_efektif" => $_POST["tgl_mulai_efektif"],
       "berlaku_angkatan" => $_POST["berlaku_angkatan"],
      "tgl_akhir_efektif" => $_POST["tgl_akhir_efektif"],
      "kode_jurusan" => $_POST["kode_jurusan"],
   );
   
   
   

    
    
    $up = $db->update("skala_nilai",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>