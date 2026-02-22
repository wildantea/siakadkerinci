<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true); 
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
case 'import':
    if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

      echo "pastikan file yang anda pilih xls|xlsx";
      exit();

  } else {
    move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/biaya/".$_FILES['semester']['name']);
    $semester = array("semester"=>$_FILES["semester"]["name"]);

  }

  $error_count = 0;
  $error = array();
  $sukses = 0;
  $values = "";
  $data_insert = array();
  $data_error = array();


  $Reader = new SpreadsheetReader("../../../upload/biaya/".$_FILES['semester']['name']);

  foreach ($Reader as $key => $val)
  {

    if ($key>0) {

        if ($val[0]!='') {
            $kode_prodi = trimmer($val[0]);

            $check = $db2->checkExist('keu_tagihan', 
              array(
                'kode_prodi' => $kode_prodi,
                'berlaku_angkatan' => trimmer($val[1]),
                'kode_tagihan' => trimmer($val[2])
              ));
            $check_jenis_tagihan = $db2->checkExist('keu_jenis_tagihan', 
              array(
                'kode_tagihan' => trimmer($val[2])
              ));

            if ($check==true) {
                $error_count++;
                $error[] = "Tagihan $val[2] Sudah Ada";
                $data_error[] = array(
                    $val[0],
                    $val[1],
                    $val[2],
                    $val[3],
                    "Tagihan ini Sudah Ada"
                );
                include "download_error_import.php";
            } elseif ($check_jenis_tagihan==false) {
                $error_count++;
                $error[] = "Kode Jenis Tagihan $val[2] tidak ditemukan di data jenis tagihan";
                $data_error[] = array(
                    $val[0],
                    $val[1],
                    $val[2],
                    $val[3],
                    "Kode Jenis tagihan tidak ditemukan"
                );
                include "download_error_import.php";
            } else {
                $sukses++;
                $data_insert[] = array(
                    'kode_prodi' => trimmer($val[0]),
                    'berlaku_angkatan' => trimmer($val[1]),
                    'kode_tagihan' => trimmer($val[2]),
                    'nominal_tagihan' => trimmer($val[3]),
                    'status_aktif' => 1,
                    'date_created_tagihan' => date('Y-m-d H:i:s')
                );
            }

        }

    }

}


if (!empty($data_insert)) {
    $insert = $db2->insertMulti('keu_tagihan',$data_insert);
    if (!$insert) {
      $error_count=1;
      $sukses=0;
      $error[] = $db2->getErrorMessage();
    }
}

unlink("../../../upload/biaya/".$_FILES['semester']['name']);
$msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

if (($sukses>0) || ($error_count>0)) {
    $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <font color=\"#3c763d\">".$sukses." data Tagihan baru berhasil di import</font><br />
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
        $msg .= "</div><br><a href='".base_url()."upload/biaya/error_tagihan_prodi.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
    }

    $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
    $msg .= "</div>";

}
echo $msg;
break;

  case 'imports':
      if (!is_dir("../../../upload/biaya")) {
              mkdir("../../../upload/biaya");
            }


   if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

              echo "pastikan file yang anda pilih xls|xlsx";
              exit();

            } else {
              move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../upload/biaya/".$_FILES['semester']['name']);
              $semester = array("semester"=>$_FILES["semester"]["name"]);

            }

      $error_count = 0;
      $error = array();
      $sukses = 0;
$values = "";

  $Reader = new SpreadsheetReader("../../../upload/biaya/".$_FILES['semester']['name']);

  foreach ($Reader as $key => $val)
  {

 
    if ($key>0) {

      if ($val[0]!='') {
          $check = $db->check_exist('keu_tagihan',array('kode_prodi' => filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH),'berlaku_angkatan' => filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH),'kode_tagihan' => filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)));
          if ($check==true) {
            $error_count++;
            $error[] = $val[0]." Sudah Ada";
          } else {
            $sukses++;
      $values .= '("'.
      preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
      preg_replace( '/[^[:print:]]/', '',filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
            preg_replace( '/[^[:print:]]/', '',filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
      filter_var($val[3], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'"),';
        }

      }

    }

      }

