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
  $data_insert = array();
  $data_error = array();


  $Reader = new SpreadsheetReader("../../../upload/upload_excel/".$_FILES['semester']['name']);

  foreach ($Reader as $key => $val)
  {

    if ($key>0) {

        if ($val[0]!='') {
            $nim = trimmer($val[0]);
            //if nim not exist
            $get_detail_mhs = $db->fetch_single_row('mahasiswa','nim',$nim);
            if ($get_detail_mhs==false) {
                $error_count++;
                $error[] = "$val[0] Nim Tidak ditemukan di Data Mahasiswa";
                $data_error[] = array(
                    $val[0],
                    $val[1],
                    $val[2],
                    $val[3],
/*                    $val[4],
                    $val[5],*/
                    "Nim Tidak ditemukan di Data Mahasiswa"
                );
            } else {
                $id_kat_detil = $db->fetch_custom_single('select id from keu_tagihan where kode_prodi=? and kode_tagihan=? and berlaku_angkatan=?',
                    array(
                        'prodi' => $get_detail_mhs->jur_kode,
                        'tagihan' => trimmer($val[1]),
                        'angkatan' => $get_detail_mhs->mulai_smt
                    )
                  );
                if ($id_kat_detil==false) {
                  $error_count++;
                  $error[] = $val[0]." Tagihan untuk Angkatan dan Prodi $get_detail_mhs->jur_kode Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Tagihan -> Tagihan Prodi";
                  $data_error[] = array(
                      $val[0],
                      $val[1],
                      $val[2],
                      $val[3],
/*                      $val[4],
                      $val[5],*/
                      "Tagihan untuk Angkatan dan Prodi $get_detail_mhs->jur_kode Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Setting Tagihan Prodi"
                  );
                } else {
                    //check if tagihan is exist
                    $check_exist = $db->check_exist("keu_tagihan_mahasiswa",array(
                        'nim' => trimmer($val[0]),
                        'id_tagihan_prodi' => $id_kat_detil->id,
                        'periode' => trimmer($val[2])
                      ));
                      if ($check_exist) {
                          $error_count++;
                          $error[] = $val[0]." Tagihan Mahasiswa di Periode ini sudah ada";
                          $data_error[] = array(
                              $val[0],
                              $val[1],
                              $val[2],
                              $val[3],
/*                              $val[4],
                              $val[5],*/
                              "Tagihan Mahasiswa di Periode ini sudah ada"
                          );
                      } else {
                          $sukses++;
                          $data_insert[] = array(
                              'nim' => trimmer($val[0]),
                              'id_tagihan_prodi' => $id_kat_detil->id,
                              'periode' => trimmer($val[2]),
                              'potongan' => trimmer($val[3]),
/*                              'tanggal_awal' => trimmer($val[4]).' 00:00:00',
                              'tanggal_akhir' => trimmer($val[5]).' 23:59:59',*/
                              'created_date_tagihan_mhs' => date('Y-m-d H:i:s')
                          );
                          $va_nim = substr(trimmer($val[0]), -10);
                          //check exist va
                          $check_va = $db->check_exist('va_bank_jambi',
                            array(
                              'nim' => trimmer($val[0]),
                              'no_va' => '506905300'.$va_nim,
                              'type_transaction' => 4
                            )
                          );
                          if ($check_va==false) {
                            $data_va_mega[] = array(
                              'nim' => trimmer($val[0]),
                              'no_va' => '506905300'.$va_nim,
                              'type_transaction' => 4,
                              'date_created' => date('Y-m-d H:i:s'),
                            );
                          }
                      }

                }
            }


        }

    }

}

