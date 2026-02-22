<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":


        $implode_jurusan = implode(",", $_POST['for_jurusan']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and kode_jur in($implode_jurusan)",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));


        if ($check_jurusan_exist) {
          $jurusan_name = $db2->fetchSingleRow('view_prodi_jenjang','kode_jur',$check_jurusan_exist->kode_jur);
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk jurusan '.$jurusan_name->jurusan.' Sudah ada');
        }
       


        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "dibuat_oleh" => $_SESSION['nama'],
            "tgl_dibuat" => date('Y-m-d H:i:s')
        );

         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_name']));
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
          } else {
            $data["harus_lunas_spp"] = "N";
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
              'matkul_syarat' => implode(",", $_POST['syarat_matkul'][$kode_jur])
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
    
    
    
    $db2->delete("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",$_REQUEST["id"]);
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
  case "up":




        $id = $_POST['id'];
    
        $implode_jurusan = implode(",", $_POST['for_jurusan']);
        //check if setting for selected jurusan and selected jenis pendaftaran is exist
        $check_jurusan_exist = $db2->fetchCustomSingle("select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi
inner join tb_data_pendaftaran_jenis_pengaturan using(id_jenis_pendaftaran_setting)
where id_jenis_pendaftaran=? and kode_jur in($implode_jurusan) and tb_data_pendaftaran_jenis_pengaturan_prodi.id_jenis_pendaftaran_setting!='$id'",array('id_jenis_pendaftaran' => $_POST['id_jenis_pendaftaran']));


        if ($check_jurusan_exist) {
          $jurusan_name = $db2->fetchSingleRow('view_prodi_jenjang','kode_jur',$check_jurusan_exist->kode_jur);
          action_response('Maaf Pengaturan Jenis Pendaftaran untuk jurusan '.$jurusan_name->jurusan.' Sudah ada');
        }

        $db2->begin_transaction();
        
        $data = array(
            "keterangan" => $_POST['keterangan'],
            "diubah_oleh" => $_SESSION['nama'],
            "tgl_diubah" => date('Y-m-d H:i:s')
        );
        
         if (isset($_POST['field'])) {
          foreach ($_POST['field'] as $key => $data_attr) {
            $data_attr['attr_name'] = str_replace(" ", "_", strtolower($data_attr['attr_name']));
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
          } else {
            $data["harus_lunas_spp"] = "N";
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
              'matkul_syarat' => implode(",", $_POST['syarat_matkul'][$kode_jur])
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