<?php
session_start();
include "../../inc/config.php";
session_check();
switch ($_POST["act"]) {
  case "datadiri":
  $tgl_lahir = $_POST['tgl_lahir_tahun'].'-'.$_POST['tgl_lahir_bulan'].'-'.$_POST['tgl_lahir_tanggal'];

  $data = array(
      "nama" => $_POST["nama"],
      "jk" => $_POST["jk"],
      "status_pernikahan" => $_POST["status_pernikahan"],
      "nik" => $_POST["nik"],
      "nisn" => $_POST["nisn"],
      "npwp" => $_POST["npwp"],
      "kewarganegaraan" => $_POST["kewarganegaraan"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $tgl_lahir,
      "id_agama" => $_POST["id_agama"],
      "a_terima_kps" => $_POST["a_terima_kps"],
      "no_kps" => $_POST["no_kps"],
      "id_jns_daftar" => $_POST["id_jns_daftar"],
      "id_pembiayaan" => $_POST["id_pembiayaan"],
      "id_jenis_sekolah" => $_POST["id_jenis_sekolah"],
      "nama_asal_sekolah" => $_POST["nama_asal_sekolah"]
  );
  if (isset($_POST['npsn'])) {
    $data['npsn'] = $_POST["npsn"];
  }

  if ($_POST['kode_pt_asal']!='') {
     //get kode_pt asal
  $pt = $db->fetch_single_row("satuan_pendidikan","id_sp",$_POST['kode_pt_asal']);
  $data['id_pt_asal'] = $pt->id_sp;
  $data['kode_pt_asal'] = $pt->npsn;

  if ($_POST['kode_prodi_asal']!="") {
    $pt_prodi = $db->fetch_single_row("sms","id_sms",$_POST['kode_prodi_asal']);
    $data['id_prodi_asal'] = $pt_prodi->id_sms;
    $data['kode_prodi_asal'] = $pt_prodi->kode_prodi;
  }

  } else {
    $data['id_pt_asal'] = '';
    $data['kode_pt_asal'] = '';
    $data['id_prodi_asal'] = '';
    $data['kode_prodi_asal'] = '';
  }

    $in = $db->update("mahasiswa",$data,'nim',$_SESSION['username']);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case 'alamat':
$data = array(
      "jln" => $_POST["jln"],
      "rt" => $_POST["rt"],
      "rw" => $_POST["rw"],
      "nm_dsn" => $_POST["nm_dsn"],
      "ds_kel" => $_POST["ds_kel"],
      "id_wil" => $_POST["id_wil"],
      "kode_pos" => $_POST["kode_pos"],
      "id_jns_tinggal" => $_POST["id_jns_tinggal"],
      "no_tel_rmh" => $_POST["no_tel_rmh"],
      "no_hp" => $_POST["no_hp"],
      "email" => $_POST["email"]
  );
   
  $in = $db->update("mahasiswa",$data,'nim',$_SESSION['username']);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "orangtua":
 $tgl_lahir_ayah = $_POST['tgl_lahir_tahun_ayah'].'-'.$_POST['tgl_lahir_bulan_ayah'].'-'.$_POST['tgl_lahir_tanggal_ayah'];

 $tgl_lahir_ibu = $_POST['tgl_lahir_tahun_ibu'].'-'.$_POST['tgl_lahir_bulan_ibu'].'-'.$_POST['tgl_lahir_tanggal_ibu'];

 $tgl_lahir_wali = $_POST['tgl_lahir_tahun'].'-'.$_POST['tgl_lahir_bulan'].'-'.$_POST['tgl_lahir_tanggal'];
   $data = array(
      "nik_ayah" => $_POST["nik_ayah"],
      "nm_ayah" => $_POST["nm_ayah"],
      "tgl_lahir_ayah" =>  $tgl_lahir_ayah,
      "id_jenjang_pendidikan_ayah" => $_POST["id_jenjang_pendidikan_ayah"],
      "id_pekerjaan_ayah" => $_POST["id_pekerjaan_ayah"],
      "id_penghasilan_ayah" => $_POST["id_penghasilan_ayah"],
      "nik_ibu_kandung" => $_POST["nik_ibu_kandung"],
      "nm_ibu_kandung" => $_POST["nm_ibu_kandung"],
      "tgl_lahir_ibu" => $tgl_lahir_ibu,
      "id_jenjang_pendidikan_ibu" => $_POST["id_jenjang_pendidikan_ibu"],
      "id_pekerjaan_ibu" => $_POST["id_pekerjaan_ibu"],
      "id_penghasilan_ibu" => $_POST["id_penghasilan_ibu"],
      //wali
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $tgl_lahir_wali,
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
   );


$up = $db->update("mahasiswa",$data,'nim',$_SESSION['username']);
    //var_dump($up);
    
     echo $db->getErrorMessage();
    if ($up==true) {
      echo "good";
    } else {
      echo $db->getErrorMessage();
      //return false;
    }
    break;
    case 'pernyataan':
        $data = array(
          "is_submit_biodata" => $_POST["is_submit_biodata"],
          "date_updated" => date('Y-m-d H:i:s')
        );
       $up = $db->update("mahasiswa",$data,'nim',$_SESSION['username']);
        if ($up==true) {
      echo "good";
    } else {
      echo $db->getErrorMessage();
      //return false;
    }
      break;
    case "wali":
   $tgl_lahir_wali = $_POST['tgl_lahir_tahun'].'-'.$_POST['tgl_lahir_bulan'].'-'.$_POST['tgl_lahir_tanggal'];
   $data = array(
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $tgl_lahir_wali,
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
   );
    
   $up = $db->update("mahasiswa",$data,'nim',$_SESSION['username']);

    //var_dump($up);
    
    if ($up==true) {
      echo "good";
    } else {
      echo $db->getErrorMessage();
      //return false;
    }
  default:
    # code...
    break;
}

?>