if ($error_count>0) {
  include "download_error_import.php";
}
$db2->begin_transaction();
if (!empty($data_insert)) {
    $insert = $db2->insertMulti('keu_tagihan_mahasiswa',$data_insert);
}
if (!empty($data_va_mega)) {
    $insert_mega = $db2->insertMulti('va_bank_jambi',$data_va_mega);
    if ($insert_mega==false) {
      echo $db2->getErrorMessage();
      $db2->rollback();
    }
}
$db2->commit();
unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
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
        $msg .= "</div><br><a href='".base_url()."upload/error_import/error_tagihan_mahasiswa.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
    }

    $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
    $msg .= "</div>";

}
echo $msg;
break;
  case "in":
    
  $get_detail_mhs = $db->fetch_single_row('mahasiswa','nim',$_POST['nim']);
  $id_kat_detil = $db->fetch_custom_single('select id from keu_tagihan where kode_prodi=? and kode_tagihan=? and berlaku_angkatan=?',array('prodi' => $get_detail_mhs->jur_kode,'tagihan' => $_POST['kode_tagihan'],'angkatan' => $get_detail_mhs->mulai_smt));
  if ($id_kat_detil==false) {
    action_response('Maaf Tagihan untuk Angkatan dan Prodi Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Setting Tagihan Prodi');
  }
  $check_exist = $db->check_exist("keu_tagihan_mahasiswa",array('nim' => $_POST['nim'],'id_tagihan_prodi' => $id_kat_detil->id,'periode' => $_POST['periode']));
  if ($check_exist) {
    action_response("Tagihan Mahasiswa di Periode ini sudah ada");
  }
  
  if ($_POST['potongan']==0) {
    $potongan = 0;
  } else {
    $potongan = (int)str_replace(".", "", $_POST['potongan']);
  }
  $data = array(
      "nim" => $_POST["nim"],
      "id_tagihan_prodi" => $id_kat_detil->id,
      "potongan" => $potongan,
/*      "tanggal_awal" => $_POST["tanggal_awal"].' 00:00:00',
      "tanggal_akhir" => $_POST["tanggal_akhir"].' 23:59:59',*/
      "periode" => $_POST["periode"],
  );
  
   
    $in = $db->insert("keu_tagihan_mahasiswa",$data);

    $va_nim = substr($_POST['nim'], -10);

    //check exist va
    $check_va = $db->check_exist(
      'va_bank_jambi',
      array(
        'nim' => $_POST['nim'],
        'no_va' => '506905300'.$va_nim,
        'type_transaction' => 4
      )
    );
    if ($check_va==false) {
      $data_va_mega = array(
        'nim' => $_POST['nim'],
        'no_va' => '506905300'.$va_nim,
        'type_transaction' => 4,
        'date_created' => date('Y-m-d H:i:s'),
      );
      $in = $db->insert("va_bank_jambi",$data_va_mega);
    }

    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("keu_tagihan_mahasiswa","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
case 'aktifkan':
      $data_ids = $_REQUEST["data_ids"];
      $data_id_array = explode(",", $data_ids);
      if(!empty($data_id_array)) {
          foreach($data_id_array as $id) {
            if ($_POST['aksi']=='1') {
              $aktif = '1';
            } else {
              $aktif = '0';
            }
            $array_update[] = array(
              'is_aktif' => $aktif
            );
            $ids[] = $id;
           }
           $db->updateMulti('keu_tagihan_mahasiswa',$array_update,'id',$ids);
      }
      action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("keu_tagihan_mahasiswa","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    $get_detail_mhs = $db->fetch_single_row('mahasiswa','nim',$_POST['nim']);
  $id_kat_detil = $db->fetch_custom_single('select id from keu_tagihan where kode_prodi=? and kode_tagihan=? and berlaku_angkatan=?',array('prodi' => $get_detail_mhs->jur_kode,'tagihan' => $_POST['kode_tagihan'],'angkatan' => $get_detail_mhs->mulai_smt));
  if ($id_kat_detil==false) {
    action_response('Maaf Tagihan untuk Angkatan dan Prodi Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Setting Tagihan Prodi');
  }
    $data = array(
      "id_tagihan_prodi" => $id_kat_detil->id,
      "potongan" => (int)str_replace(".", "", $_POST['potongan']),
      "tanggal_awal" => $_POST["tanggal_awal"].' 00:00:00',
      "tanggal_akhir" => $_POST["tanggal_akhir"].' 23:59:59',
      "updated_date_tagihan_mhs" => date('Y-m-d H:i:s')
    );

    $up = $db->update("keu_tagihan_mahasiswa",$data,"id",$_POST["id"]);
    $va_nim = substr($_POST['nim'], -10);
    //check exist va
    $check_va = $db->check_exist('va_bank_jambi',
      array(
        'nim' => $_POST['nim'],
        'no_va' => '506905300'.$va_nim,
        'type_transaction' => 4
      )
    );
    if ($check_va==false) {
      $data_va_mega = array(
        'nim' => $_POST['nim'],
        'no_va' => '506905300'.$va_nim,
        'type_transaction' => 4,
        'date_created' => date('Y-m-d H:i:s'),
      );
      $in = $db->insert("va_bank_jambi",$data_va_mega);
    }
    
    action_response($db->getErrorMessage());
    break;
    case 'up_tanggal_massal':
      $data_ids = $_REQUEST["id"];
      $data_id_array = explode(",", $data_ids);
      if(!empty($data_id_array)) {
          foreach($data_id_array as $id) {
            $array_update[] = array(
              'tanggal_awal' => $_POST['tanggal_awal'].' 00:00:00',
              'tanggal_akhir' => $_POST['tanggal_akhir'].' 23:59:59'
            );
            $data_id_update[] = $id;
           }
           $db->updateMulti('keu_tagihan_mahasiswa',$array_update,'id',$data_id_update);
      }
      action_response($db->getErrorMessage());
      break;
  default:
    # code...
    break;
}

?>