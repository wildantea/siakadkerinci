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

  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);

  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
        $nip = filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
        $nip = str_replace(" ", "", $nip);
        $nip = preg_replace( '/[^[:print:]]/', '',$nip);
        $nip = trim($nip);
        $nidn = filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
        $nidn = str_replace(" ", "", $nidn);
        $nidn = preg_replace( '/[^[:print:]]/', '',$nidn);
        $nidn = trim($nidn);
          $check = $db->check_exist('dosen',array('nip' => $nip));
          if ($check==true) {
            $error_count++;
            $error[] = "Nomor Induk Pegawai ".$val[0]." Sudah Ada";
          } else {
            $sukses++;
            $kode_jur = filter_var($val[5], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
      $values .= '("'.$nip.'","'.$nidn.'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[4], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.$kode_jur.'","'.
preg_replace( '/[^[:print:]]/', '',filter_var($val[6], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'"),';
        }

      }

    }

  }

if ($values!="") {
  $values = rtrim($values,",");

  $query = "insert into dosen (nip,nidn,nama_dosen,email,no_hp,kode_jur,kode_rumpun) values ".$values;
  //echo $query;
  $db->query($query);
  echo $db->getErrorMessage();
}

          unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Dosen baru berhasil di import</font><br />
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
    
          $nip = $_POST["nip"];
        $nip = str_replace(" ", "", $nip);
        $nip = preg_replace( '/[^[:print:]]/', '',$nip);
        $nip = trim($nip);
  
       $nidn = $_POST["nidn"];
        $nidn = str_replace(" ", "", $nidn);
        $nidn = preg_replace( '/[^[:print:]]/', '',$nidn);
        $nidn = trim($nidn);
  $data = array(
      "nip" => $nip,
      "nidn" => $nidn,
      "nama_dosen" => $_POST["nama_dosen"],
      "email" => $_POST["email"],
      "no_hp" => $_POST["no_hp"],
      "kode_jur" => $_POST["kode_jur"],
  );
  
  
  
   
          if(isset($_POST["aktif"])=="on")
          {
            $aktif = array("aktif"=>"Y");
            $data=array_merge($data,$aktif);
          } else {
            $aktif = array("aktif"=>"N");
            $data=array_merge($data,$aktif);
          }
    $in = $db->insert("dosen",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    $db->delete("dosen","id_dosen",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("dosen","id_dosen",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;

  case 'up_jenis':
  $data = array();
  $jenis = $_POST['id_jenis_dosen'];

  $data_ids = $_REQUEST["id"];
  $data_id_array = explode(",", $data_ids);
  if(!empty($data_id_array)) {
      foreach($data_id_array as $id) {
        $array_update[] = array(
          'id_jenis_dosen' => $jenis
        );
        $ids[] = $id;
       }
       $db->updateMulti('dosen',$array_update,'nip',$ids);
  }
      action_response($db->getErrorMessage());

    break;

  case 'up_status_aktif':
  $data = array();
  if(isset($_POST["aktif"])=="on")
  {
    $status = "Y";
  } else {
    $status = "N";
  }

  $data_ids = $_REQUEST["id"];
  $data_id_array = explode(",", $data_ids);
  if(!empty($data_id_array)) {
      foreach($data_id_array as $id) {
        $array_update[] = array(
          'aktif' => $status
        );
        $ids[] = $id;
       }
       $db->updateMulti('sys_users',$array_update,'username',$ids);
       $db->updateMulti('dosen',$array_update,'nip',$ids);
  }
      action_response($db->getErrorMessage());

    break;
  case "up":
        $nip = $_POST["nip"];
        $nip = str_replace(" ", "", $nip);
        $nip = preg_replace( '/[^[:print:]]/', '',$nip);
        $nip = trim($nip);
  
       $nidn = $_POST["nidn"];
        $nidn = str_replace(" ", "", $nidn);
        $nidn = preg_replace( '/[^[:print:]]/', '',$nidn);
        $nidn = trim($nidn);

   $data = array(
      "nip" => $nip,
      "nidn" => $nidn,
      "nama_dosen" => $_POST["nama_dosen"],
      "email" => $_POST["email"],
      "id_jenis_dosen" => $_POST["id_jenis_dosen"],
      "no_hp" => $_POST["no_hp"],
      "kode_jur" => $_POST["kode_jur"],
      "date_updated" => date('Y-m-d H:i:s')
   );
   
   
  if ($_POST['rumpun']!='all') {
              $kode_rumpun = array("kode_rumpun"=>$_POST['bidang_ilmu']);
            $data=array_merge($data,$kode_rumpun);
  } else {
      $db->query("update dosen set kode_rumpun=NULL where id_dosen=".$_POST['id']);
  }

    
          if(isset($_POST["aktif"])=="on")
          {
            $aktif = array("aktif"=>"Y");
            $data=array_merge($data,$aktif);
            $db->update('sys_users',array('aktif' => 'Y'),'username',$_POST['nip']);
          } else {
            $aktif = array("aktif"=>"N");
            $data=array_merge($data,$aktif);
             $db->update('sys_users',array('aktif' => 'N'),'username',$_POST['nip']);
          }

    
    $up = $db->update("dosen",$data,"id_dosen",$_POST["id"]);

    $update_user = $db->update('sys_users',array('username' => $_POST['nip']),'username',$_POST['old_nip']);
    $update_user = $db->update('dosen_kelas',array('id_dosen' => $_POST['nip']),'id_dosen',$_POST['old_nip']);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>