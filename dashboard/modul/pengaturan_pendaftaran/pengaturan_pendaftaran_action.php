<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case 'up_template':
    $data = array(
      'isi_template_surat' => base64_encode($_POST['isi_template_surat'])
    );
    $up = $db2->query("update tb_data_pendaftaran_jenis_pengaturan set isi_template_surat='".$_POST['isi_template_surat']."' where id_jenis_pendaftaran_setting='".$_POST["id"]."'");
    action_response($db2->getErrorMessage());
    break;
case "in_dopeg":

        $implode_jurusan = implode(",", $_POST['unit']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and diperuntukan='dopeg' and kode_jur in($implode_jurusan)",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));

        if ($check_jurusan_exist) {
          $jurusan_name = $db2->fetchSingleRow('view_prodi_jenjang','kode_jur',$check_jurusan_exist->kode_jur);
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk jurusan '.$jurusan_name->nama_jurusan.' Sudah ada');
        }
       


        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "diperuntukan" => $_POST['diperuntukan'],
            "id_jenis_akt_mhs" => $_POST["id_jenis_aktivitas_dikti"] 
        );
        

         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_label']));
            $data_attributes[] = $data_attr;
          }
          $data['has_attr'] = 'Y';
          $data['attr_value'] = json_encode($data_attributes);
        }




        if (!empty($_POST['bukti'])) {
          $bukti = implode(",", $_POST["bukti"]);
          $data["bukti"] = $bukti;
        } else {
          $data["bukti"] = "";
        }


          if(isset($_POST["status_aktif"])=="on")
          {
            $data["status_aktif"] = "Y";
          } else {
            $data["status_aktif"] = "N";
          }
    

    $data['id_jenis_pendaftaran'] = $_POST['id_jenis_pendaftaran'];

    $db2->begin_transaction();
    $in = $db2->insert("tb_data_pendaftaran_jenis_pengaturan",$data);

    if ($in) {
        $last_id = $db2->getLastInsertId();
        foreach ($_POST['unit'] as $kode_jur) {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $last_id,
              'kode_jur' => $kode_jur
            );
          

        }

       $insert_for_jurusan = $db2->insertMulti("tb_data_pendaftaran_jenis_pengaturan_prodi",$data_for_jurusan);
        if ($insert_for_jurusan==false) {
            $db2->rollback();
        }

    }

     $db2->commit();


    
    
    action_response($db2->getErrorMessage());
    break;
  case "in":


        $implode_jurusan = implode(",", $_POST['for_jurusan']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and kode_jur in($implode_jurusan)",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));

        if ($check_jurusan_exist) {
          $jurusan_name = $db2->fetchSingleRow('view_prodi_jenjang','kode_jur',$check_jurusan_exist->kode_jur);
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk jurusan '.$jurusan_name->nama_jurusan.' Sudah ada');
        }
       


        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "id_jenis_akt_mhs" => $_POST["id_jenis_aktivitas_dikti"] 
        );

         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_label']));
            $data_attributes[] = $data_attr;
          }
          $data['has_attr'] = 'Y';
          $data['attr_value'] = json_encode($data_attributes);
        }



        if (!empty($_POST['syarat_daftar'])) {
          $syarat_daftar = implode(",", $_POST["syarat_daftar"]);
          $data["syarat_daftar"] = $syarat_daftar;
        } else {
          $data["syarat_daftar"] = "";
        }

        if (!empty($_POST['bukti'])) {
          $bukti = implode(",", $_POST["bukti"]);
          $data["bukti"] = $bukti;
        } else {
          $data["bukti"] = "";
        }

          if(isset($_POST["ada_matkul_syarat"])=="on")
          {
            $data["ada_matkul_syarat"] = "Y";
          } else {
            $data["ada_matkul_syarat"] = "N";
          }

          if(isset($_POST["harus_status_aktif"])=="on")
          {
            $data["harus_status_aktif"] = "Y";
          } else {
            $data["harus_status_aktif"] = "N";
          }

          if(isset($_POST["status_aktif"])=="on")
          {
            $data["status_aktif"] = "Y";
          } else {
            $data["status_aktif"] = "N";
          }
    
          if(isset($_POST["ada_jadwal"])=="on")
          {
            $data["ada_jadwal"] = "Y";
          } else {
            $data["ada_jadwal"] = "N";
          }
