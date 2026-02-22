<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "afirmasi_krs":
   $id_tagihan = $_POST['id_tagihan'];
   if ($_POST['aff']=='1') {
           $pem = $db->fetch_custom_single('select ktm.periode, kt.kode_prodi,kt.nominal_tagihan,ktm.id 
          from keu_tagihan_mahasiswa ktm  inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
          where ktm.id=?',array('id' => $id_tagihan));
         $urutan_bayar_prodi = $db->fetch_custom_single("select kbm.urutan_bayar_prodi from keu_bayar_mahasiswa kbm 
          inner join keu_tagihan_mahasiswa ktm on kbm.id_keu_tagihan_mhs=ktm.id
          inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
          where periode='".$pem->periode."' and kode_prodi='$pem->kode_prodi'
          order by urutan_bayar_prodi desc limit 1");
         if ($urutan_bayar_prodi) {
          $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
          if ($urutan<10) {
            $no_kwitansi = $pem->periode.$pem->kode_prodi.'0'.($urutan+1);
          } else {
            $no_kwitansi = $pem->periode.$pem->kode_prodi.($urutan+1);
          }
        } else {
          $no_kwitansi = $pem->periode.$pem->kode_prodi.'01';
          $urutan = 1;
        }
        $rand_time = mt_rand(0,23).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT);
         $bank = $db->fetch_custom_single("SELECT * FROM keu_bank LIMIT 1")->kode_bank;
         $data = array(
            'id_keu_tagihan_mhs' => $pem->id,
            'tgl_bayar' => date('Y-m-d '.$rand_time),
            'tgl_validasi' => date('Y-m-d H:m:s'),
            'created_by' => $_SESSION['username'],
            'nominal_bayar' => '0',
            'no_kwitansi' => $no_kwitansi,
            'urutan_bayar_prodi' => ($urutan+1),
            'id_bank' => $bank,
            'afirmasi' =>1
          );

          $ada = $db->query("select * from keu_bayar_mahasiswa where id_keu_tagihan_mhs='$id_tagihan' ");
          if ($ada->rowCount()==0) {
             $db->insert("keu_bayar_mahasiswa",$data);
          }
          action_response($db->getErrorMessage());
   }else{
     $db->query("delete from keu_bayar_mahasiswa where id_keu_tagihan_mhs='$id_tagihan' ");
   }
   
    break;

  case "get_nominal":
  $q=$db->query("SELECT t.nominal_tagihan FROM keu_tagihan_mahasiswa m JOIN keu_tagihan t
    ON t.id=m.id_tagihan_prodi WHERE m.id='".$_POST['id_tagihan']."'");
  foreach ($q as $k) {
   echo $k->nominal_tagihan;
 }
 break;

 case "in":
   //echo "<pre>";
 $nim=$_POST['nim'];
 $q=$db->query("SELECT js.jns_semester,js.nm_singkat,s.id_semester,s.tahun FROM keu_tagihan_mahasiswa m
  JOIN semester_ref s ON s.id_semester=m.periode
  JOIN jenis_semester js ON js.id_jns_semester=s.id_jns_semester
  WHERE m.nim='$nim' GROUP BY m.periode");

 $jml_tagihan_all=0;
 $jml_sudah=0;
 foreach ($q as $k) {

  $det_tag = $db->query("SELECT jt.syarat_krs, t.id, m.nama, m.nim, jt.nama_tagihan,ta.nominal_tagihan FROM keu_tagihan_mahasiswa t 
    JOIN mahasiswa m ON m.nim=t.nim
    JOIN keu_tagihan ta ON ta.id=t.id_tagihan_prodi
    JOIN keu_jenis_tagihan jt ON jt.kode_tagihan=ta.kode_tagihan
    WHERE t.nim='$nim' AND t.periode='$k->id_semester'");
  foreach ($det_tag as $d) {
   $pem = $db->fetch_custom_single('select ktm.periode, kt.kode_prodi,kt.nominal_tagihan,ktm.id 
    from keu_tagihan_mahasiswa ktm  inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
    where ktm.id=?',array('id' => $d->id));
   $urutan_bayar_prodi = $db->fetch_custom_single("select kbm.urutan_bayar_prodi from keu_bayar_mahasiswa kbm 
    inner join keu_tagihan_mahasiswa ktm on kbm.id_keu_tagihan_mhs=ktm.id
    inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
    where periode='".$pem->periode."' and kode_prodi='$pem->kode_prodi'
    order by urutan_bayar_prodi desc limit 1");
   if ($urutan_bayar_prodi) {
    $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
    if ($urutan<10) {
      $no_kwitansi = $pem->periode.$pem->kode_prodi.'0'.($urutan+1);
    } else {
      $no_kwitansi = $pem->periode.$pem->kode_prodi.($urutan+1);
    }
  } else {
    $no_kwitansi = $pem->periode.$pem->kode_prodi.'01';
    $urutan = 1;
  }
  $rand_time = mt_rand(0,23).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT);
  if (array_key_exists('krs_boleh-'.$d->id, $_POST)) {

    $data = array(
      'id_keu_tagihan_mhs' => $pem->id,
      'tgl_bayar' => date('Y-m-d '.$rand_time),
      'tgl_validasi' => date('Y-m-d H:m:s'),
      'created_by' => $_SESSION['username'],
      'nominal_bayar' => '0',
      'no_kwitansi' => $no_kwitansi,
      'urutan_bayar_prodi' => ($urutan+1),
      'id_bank' => $_POST['bank'],
      'afirmasi' =>1
    );
          //  print_r($data);
    $ada = $db->query("select * from keu_bayar_mahasiswa where id_keu_tagihan_mhs='$d->id' ");
    if ($ada->rowCount()==0) {
     $db->insert("keu_bayar_mahasiswa",$data);
    }
  }
  if (array_key_exists('cek-'.$d->id, $_POST)) {
    $qj=$db->query("SELECT SUM(c.jml_bayar) as jml FROM keu_cicilan c  WHERE c.id_tagihan_mhs='$d->id'");
    $tot_cicilan=0;
    foreach ($qj as $kjm) {
     $tot_cicilan=$kjm->jml;
   }
   if (($tot_cicilan+(int)$_POST['data-'.$d->id]) >= (int)$pem->nominal_tagihan) {
     $data = array(
      'id_keu_tagihan_mhs' => $pem->id,
      'tgl_bayar' => $_POST['tgl_bayar'].date(" H:i:s"),
      'tgl_validasi' => date('Y-m-d H:m:s'),
      'created_by' => $_SESSION['username'],
      'nominal_bayar' => $pem->nominal_tagihan,
      'no_kwitansi' => $no_kwitansi,
      'urutan_bayar_prodi' => ($urutan+1),
      'id_bank' => $_POST['bank'],
      'afirmasi' =>0
    );
              //  print_r($data);
     $db->insert("keu_bayar_mahasiswa",$data);
   }

   $data_cicilan = array('id_tagihan_mhs' => $_POST['ket_id-'.$d->id],
    'jml_bayar' => $_POST['data-'.$d->id] ,
    'tgl_bayar' => $_POST['tgl_bayar'].date(" H:i:s"),
    'validator' => $_SESSION['username']);
              //print_r($data_cicilan);

   $db->insert("keu_cicilan",$data_cicilan);
 }

}

}
action_response($db->getErrorMessage());
      //print_r($_POST);
break;
  /*case "in":

  foreach ($_POST['id_tagihan'] as $tg) {
    $pem = $db->fetch_custom_single('select kt.kode_prodi,kt.nominal_tagihan,ktm.id from keu_tagihan_mahasiswa ktm
      inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
      where ktm.id=?',array('id' => $tg));
    $urutan_bayar_prodi = $db->fetch_custom_single("select kbm.urutan_bayar_prodi from keu_bayar_mahasiswa kbm 
      inner join keu_tagihan_mahasiswa ktm on kbm.id_keu_tagihan_mhs=ktm.id
      inner join keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
      where periode='".$_POST['periode']."' and kode_prodi='$pem->kode_prodi'
      order by urutan_bayar_prodi desc limit 1");
      if ($urutan_bayar_prodi) {
        $urutan = $urutan_bayar_prodi->urutan_bayar_prodi;
        if ($urutan<10) {
          $no_kwitansi = $_POST['periode'].$pem->kode_prodi.'0'.($urutan+1);
        } else {
          $no_kwitansi = $_POST['periode'].$pem->kode_prodi.($urutan+1);
        }
      } else {
        $no_kwitansi = $_POST['periode'].$pem->kode_prodi.'01';
        $urutan = 1;
      }
      $rand_time = mt_rand(0,23).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT);
      $data = array(
        'id_keu_tagihan_mhs' => $pem->id,
        'tgl_bayar' => date('Y-m-d '.$rand_time),
        'tgl_validasi' => date('Y-m-d H:m:s'),
        'created_by' => $_SESSION['username'],
        'nominal_bayar' => $pem->nominal_tagihan,
        'no_kwitansi' => $no_kwitansi,
        'urutan_bayar_prodi' => ($urutan+1),
        'id_bank' => $_POST['bank']
         );
     // $in = $db->insert("keu_bayar_mahasiswa",$data);
  }
    
   // action_response($db->getErrorMessage());
  break;*/
  case "delete":



  $db->delete("keu_bayar_mahasiswa","id",$_GET["id"]);
  action_response($db->getErrorMessage());
  break;
  case "del_massal":
  $data_ids = $_REQUEST["data_ids"];
  $data_id_array = explode(",", $data_ids);
  if(!empty($data_id_array)) {
    foreach($data_id_array as $id) {
      $db->delete("keu_bayar_mahasiswa","id",$id);
    }
  }
  action_response($db->getErrorMessage());
  break;
  case "up":

  $data = array(
    "id_keu_tagihan_mhs" => $_POST["id_keu_tagihan_mhs"],
    "nominal_bayar" => $_POST["nominal_bayar"],
  );






  $up = $db->update("keu_bayar_mahasiswa",$data,"id",$_POST["id"]);

  action_response($db->getErrorMessage());
  break;
  default:
    # code...
  break;
}

?>