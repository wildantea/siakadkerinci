<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'input_nilai':
    $data = array(
      "nilai_ujian" => $_POST["nilai_ujian"],
   );
   
    $up = $db->update("tb_data_pendaftaran_penguji",$data,"id_pendaftaran_penguji",$_POST["id"]);
    //check if all penguji has given grade
    $jml_penguji = 0;
    $sudah_nilai = 0;
    $nilai_akhir = 0;
    $is_nilai_complete = $db->query("select * from tb_data_pendaftaran_penguji where id_jadwal_ujian=?",array('id_jadwal_ujian' => $_POST['id_jadwal']));
    foreach ($is_nilai_complete as $nilai_uji) {
      if ($nilai_uji->nilai_ujian!="") {
        $sudah_nilai++;
        $nilai_akhir+=$nilai_uji->nilai_ujian;
      }
      $jml_penguji++;
    }

    $nilai_akhir = round($nilai_akhir/$jml_penguji,2);

    if ($sudah_nilai==$jml_penguji) {
        $mhs_data = $db2->fetchCustomSingle("select * from view_simple_mhs_data where nim='".$_POST['nim']."'");
        $kode_jurusan= $mhs_data->jur_kode;
        $bobot= $nilai_akhir;
        $berlaku_angkatan=$mhs_data->mulai_smt;
        $where_berlaku = "";
        if ($berlaku_angkatan>=20202) {
              $where_berlaku = "and berlaku_angkatan='".$berlaku_angkatan."'"; 
        } else{
              $where_berlaku = "and berlaku_angkatan is null"; 
        }
        $data_update_nilai = array();
        $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku and $bobot >= bobot_nilai_min and $bobot <= bobot_nilai_maks",array('kode_jurusan' => $kode_jurusan));
        //echo "select * from skala_nilai where kode_jurusan=? $where_berlaku"; 
               if ($skala_nilai->rowCount()>0) {
                    foreach ($skala_nilai as $skala) {
                        $huruf =  $skala->nilai_huruf;
                          $data_update_nilai = array(
                              'nilai_angka' => $nilai_akhir,
                              'nilai_huruf' => $huruf,
                              'bobot' => $skala->nilai_indeks,
                              'tgl_perubahan_nilai' => date('Y-m-d H:i:s'),
                              'pengubah_nilai' =>  $_SESSION['nama']
                          );
                    }
               }


        if (!empty($data_update_nilai)) {
            //matkul syarat
             $mk_id = $db2->fetchCustomSingle("select matkul_syarat from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting='".$_POST['id_jenis_pendaftaran_setting']."' and kode_jur='$mhs_data->jur_kode'");
             if ($mk_id) {
               
                 //cari krs mahasiswa
                 $get_krs_mhs = $db2->fetchCustomSingle("select id_krs_detail from krs_detail where kode_mk in($mk_id->matkul_syarat) and id_semester='".$_POST['id_semester']."' and nim='$mhs_data->nim'");
                 if ($get_krs_mhs) {
                    $db->update('krs_detail',$data_update_nilai,'id_krs_detail',$get_krs_mhs->id_krs_detail);
                    update_akm($mhs_data->nim);
                 }
             }
        }

    }
    action_response($db->getErrorMessage());
    break;
  case "in":
    
  
  
  
  $data = array(
      "tanggal_ujian" => $_POST["tanggal_ujian"],
  );
  
  
  
   
    $in = $db->insert("tb_data_pendaftaran_jadwal_ujian",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_data_pendaftaran_jadwal_ujian","id_jadwal_ujian",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_pendaftaran_jadwal_ujian","id_jadwal_ujian",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "tanggal_ujian" => $_POST["tanggal_ujian"],
   );
   
   
   

    
    
    $up = $db->update("tb_data_pendaftaran_jadwal_ujian",$data,"id_jadwal_ujian",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>