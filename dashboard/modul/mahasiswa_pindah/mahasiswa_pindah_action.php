<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {

  case "simpan_data":
   if ($_POST['ket']=='nilai') {
    $exp_nilai = explode("#", $_POST['val']);
     $nilai_huruf = $exp_nilai[0];
     $bobot = $exp_nilai[1];
     $db->query("update konversi_matkul set nilai='$nilai_huruf' where id='".$_POST['id']."' ");
     $db->query("update konversi_matkul set bobot='$bobot' where id='".$_POST['id']."'");

   } else {
    $db->query("update konversi_matkul set ".$_POST['ket']."='".$_POST['val']."' where id='".$_POST['id']."' ");
   }
   
    echo $db->getErrorMessage();
  // echo "update konversi_matkul set ".$_POST['ket']."='".$_POST['val']."' where id_pindah='".$_POST['id']."' ";
    break;

  case "minus_baris":
   $db->query("delete from konversi_matkul where id='".$_POST['id']."' "); 
    break;

  case "add_baris":
    $data_kon = array('id_pindah' => $_POST['id'] , 
                                                        'date_created' => date("Y-m-d H:i:s"),
                                                        'date_updated' => date("Y-m-d H:i:s"));
                                      $db->insert("konversi_matkul",$data_kon);
                                      $id_konversi = $db->last_insert_id();
                                       $qs = $db->fetch_all("view_semester");
                                            $sem = "<select class='form-control' onchange='set_semester(this.value,$id_konversi)' name='sem_$id_konversi'><option value=''>Pilih Semester</option> ";
                                            foreach ($qs as $ks) {                                            
                                                 $sem.="<option value='$ks->id_semester'>$ks->tahun_akademik</option>";
                                               }
                                               $sem .="</select>";
                                            $val_kode = "";
                                            
                                           ?>
                                            <tr id="baris_matkul_<?= $id_konversi ?>">
                                              <td>
                                                <button class="btn btn-success" onclick="plus_baris('<?= $_POST['id'] ?>')"><i class="fa fa-plus"></i></button>
                                                <button class="btn btn-danger" onclick="minus_baris('<?= $id_konversi ?>')"><i class="fa fa-minus"></i></button>
                                              </td>
                                              <td>
                                                <input type="text" name="kode_lama[]" onkeyup="simpan_data('kode_lama','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="matkul_lama[]" onkeyup="simpan_data('nama_mk','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="sks_lama[]" onkeyup="simpan_data('sks','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                               
                                                <input type="text" name="nilai_angka[]" data-id="<?=$id_konversi;?>" onkeyup="simpan_data('nilai_angka','<?= $id_konversi ?>',this.value)" class="form-control nilai_angka">
                                              </td>
                                              <td>
                                                 <select class="form-control nilai_huruf" onchange="simpan_data('nilai','<?= $id_konversi ?>',this.value)">
                                                  <option value="">Pilih Nilai</option>
                                                <?php
                                                $qn = $db->query("select nilai_huruf,nilai_indeks from skala_nilai group by nilai_huruf");
                                                foreach ($qn as $kn) {
                                                   echo "<option value='$kn->nilai_huruf'>$kn->nilai_huruf</option>";
                                                }
                                                ?> 
                                                </select>
                                               <!--  <input type="text" name="nilai_lama[]" onkeyup="simpan_data('nilai','<?= $id_konversi ?>',this.value)" class="form-control"> -->
                                              </td>
                                              <td><input type='text' id="baris_<?= $id_konversi ?>"  onkeyup='cek_matkul("<?= $id_konversi ?>")' class='form-control cari_matkul' placeholder='Input Kode, Mata Kuliah'></td>
                                             
                                              </tr>
                                            <?php 
                                          
    break; 

  case "simpan_semester_konversi":
    $id = $_POST['id'];
   $semester = $_POST['val'];
   $db->query("update konversi_matkul set semester='$semester' where id='$id' ");  
    break;

  case "simpan_matkul_konversi":
   
   $id = $_POST['id'];
   $kode_mk = $_POST['kode_mk'];
   $q = $db->query("select id_pindah from konversi_matkul where id='$id'  ");
   foreach ($q as $k) {
      $id_pindah = $k->id_pindah;
   }
   $db->query("update konversi_matkul set kode_baru='$kode_mk' where id='$id' "); 
   echo $db->getErrorMessage();
   get_matkul_konversi($id_pindah);
    break;

  case "cari_matkul":
   $key = $_POST['term'];
   $q = $db->query("select j.nama_jur, nama_mk,id_matkul,kode_mk from matkul join kurikulum k on k.kur_id=matkul.kur_id
   join jurusan j on k.kode_jur=j.kode_jur where nama_mk like '%$key%' or kode_mk like '%$key%' ");
   $res = array();
   foreach ($q as $k) {
      $h['id_matkul'] = $k->id_matkul;
      $h['kode_mk'] = $k->kode_mk;
      $h['nama_mk'] = $k->nama_mk;
      $h['nama_jur'] = $k->nama_jur; 
      $res[] = $h;
   }
   echo json_encode($res);
    break;
  case "in":
    

  
  $data = array(
      "nim_lama" => $_POST["nim_lama"],
      "nama_mhs" => $_POST["nama_mhs"],
      "nim_baru" => $_POST["nim_baru"],
      "jenis_pindah" => $_POST["jenis_pindah"],
      "kampus_baru" => $_POST["kampus_baru"],
      "jurusan_lama" => $_POST["jurusan_lama"],
      "jurusan_baru" => $_POST["jurusan_baru"],
       "angkatan_lama" => $_POST["angkatan_lama"],
      "angkatan_baru" => $_POST["angkatan_baru"],
      "tgl_pindah" => $_POST["tgl_pindah"],
      "no_sk" => $_POST["no_sk"],
  );

  if ($_POST['jenis_pindah']=='eksternal') {
    $data['kode_pt_asal'] = $_POST['kode_pt'];
      $pt = $db->fetch_custom_single("select * from satuan_pendidikan where npsn='".$_POST['kode_pt']."'");
    $data['kampus_lama'] = $pt->nm_lemb ;

      $pt = $db->fetch_custom_single("select * from satuan_pendidikan where npsn='".$_POST['kode_pt_baru']."'");
    $data['kampus_baru'] = $pt->nm_lemb ;
  } else {
    $data['kode_pt_asal'] = '' ;
    $data['kampus_lama'] = '' ;
  }

  if (!$db->check_exist('mahasiswa',array('nim' => $_POST["nim_baru"]))) {
     $datam = array('nim'      => $_POST["nim_baru"] , 
                    'jur_kode' => $_POST["jurusan_baru"],
                    'nama'     => $_POST["nama_mhs"],
                    'mulai_smt' => $_POST["angkatan_baru"],   
                    'id_jns_daftar' => '2'
                  );
     $db->insert("mahasiswa",$datam);
  }else{
       $db->query("update mahasiswa set nim='".$_POST["nim_baru"]."', jur_kode='".$_POST["jurusan_baru"]."' where nim='".$_POST["nim_lama"]."' ");
     } 

  // if ($_POST['jenis_pindah']=='exsternal') {
    
  // }
  
  
  
   
    $in = $db->insert("mhs_pindah",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("mhs_pindah","id",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mhs_pindah","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim_lama" => $_POST["nim_lama"],
      "nama_mhs" => $_POST["nama_mhs"],
      "nim_baru" => $_POST["nim_baru"],
      "jenis_pindah" => $_POST["jenis_pindah"],
      "jurusan_lama" => $_POST["jurusan_lama"],
      "angkatan_lama" => $_POST["angkatan_lama"],
      "angkatan_baru" => $_POST["angkatan_baru"],
      "jurusan_baru" => $_POST["jurusan_baru"],
      "tgl_pindah" => $_POST["tgl_pindah"],
      "no_sk" => $_POST["no_sk"],
   ); 

  if ($_POST['jenis_pindah']=='eksternal') {
    $data['kode_pt_asal'] = $_POST['kode_pt'];
      $pt = $db->fetch_custom_single("select * from satuan_pendidikan where npsn='".$_POST['kode_pt']."'");
    $data['kampus_lama'] = $pt->nm_lemb ;

      $pt = $db->fetch_custom_single("select * from satuan_pendidikan where npsn='".$_POST['kode_pt_baru']."'");
    $data['kampus_baru'] = $pt->nm_lemb ;
  } else {
    $data['kode_pt_asal'] = '' ;
    $data['kampus_lama'] = '' ;
  }


   if (!$db->check_exist('mahasiswa',array('nim' => $_POST["nim_baru"]))) {
     $datam = array('nim'      => $_POST["nim_baru"] , 
                    'jur_kode' => $_POST["jurusan_baru"],
                    'nama'     => $_POST["nama_mhs"],
                    'mulai_smt' => $_POST['angkatan_baru'],  
                    'id_jns_daftar' => '2'
                  );
     $db->insert("mahasiswa",$datam);
  }

   $db->query("update mahasiswa set nim='".$_POST["nim_baru"]."', mulai_smt='".$_POST['angkatan_baru']."', jur_kode='".$_POST["jurusan_baru"]."' where nim='".$_POST["nim_baru"]."' ");
   $db->query("update sys_users set username='".$_POST["nim_baru"]."' where username='".$_POST["nim_lama"]."' "); 
   
   
   

    
    
    $up = $db->update("mhs_pindah",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>