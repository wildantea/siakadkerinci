<?php
session_start();
include "../../inc/config.php";
session_check_end();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "jk" => $_POST["jk"],
       "status_pernikahan" => $_POST["status_pernikahan"],
      "nik" => $_POST["nik"],
      "nisn" => $_POST["nisn"],
      "npwp" => $_POST["npwp"],
      "kewarganegaraan" => $_POST["kewarganegaraan"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
      "id_agama" => $_POST["id_agama"],
      "id_wil" => $_POST["id_wil"],
      "jln" => $_POST["jln"],
      "rt" => $_POST["rt"],
      "rw" => $_POST["rw"],
      "nm_dsn" => $_POST["nm_dsn"],
      "ds_kel" => $_POST["ds_kel"],
      "kode_pos" => $_POST["kode_pos"],
      "id_jns_tinggal" => $_POST["id_jns_tinggal"],
      "no_tel_rmh" => $_POST["no_tel_rmh"],
      "no_hp" => $_POST["no_hp"],
      "email" => $_POST["email"],
      "a_terima_kps" => $_POST["a_terima_kps"],
      "no_kps" => $_POST["no_kps"],
      "id_jns_daftar" => $_POST["id_jns_daftar"],
      "nik_ayah" => $_POST["nik_ayah"],
      "nm_ayah" => $_POST["nm_ayah"],
      "tgl_lahir_ayah" => $_POST["tgl_lahir_ayah"],
      "id_jenjang_pendidikan_ayah" => $_POST["id_jenjang_pendidikan_ayah"],
      "id_pekerjaan_ayah" => $_POST["id_pekerjaan_ayah"],
      "id_penghasilan_ayah" => $_POST["id_penghasilan_ayah"],
      "nik_ibu_kandung" => $_POST["nik_ibu_kandung"],
      "nm_ibu_kandung" => $_POST["nm_ibu_kandung"],
      "tgl_lahir_ibu" => $_POST["tgl_lahir_ibu"],
      "id_jenjang_pendidikan_ibu" => $_POST["id_jenjang_pendidikan_ibu"],
      "id_pekerjaan_ibu" => $_POST["id_pekerjaan_ibu"],
      "id_penghasilan_ibu" => $_POST["id_penghasilan_ibu"],
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $_POST["tgl_lahir_wali"],
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
      "dosen_pemb" => $_POST["dosen_pemb"],
  );
  
  
  
   
    $in = $db->insert("mahasiswa",$data);
    
    
    if ($in=true) {
      echo "good";
    } else {
      return false;
    }
    break;
  case "delete":
    
    
    
    $db->delete("mahasiswa","mhs_id",$_GET["id"]);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    break;
  case "up":

   $data = array(
      "nim" => $_POST["nim"],
      "nama" => $_POST["nama"],
      "jk" => $_POST["jk"],
       "status_pernikahan" => $_POST["status_pernikahan"],
      "nik" => $_POST["nik"],
      "nisn" => $_POST["nisn"],
      "npwp" => $_POST["npwp"],
      "kewarganegaraan" => $_POST["kewarganegaraan"],
      "id_jalur_masuk" => $_POST["id_jalur_masuk"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
      "id_agama" => $_POST["id_agama"],
      "id_wil" => $_POST["id_wil"],
      "jln" => $_POST["jln"],
      "rt" => $_POST["rt"],
      "rw" => $_POST["rw"],
      "nm_dsn" => $_POST["nm_dsn"],
      "ds_kel" => $_POST["ds_kel"],
      "kode_pos" => $_POST["kode_pos"],
      "id_jns_tinggal" => $_POST["id_jns_tinggal"],
      "no_hp" => $_POST["no_hp"],
      "no_tel_rmh" => $_POST["no_tel_rmh"],
      "email" => $_POST["email"],
      "a_terima_kps" => $_POST["a_terima_kps"],
      "no_kps" => $_POST["no_kps"],
      "id_jns_daftar" => $_POST["id_jns_daftar"],
      "nik_ayah" => $_POST["nik_ayah"],
      "nm_ayah" => $_POST["nm_ayah"],
      "tgl_lahir_ayah" => $_POST["tgl_lahir_ayah"],
      "id_jenjang_pendidikan_ayah" => $_POST["id_jenjang_pendidikan_ayah"],
      "id_pekerjaan_ayah" => $_POST["id_pekerjaan_ayah"],
      "id_penghasilan_ayah" => $_POST["id_penghasilan_ayah"],
      "nik_ibu_kandung" => $_POST["nik_ibu_kandung"],
      "nm_ibu_kandung" => $_POST["nm_ibu_kandung"],
      "tgl_lahir_ibu" => $_POST["tgl_lahir_ibu"],
      "id_jenjang_pendidikan_ibu" => $_POST["id_jenjang_pendidikan_ibu"],
      "id_pekerjaan_ibu" => $_POST["id_pekerjaan_ibu"],
      "id_penghasilan_ibu" => $_POST["id_penghasilan_ibu"],
      "nm_wali" => $_POST["nm_wali"],
      "tgl_lahir_wali" => $_POST["tgl_lahir_wali"],
      "id_jenjang_pendidikan_wali" => $_POST["id_jenjang_pendidikan_wali"],
      "id_pekerjaan_wali" => $_POST["id_pekerjaan_wali"],
      "id_penghasilan_wali" => $_POST["id_penghasilan_wali"],
      "dosen_pemb" => $_POST["dosen_pemb"],
      "id_jenis_sekolah" => $_POST["id_jenis_sekolah"],
      "nama_asal_sekolah" => $_POST["nama_asal_sekolah"]
   );

   //$data = array_filter($data);
   
   
   

    
    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);

    //var_dump($up);
    
    if ($up==true) {
      echo "good";
    } else {
      echo $db->getErrorMessage();
      //return false;
    }
    break;
  default:
    # code...
    break;
}

?>