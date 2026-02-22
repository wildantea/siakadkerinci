<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true);
require('../../inc/lib/SpreadsheetReader.php');
switch ($_GET["act"]) {
   case 'import':
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
          $check_nim_exist = $db->check_exist("mahasiswa",array('nim' => filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)));

          if ($check_nim_exist) {
            $get_detail_mhs = $db->fetch_single_row('mahasiswa','nim',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH));
            $id_kat_detil = $db->fetch_custom_single('select id from keu_tagihan where kode_prodi=? and kode_tagihan=? and berlaku_angkatan=?',array('prodi' => $get_detail_mhs->jur_kode,'tagihan' => filter_var($val[1], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH),'angkatan' => $get_detail_mhs->mulai_smt));

            if ($id_kat_detil==false) {
              $error_count++;
              $error[] = $val[0]." Tagihan untuk Angkatan dan Prodi $get_detail_mhs->jur_kode Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Setting Tagihan Prodi";
            } else {
              $check_exist = $db->check_exist("keu_tagihan_mahasiswa",array('nim' => filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH),'id_tagihan_prodi' => $id_kat_detil->id,'periode' => filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)));
                if ($check_exist) {
                $error_count++;
                $error[] = $val[0]." Tagihan Mahasiswa di Periode ini sudah ada";
                } else {

                $sukses++;
                $values .= '("'.
                preg_replace( '/[^[:print:]]/', '',filter_var($val[0], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH)).'","'.
                $id_kat_detil->id.'","'.
                filter_var($val[2], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH).'"),';
                  }

            }

          } else {
              $error_count++;
              $error[] = $val[0]." Nim Tidak ditemukan di Data Mahasiswa";
          }

      }

    }

      }

if ($values!="") {
  $values = rtrim($values,",");

  $query = "insert into keu_tagihan_mahasiswa (nim,id_tagihan_prodi,periode) values ".$values;
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
            <font color=\"#3c763d\">".$sukses." data Tagihan baru berhasil di import</font><br />
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

  $get_detail_mhs = $db->fetch_single_row('mahasiswa','nim',$_POST['nim']);
  //echo "$get_detail_mhs->mulai_smt"; 
  $id_kat_detil = $db->fetch_custom_single('select id,nominal_tagihan from keu_tagihan where kode_prodi=? and kode_tagihan=? and berlaku_angkatan=?',array('prodi' => $get_detail_mhs->jur_kode,'tagihan' => $_POST['kode_tagihan'],'angkatan' => $get_detail_mhs->mulai_smt));
  if ($id_kat_detil==false) {
    action_response('Maaf Tagihan untuk Angkatan dan Prodi Mahasiswa ini belum di Setting, Silakan tambahkan di menu Pembayaran -> Setting Tagihan Prodi');
  }
  $check_exist = $db->check_exist("keu_tagihan_mahasiswa",
                                    array('nim' => $_POST['nim'],
                                          'id_tagihan_prodi' => $id_kat_detil->id,
                                          'periode' => $_POST['periode'])
                                  );
  if ($check_exist) {
    action_response("Tagihan Mahasiswa di Periode ini sudah ada");
  }

  $data = array(
      "nim" => $_POST["nim"],
      "id_tagihan_prodi" => $id_kat_detil->id,
      "periode" => $_POST["periode"],
  );
  //return format;
/*  stdClass Object
  (
    [status] =>
    [responseCode] => 13
    [errDesc] => Data Customer Sudah Ada
    [data] => stdClass Object
    (
      [institutionCode] => J104408
      [brivaNo] => 77777
      [custCode] => 1122334455660
      [nama] => Septri Nur
      [amount] => 100
      [keterangan] => SPP
      [expiredDate] => 2017-09-10 09:57:26
    )

  )*/
//  print_r($_POST);
//  die();
  // $status_briva = create_briva($_POST['nim'],$_POST['nama'],$_POST["periode"],$_POST['kode_tagihan']);
  // if ($status_briva->status=='1') {
  //    $data['briva_no'] = $status_briva->data->custCode;
  //    $data['jumlah']   = $status_briva->data->amount;
  //    $data['ket']      = $status_briva->data->keterangan;
  //    $data['exp_tagihan'] = $status_briva->data->expiredDate;
  //    $data['date_created'] = date("Y-m-d H:i:s");
  //    $in = $db->insert("keu_tagihan_mahasiswa",$data);
  //    action_response($db->getErrorMessage());
  // }else{
  //    action_response("Create briva ".$_POST['nim']." gagal, ".$status_briva->errDesc);
  // }

    // $data['briva_no'] = $status_briva->data->custCode;
     $data['jumlah']   = $id_kat_detil->nominal_tagihan;
   //  $data['ket']      = $status_briva->data->keterangan;
    // $data['exp_tagihan'] = $status_briva->data->expiredDate;
     $data['date_created'] = date("Y-m-d H:i:s");
     $in = $db->insert("keu_tagihan_mahasiswa",$data);
     action_response($db->getErrorMessage()); 
 


    break;
  case "delete":


    $mhs=$db->fetch_single_row("keu_tagihan_mahasiswa","id",$_GET['id']);
    $briva_no = $mhs->briva_no;
    //$hapus = delete_briva($briva_no);
        $db->delete("keu_tagihan_mahasiswa","id",$_GET["id"]);
        action_response($db->getErrorMessage());
        exit();
    if ($hapus->status!='1' && $hapus->status=='14') {
      action_response("No briva ".$briva_no." tidak ditemukan");
    }else{
        $db->delete("keu_tagihan_mahasiswa","id",$_GET["id"]);
        action_response($db->getErrorMessage());
    }

    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
         $mhs=$db->fetch_single_row("keu_tagihan_mahasiswa","id",$id);
         $briva_no = $mhs->nim.$mhs->id_tagihan_prodi;
         //$hapus = delete_briva($briva_no);
     /*    if ($hapus->status!='1' && $hapus->status=='14') {
          action_response("No briva ".$briva_no." tidak ditemukan");
        }*/
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

    if ($id_kat_detil->id!==$_POST['id_tagihan_prodi']) {
     $nama = $db->fetch_single_row("mahasiswa","nim",$_POST['nim'])->nama;
     $tagihan = $db->fetch_single_row("keu_tagihan_mahasiswa","id",$_POST['id']);
    // echo $tagihan->id_tagihan_prodi;
     $update_briva = update_briva($_POST['nim'],$nama,$_POST["periode"],$_POST['kode_tagihan'],$tagihan->briva_no);
   // print_r($update_briva);
    if ($update_briva->status=='1') {
       $data = array(
        "nim" => $_POST["nim"],
        "id_tagihan_prodi" => $id_kat_detil->id,
        "periode" => $_POST["periode"]
      );
      // print_r($data);
       $up = $db->update("keu_tagihan_mahasiswa",$data,"id",$_POST["id"]);
       action_response($db->getErrorMessage());
     }else{
        action_response($update_briva->errDesc);
     }

   }

   // echo $id_kat_detil->id." ".$_POST['id_tagihan_prodi'];



    break;
  default:
    # code...
    break;
}

?>