if ($values!="") {
  $values = rtrim($values,",");

  $query = "insert into keu_tagihan (kode_prodi,berlaku_angkatan,kode_tagihan,nominal_tagihan) values ".$values;
  //echo $query;
  $db->query($query);
}

          unlink("../../../upload/biaya/".$_FILES['semester']['name']);
          $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

      if (($sukses>0) || ($error_count>0)) {
        $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
            <font color=\"#3c763d\">".$sukses." data Biaya baru berhasil di import</font><br />
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
  if ($_POST["kode_prodi"]=='all') {
    foreach ($db->query("select j.kode_jur,j.nama_jur,t.id from jurusan j left join keu_tagihan t on
 (t.kode_prodi=j.kode_jur and t.berlaku_angkatan='".$_POST["berlaku_angkatan"]."' 
  and t.kode_tagihan='".$_POST["kode_tagihan"]."')
where t.id is  null ") as $k) {
            $check = $db->check_exist("keu_tagihan",array(
                                      'kode_tagihan' => $_POST['kode_tagihan'],
                                      'berlaku_angkatan' => $_POST['berlaku_angkatan'],
                                      "kode_prodi" => $k->kode_jur)
                                );

        /*if ($check) {
          action_response("Biaya Tagihan untuk angkatan dan jenis biaya ini sudah ada");
        }*/
        $biaya = str_replace(".", "", $_POST['nominal_tagihan']);
        $data = array(
          "kode_prodi" => $k->kode_jur,
          "kode_tagihan" => $_POST["kode_tagihan"],
          "nominal_tagihan" => $biaya,
          "berlaku_angkatan" => $_POST["berlaku_angkatan"],
        );
        // print_r($data);
        $in = $db->insert("keu_tagihan",$data);        
      }
   }else
      {
          $check = $db->check_exist("keu_tagihan",array(
                                      'kode_tagihan' => $_POST['kode_tagihan'],
                                      'berlaku_angkatan' => $_POST['berlaku_angkatan'],
                                      "kode_prodi" => $_POST["kode_prodi"])
                                );
        if ($check) {
          action_response("Biaya Tagihan untuk angkatan dan jenis biaya ini sudah ada");
        }


        $biaya = str_replace(".", "", $_POST['nominal_tagihan']);
        $data = array(
          "kode_prodi" => $_POST["kode_prodi"],
          "kode_tagihan" => $_POST["kode_tagihan"],
          "nominal_tagihan" => $biaya,
          "berlaku_angkatan" => $_POST["berlaku_angkatan"],
          "date_created_tagihan" => date('Y-m-d H:i:s')
        );


        $in = $db->insert("keu_tagihan",$data);
      }
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_tagihan","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_tagihan","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
      $check = $db->query("select * from keu_tagihan where kode_prodi=? and berlaku_angkatan=? and kode_tagihan=? and id!='".$_POST['id']."'",array('kode_prodi' => $_POST['kode_prodi'],'berlaku_angkatan' => $_POST['berlaku_angkatan'],'kode_tagihan' => $_POST['kode_tagihan']));
      if ($check->rowCount()>0) {
        action_response("Biaya Tagihan untuk angkatan dan jenis biaya ini sudah ada");
      }

  $biaya = str_replace(".", "", $_POST['nominal_tagihan']);
   $data = array(
      "kode_prodi" => $_POST["kode_prodi"],
      "kode_tagihan" => $_POST["kode_tagihan"],
      "nominal_tagihan" => $biaya,
      "berlaku_angkatan" => $_POST["berlaku_angkatan"]
   );
   
   
   

    
    
    $up = $db->update("keu_tagihan",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>