/*          if(isset($_POST["ada_bukti"])=="on")
          {
            $data["ada_bukti"] = "Y";
          } else {
            $data["ada_bukti"] = "N";
          }*/
          if(isset($_POST["ada_judul"])=="on")
          {
            $data["ada_judul"] = "Y";
          } else {
            $data["ada_judul"] = "N";
          }
          if(isset($_POST["ada_pembimbing"])=="on")
          {
            $data["ada_pembimbing"] = "Y";
            $data["jumlah_pembimbing"] = $_POST["jumlah_pembimbing"];
          } else {
            $data["ada_pembimbing"] = "N";
            $data["jumlah_pembimbing"] = 0;
          }

          if(isset($_POST["isi_pembimbing_by_mahasiswa"])=="on")
          {
            $data["isi_pembimbing_by_mahasiswa"] = "Y";
          } else {
            $data["isi_pembimbing_by_mahasiswa"] = "N";
          }

          if(isset($_POST["ada_penguji"])=="on")
          {
            $data["ada_penguji"] = "Y";
            $data["jumlah_penguji"] = $_POST["jumlah_penguji"];
          } else {
            $data["ada_penguji"] = "N";
            $data["jumlah_penguji"] = 0;
          }

          if(isset($_POST["ada_sks_ditempuh"])=="on")
          {
            $data["ada_sks_ditempuh"] = "Y";
            $data["jumlah_sks_ditempuh"] = $_POST["jumlah_sks_ditempuh"];
          } else {
            $data["ada_sks_ditempuh"] = "N";
            $data["jumlah_sks_ditempuh"] = 0;
          }
          if(isset($_POST["harus_lunas_spp"])=="on")
          {
            $data["harus_lunas_spp"] = "Y";
            $data["periode_lunas"] = $_POST['periode_lunas'];
            $jenis_tagihan = implode(",", $_POST['jenis_tagihan_lunas']);
            $data["jenis_tagihan_lunas"] = $jenis_tagihan ;
          } else {
            $data["harus_lunas_spp"] = "N";
            $data["periode_lunas"] = '';
            $data["jenis_tagihan_lunas"] = '';
          }
          if(isset($_POST["harus_lulus_ict"])=="on")
          {
            $data["harus_lulus_ict"] = "Y";
          } else {
            $data["harus_lulus_ict"] = "N";
          }
          
          if(isset($_POST["harus_lulus_toafl"])=="on")
          {
            $data["harus_lulus_toafl"] = "Y";
            $data["min_nilai_toafl"] = $_POST["min_nilai_toafl"];
          } else {
            $data["harus_lulus_toafl"] = "N";
            $data["min_nilai_toafl"] = 0;
          }
          if(isset($_POST["harus_lulus_toefa"])=="on")
          {
            $data["harus_lulus_toefa"] = "Y";
            $data["min_nilai_toefa"] = $_POST["min_nilai_toefa"];
          } else {
            $data["harus_lulus_toefa"] = "N";
            $data["min_nilai_toefa"] = 0;
          }

    $data['id_jenis_pendaftaran'] = $_POST['id_jenis_pendaftaran'];

    $db2->begin_transaction();
    $in = $db2->insert("tb_data_pendaftaran_jenis_pengaturan",$data);

    if(isset($_POST["ada_matkul_syarat"])=="on")
    {
      $matkul_syarat = "Y";
    } else {
      $matkul_syarat = "N";
    }

    if ($in) {
      
        $last_id = $db2->getLastInsertId();
        foreach ($_POST['for_jurusan'] as $kode_jur) {
          if ($matkul_syarat=='Y') {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $last_id,
              'kode_jur' => $kode_jur,
              'matkul_syarat' => implode(',',$_POST['syarat_matkul'][$kode_jur])
            );
          } else {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $last_id,
              'kode_jur' => $kode_jur
            );
          }

        }

       $insert_for_jurusan = $db2->insertMulti("tb_data_pendaftaran_jenis_pengaturan_prodi",$data_for_jurusan);
        if ($insert_for_jurusan==false) {
            $db2->rollback();
        }

    }

     $db2->commit();


    
    
    action_response($db2->getErrorMessage());
    break;
  case "delete":

    
    $db2->delete("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting", $_POST['id']);
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
    case 'up_dopeg':



        $id = $_POST['id'];
    
        $implode_jurusan = implode(",", $_POST['unit']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and diperuntukan='dopeg' and kode_jur in($implode_jurusan) and tb_data_pendaftaran_jenis_pengaturan_prodi.id_jenis_pendaftaran_setting!='$id'",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));

        if ($check_jurusan_exist) {
 $jurusan_name = $db2->fetchCustomSingle("select kode_jur as kode_unit,nama_jurusan as nama_unit from view_prodi_jenjang where kode_jur='$check_jurusan_exist->kode_jur' union select kode_unit,nama_unit from tb_data_unit  where tb_data_unit.kode_unit='$check_jurusan_exist->kode_jur'");
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk UNIT '.$jurusan_name->nama_jurusan.' Sudah ada');
        }

        $db2->begin_transaction();
        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "diperuntukan" => $_POST['diperuntukan'],
            "id_jenis_akt_mhs" => $_POST["id_jenis_aktivitas_dikti"]
        );
        
         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            //$data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_name']));
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_label']));
            $data_attributes[] = $data_attr;
          }
          $data['has_attr'] = 'Y';
          $data['attr_value'] = json_encode($data_attributes);
        }



        if (!empty($_POST['bukti'])) {
          $bukti = implode(",", $_POST["bukti"]);
          $data["bukti"] = $bukti;
        } else {
          $data["bukti"] = "";
        }

          if(isset($_POST["status_aktif"])=="on")
          {
            $data["status_aktif"] = "Y";
          } else {
            $data["status_aktif"] = "N";
          }
          if(isset($_POST["ada_template_surat"])=="on")
          {
            $data["ada_template_surat"] = "Y";
            $pemaraf_ke = 1;
            if ($_POST['satu_penanda']=='more') {
              
              foreach ($_POST['pemaraf'] as $pemaraf) {
                $array_pemaraf[] = array(
                  'status' => 'pemaraf',
                  'pemaraf_ke' => $pemaraf_ke,
                  'id_jabatan_kat' => $pemaraf
                );
                $pemaraf_ke++;
              }
            }
            $array_pemaraf[] = array(
              'status' => 'penanda_tangan',
              'pemaraf_ke' => $pemaraf_ke++,
              'id_jabatan_kat' => $_POST['penanda_tangan']
            );
            $data['penanda_tangan'] = json_encode($array_pemaraf);
            if(isset($_POST["ada_kuesioner"])=="on")
            {
              $data["ada_kuesioner"] = "Y";
              $data["id_jenis_kuesioner"] = implode(",", $_POST["id_jenis_kuesioner"]);
            } else {
              $data["ada_kuesioner"] = "N";
              $data["id_jenis_kuesioner"] = NULL;
            }
          } else {
            $data["ada_template_surat"] = "N";
            $data['penanda_tangan'] = '';
          }

        

    $up = $db2->update("tb_data_pendaftaran_jenis_pengaturan",$data,"id_jenis_pendaftaran_setting",$_POST["id"]);

    echo $db2->getErrorMessage();

        $db2->query("delete from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $id));
        foreach ($_POST['unit'] as $kode_jur) {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $_POST["id"],
              'kode_jur' => $kode_jur
            );
        }

       $insert_for_jurusan = $db2->insertMulti("tb_data_pendaftaran_jenis_pengaturan_prodi",$data_for_jurusan);
        if ($insert_for_jurusan==false) {
            $db2->rollback();
        }
    $db2->commit();
    action_response($db2->getErrorMessage());
      break;
  case "up":




        $id = $_POST['id'];
    
        $implode_jurusan = implode(",", $_POST['for_jurusan']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and kode_jur in($implode_jurusan) and diperuntukan='mahasiswa' and tb_data_pendaftaran_jenis_pengaturan_prodi.id_jenis_pendaftaran_setting!='$id'",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));

        if ($check_jurusan_exist) {
          $jurusan_name = $db2->fetchSingleRow('view_prodi_jenjang','kode_jur',$check_jurusan_exist->kode_jur);
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk jurusan '.$jurusan_name->nama_jurusan.' Sudah ada');
        }

        $db2->begin_transaction();
        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "diperuntukan" => $_POST['diperuntukan'],
            "id_jenis_akt_mhs" => $_POST["id_jenis_aktivitas_dikti"]
        );
        
         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            //$data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_name']));
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_label']));
            $data_attributes[] = $data_attr;
          }
          $data['has_attr'] = 'Y';
          $data['attr_value'] = json_encode($data_attributes);
        }


   

        if (!empty($_POST['syarat_daftar'])) {
          $syarat_daftar = implode(",", $_POST["syarat_daftar"]);
          $data["syarat_daftar"] = $syarat_daftar;
        } else {
          $data["syarat_daftar"] = "";
        }

        if (!empty($_POST['bukti'])) {
          $bukti = implode(",", $_POST["bukti"]);
          $data["bukti"] = $bukti;
        } else {
          $data["bukti"] = "";
        }

          if(isset($_POST["ada_matkul_syarat"])=="on")
          {
            $data["ada_matkul_syarat"] = "Y";
          } else {
            $data["ada_matkul_syarat"] = "N";
          }
          if(isset($_POST["harus_status_aktif"])=="on")
          {
            $data["harus_status_aktif"] = "Y";
          } else {
            $data["harus_status_aktif"] = "N";
          }
          if(isset($_POST["status_aktif"])=="on")
          {
            $data["status_aktif"] = "Y";
          } else {
            $data["status_aktif"] = "N";
          }
          if(isset($_POST["ada_template_surat"])=="on")
          {
            $data["ada_template_surat"] = "Y";
            $pemaraf_ke = 1;
            if ($_POST['satu_penanda']=='more') {
              
              foreach ($_POST['pemaraf'] as $pemaraf) {
                $array_pemaraf[] = array(
                  'status' => 'pemaraf',
                  'pemaraf_ke' => $pemaraf_ke,
                  'id_jabatan_kat' => $pemaraf
                );
                $pemaraf_ke++;
              }
            }
            $array_pemaraf[] = array(
              'status' => 'penanda_tangan',
              'pemaraf_ke' => $pemaraf_ke++,
              'id_jabatan_kat' => $_POST['penanda_tangan']
            );
            $data['penanda_tangan'] = json_encode($array_pemaraf);
            if(isset($_POST["ada_kuesioner"])=="on")
            {
              $data["ada_kuesioner"] = "Y";
              $data["id_jenis_kuesioner"] = $_POST["id_jenis_kuesioner"];
            } else {
              $data["ada_kuesioner"] = "N";
              $data["id_jenis_kuesioner"] = NULL;
            }
          } else {
            $data["ada_template_surat"] = "N";
            $data['penanda_tangan'] = '';
            
          }

          if(isset($_POST["ada_sk_pembimbing"])=="on")
          {
            $data["ada_sk_pembimbing"] = "Y";
          } else {
            $data["ada_sk_pembimbing"] = "N";
          }

          if(isset($_POST["ada_sk_penguji"])=="on")
          {
            $data["ada_sk_penguji"] = "Y";
          } else {
            $data["ada_sk_penguji"] = "N";
          }

    
          if(isset($_POST["ada_jadwal"])=="on")
          {
            $data["ada_jadwal"] = "Y";
          } else {
            $data["ada_jadwal"] = "N";
          }
/*          if(isset($_POST["ada_bukti"])=="on")
          {
            $data["ada_bukti"] = "Y";
          } else {
            $data["ada_bukti"] = "N";
          }*/
          if(isset($_POST["ada_judul"])=="on")
          {
            $data["ada_judul"] = "Y";
          } else {
            $data["ada_judul"] = "N";
          }
          if(isset($_POST["ada_pembimbing"])=="on")
          {
            $data["ada_pembimbing"] = "Y";
            $data["jumlah_pembimbing"] = $_POST["jumlah_pembimbing"];
          } else {
            $data["ada_pembimbing"] = "N";
            $data["jumlah_pembimbing"] = 0;
          }

          if(isset($_POST["isi_pembimbing_by_mahasiswa"])=="on")
          {
            $data["isi_pembimbing_by_mahasiswa"] = "Y";
          } else {
            $data["isi_pembimbing_by_mahasiswa"] = "N";
          }
          
          if(isset($_POST["ada_penguji"])=="on")
          {
            $data["ada_penguji"] = "Y";
            $data["jumlah_penguji"] = $_POST["jumlah_penguji"];
          } else {
            $data["ada_penguji"] = "N";
            $data["jumlah_penguji"] = 0;
          }

          if(isset($_POST["ada_sks_ditempuh"])=="on")
          {
            $data["ada_sks_ditempuh"] = "Y";
            $data["jumlah_sks_ditempuh"] = $_POST["jumlah_sks_ditempuh"];
          } else {
            $data["ada_sks_ditempuh"] = "N";
            $data["jumlah_sks_ditempuh"] = 0;
          }
          if(isset($_POST["harus_lunas_spp"])=="on")
          {
            $data["harus_lunas_spp"] = "Y";
            $data["periode_lunas"] = $_POST['periode_lunas'];
            $jenis_tagihan = implode(",", $_POST['jenis_tagihan_lunas']);
            $data["jenis_tagihan_lunas"] = $jenis_tagihan ;
          } else {
            $data["harus_lunas_spp"] = "N";
            $data["periode_lunas"] = '';
            $data["jenis_tagihan_lunas"] = '';
          }
          if(isset($_POST["harus_lulus_ict"])=="on")
          {
            $data["harus_lulus_ict"] = "Y";
          } else {
            $data["harus_lulus_ict"] = "N";
          }
          
          if(isset($_POST["harus_lulus_toafl"])=="on")
          {
            $data["harus_lulus_toafl"] = "Y";
            $data["min_nilai_toafl"] = $_POST["min_nilai_toafl"];
          } else {
            $data["harus_lulus_toafl"] = "N";
            $data["min_nilai_toafl"] = 0;
          }
          if(isset($_POST["harus_lulus_toefa"])=="on")
          {
            $data["harus_lulus_toefa"] = "Y";
            $data["min_nilai_toefa"] = $_POST["min_nilai_toefa"];
          } else {
            $data["harus_lulus_toefa"] = "N";
            $data["min_nilai_toefa"] = 0;
          }

    if(isset($_POST["ada_matkul_syarat"])=="on")
    {
      $matkul_syarat = "Y";
    } else {
      $matkul_syarat = "N";
    }

    $up = $db2->update("tb_data_pendaftaran_jenis_pengaturan",$data,"id_jenis_pendaftaran_setting",$_POST["id"]);

        $db2->query("delete from tb_data_pendaftaran_jenis_pengaturan_prodi where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $id));
        foreach ($_POST['for_jurusan'] as $kode_jur) {
          if ($matkul_syarat=='Y') {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $_POST["id"],
              'kode_jur' => $kode_jur,
              'matkul_syarat' => implode(',',$_POST['syarat_matkul'][$kode_jur])
            );
          } else {
            $data_for_jurusan[] = array(
              'id_jenis_pendaftaran_setting' => $_POST["id"],
              'kode_jur' => $kode_jur
            );
          }
        }

       $insert_for_jurusan = $db2->insertMulti("tb_data_pendaftaran_jenis_pengaturan_prodi",$data_for_jurusan);
        if ($insert_for_jurusan==false) {
            $db2->rollback();
        }
    $db2->commit();
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>