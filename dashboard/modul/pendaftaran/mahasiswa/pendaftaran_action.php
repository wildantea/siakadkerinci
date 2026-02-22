<?php
session_start();
include "../../../inc/config.php";
session_check_json();
$semester_aktif = getSemesterAktif();
$data_mhs = $db2->fetchSingleRow('view_simple_mhs_data','nim',$_SESSION['username']);


switch ($_GET["act"]) {
  case "in":

//check if exist with select status
$check_pendaftaran = $db2->checkExist('tb_data_pendaftaran',array(
    'nim' => $_SESSION['username'],
    'status' => '0',
    'id_jenis_pendaftaran_setting' => $_POST['id_jenis_pendaftaran_setting']
    )
);
if ($check_pendaftaran) {
  action_response("Maaf, Data pendaftaran sudah ada");
}

  $data = array(
      "id_jenis_pendaftaran_setting" => $_POST["id_jenis_pendaftaran_setting"],
      "kode_jurusan" => $data_mhs->jur_kode,
      "nim" => $_SESSION['username'],
      "id_semester" => $semester_aktif,
      "date_created" => date('Y-m-d H:i:s'),
      "dibuat_oleh" => $_SESSION['username'],
      "tgl_dibuat" => date('Y-m-d H:i:s')
  );

  //directory upload
  $directory = $db2->fetchCustomSingle("select * from view_jenis_pendaftaran where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $_POST['id_jenis_pendaftaran_setting']));

                if ($directory->has_attr=='Y') {
                  $data_decode = json_decode($directory->attr_value);
                  foreach ($data_decode as $dt) {
                    //check if attribute no select, and crete variable from it's attribute post
                      $data_attr[$dt->attr_name] = $_POST[$dt->attr_name];
                      ${$dt->attr_name} = $_POST[$dt->attr_name];
                  }

                  $data['attr_value'] = json_encode($data_attr);
                } else {
                  $data['attr_value'] = "";
                }


  if (isset($_POST['id_jenis_bukti'])) {
    foreach ($_POST['id_jenis_bukti'] as $bukti) {
      if ($_POST['type_dokumen'][$bukti]=='1') {
            if (!preg_match("/.(pdf|jpeg|jpg|gif|png|bmp)$/i", $_FILES["file_name"]["name"][$bukti]) ) {
              action_response($lang["upload_file_error_extention"]."pdf|jpeg|jpg|gif|png|bmp");
              exit();
            }
      }
    }

  }




    if (isset($_POST['judul'])) {
      $data['judul']= $_POST["judul"];
    }

    if (isset($_POST['nomor_sk_tugas'])) {
      $data['nomor_sk_tugas']= $_POST["nomor_sk_tugas"];
      $data['tanggal_sk_tugas']= $_POST["tanggal_sk_tugas"];
      $data['lokasi']= $_POST["lokasi"];
    }





  $db2->begin_transaction();
  $in = $db2->insert("tb_data_pendaftaran",$data);

  if ($in) {
    $id_pendaftaran = $db2->getLastInsertId();
  if (isset($_POST['id_jenis_bukti'])) {
      if (!is_dir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username'])) {
          mkdir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']);
      }
      $index = 0;
    foreach ($_POST['id_jenis_bukti'] as $bukti) {
      //if link dokumen
      if ($_POST['type_dokumen'][$bukti]=='0') {
        $data_bukti_dokumen[] = array(
          'id_pendaftaran' => $id_pendaftaran,
          'id_jenis_bukti' => $bukti,
          'type_dokumen' => 0,
          'link_dokumen' => $_POST['link_dokumen'][$bukti],
          'date_created' => date('Y-m-d H:i:s'),
          'ext_type' => '',
          'file_name' => '',
        );
      } else {
        $data_bukti_dokumen[] = array(
          'id_pendaftaran' => $id_pendaftaran,
          'id_jenis_bukti' => $bukti,
          'type_dokumen' => 1,
          'link_dokumen' => '',
          'date_created' => date('Y-m-d H:i:s')
        );

        $filename = $_FILES["file_name"]["name"][$bukti];
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
        $ex = explode(".", $filename); // split filename
        $fileExt = end($ex); // ekstensi akhir
        $filename = time().rand().".".$fileExt;//rename nama file';

        $data_bukti_dokumen[$index]["ext_type"] = $fileExt;

        move_uploaded_file($_FILES["file_name"]["tmp_name"][$bukti], "../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']."/".$filename);
        $data_bukti_dokumen[$index]["file_name"] = $filename;

      }
      $index++;
    }
    $insert_bukti = $db2->insertMulti('tb_data_pendaftaran_bukti_dokumen',$data_bukti_dokumen);
    if ($insert_bukti==false) {
      $db2->rollback();
    }
/*    if ($db2->getErrorMessage()!="") {
      # code...
    }*/
  }
      if (isset($_POST['pembimbing'])) {
        $pem_ke = 1;
        foreach ($_POST['pembimbing'] as $pem) {
          $data_pembimbing[] = array(
            'id_pendaftaran' => $id_pendaftaran,
            'nip_dosen_pembimbing' => $pem,
            'pembimbing_ke' => $pem_ke
          );
          $pem_ke++;
        }
        $insert_pembimbing = $db2->insertMulti('tb_data_pendaftaran_pembimbing',$data_pembimbing);
            if ($insert_pembimbing==false) {
              $db2->rollback();
            }
      }
  }

  $db2->commit();
    
    action_response($db2->getErrorMessage());
    break;
  case 'change_syarat':
      //directory upload
      $directory = $db2->fetchCustomSingle("select nama_directory,file_name,type_dokumen from tb_data_pendaftaran_bukti_dokumen inner join tb_data_pendaftaran using(id_pendaftaran)
inner join view_jenis_pendaftaran using(id_jenis_pendaftaran_setting)
where id_bukti=?",array('id_bukti' => $_POST['id_bukti']));

    if ($_POST['type_dokumen']=='1') {
        if (!preg_match("/.(pdf|jpeg|jpg|gif|png|bmp)$/i", $_FILES["file_name"]["name"]) ) {
          action_response($lang["upload_file_error_extention"]."pdf|jpeg|jpg|gif|png|bmp");
          exit();
        }

        $data_bukti_dokumen = array(
          'type_dokumen' => 1,
          'link_dokumen' => '',
          'date_updated' => date('Y-m-d H:i:s')
        );

        $filename = $_FILES["file_name"]["name"];
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
        $ex = explode(".", $filename); // split filename
        $fileExt = end($ex); // ekstensi akhir
        $filename = time().rand().".".$fileExt;//rename nama file';

        $data_bukti_dokumen["ext_type"] = $fileExt;

        //upload file baru
        move_uploaded_file($_FILES["file_name"]["tmp_name"], "../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']."/".$filename);
        $data_bukti_dokumen["file_name"] = $filename;

    } else {
        $data_bukti_dokumen = array(
          'type_dokumen' => 0,
          'link_dokumen' => $_POST['link_dokumen'],
          'date_updated' => date('Y-m-d H:i:s'),
          'ext_type' => '',
          'file_name' => '',
        );

    }

    if ($directory->type_dokumen==1) {
      //hapus file lama
      unlink("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']."/".$directory->file_name);
    }
    $db2->update('tb_data_pendaftaran_bukti_dokumen',$data_bukti_dokumen,'id_bukti',$_POST['id_bukti']);
    action_response($db2->getErrorMessage());
    break;
  case "delete":
    
    $check_delete = $db->query("select * from tb_data_pendaftaran where id_pendaftaran=? and nim=?",array('id_pendaftaran' => $_GET['id'],'nim' => $_SESSION['username']));
    if ($check_delete->rowCount()>0) {
      $db2->delete("tb_data_pendaftaran","id_pendaftaran",$_GET["id"]);
    }
    
    
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_pendaftaran","id_pendaftaran",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":

    $id_pendaftaran = $_POST['id'];

  $data = array(
      "date_updated" => date('Y-m-d H:i:s'),
      "diubah_oleh" => $_SESSION['username'],
      "tgl_diubah" => date('Y-m-d H:i:s')
  );

  //directory upload
  $directory = $db2->fetchCustomSingle("select * from view_jenis_pendaftaran where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $_POST['id_jenis_pendaftaran_setting']));

                if ($directory->has_attr=='Y') {
                  $data_decode = json_decode($directory->attr_value);
                  foreach ($data_decode as $dt) {
                    //check if attribute no select, and crete variable from it's attribute post
                      $data_attr[$dt->attr_name] = $_POST[$dt->attr_name];
                      ${$dt->attr_name} = $_POST[$dt->attr_name];
                  }

                  $data['attr_value'] = json_encode($data_attr);
                } else {
                  $data['attr_value'] = "";
                }

  if (isset($_POST['id_jenis_bukti'])) {
    foreach ($_POST['id_jenis_bukti'] as $bukti) {
      if ($_POST['type_dokumen'][$bukti]=='1') {
            if (!preg_match("/.(pdf|jpeg|jpg|gif|png|bmp)$/i", $_FILES["file_name"]["name"][$bukti]) ) {
              action_response($lang["upload_file_error_extention"]."pdf|jpeg|jpg|gif|png|bmp");
              exit();
            }
      }
    }

  }




    if (isset($_POST['judul'])) {
      $data['judul']= $_POST["judul"];
    }

    if (isset($_POST['nomor_sk_tugas'])) {
      $data['nomor_sk_tugas']= $_POST["nomor_sk_tugas"];
      $data['tanggal_sk_tugas']= $_POST["tanggal_sk_tugas"];
      $data['lokasi']= $_POST["lokasi"];
    }

  $db2->begin_transaction();
  $in = $db2->update("tb_data_pendaftaran",$data,'id_pendaftaran',$id_pendaftaran);

  if ($in) {
  if (isset($_POST['id_jenis_bukti'])) {
      if (!is_dir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username'])) {
          mkdir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']);
      }
      $index = 0;
    foreach ($_POST['id_jenis_bukti'] as $bukti) {
      //if link dokumen
      if ($_POST['type_dokumen'][$bukti]=='0') {
        $data_bukti_dokumen[] = array(
          'id_pendaftaran' => $id_pendaftaran,
          'id_jenis_bukti' => $bukti,
          'type_dokumen' => 0,
          'link_dokumen' => $_POST['link_dokumen'][$bukti],
          'date_created' => date('Y-m-d H:i:s'),
          'ext_type' => '',
          'file_name' => '',
        );
      } else {
        $data_bukti_dokumen[] = array(
          'id_pendaftaran' => $id_pendaftaran,
          'id_jenis_bukti' => $bukti,
          'type_dokumen' => 1,
          'link_dokumen' => '',
          'date_created' => date('Y-m-d H:i:s')
        );

        $filename = $_FILES["file_name"]["name"][$bukti];
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
        $ex = explode(".", $filename); // split filename
        $fileExt = end($ex); // ekstensi akhir
        $filename = time().rand().".".$fileExt;//rename nama file';

        $data_bukti_dokumen[$index]["ext_type"] = $fileExt;

        move_uploaded_file($_FILES["file_name"]["tmp_name"][$bukti], "../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$_SESSION['username']."/".$filename);
        $data_bukti_dokumen[$index]["file_name"] = $filename;

      }
      $index++;
    }
    $insert_bukti = $db2->insertMulti('tb_data_pendaftaran_bukti_dokumen',$data_bukti_dokumen);
    if ($insert_bukti==false) {
      $db2->rollback();
    }
/*    if ($db2->getErrorMessage()!="") {
      # code...
    }*/
  }
      if (isset($_POST['pembimbing'])) {
        $db2->delete('tb_data_pendaftaran_pembimbing','id_pendaftaran',$id_pendaftaran);
        $pem_ke = 1;
        foreach ($_POST['pembimbing'] as $pem) {
          $data_pembimbing[] = array(
            'id_pendaftaran' => $id_pendaftaran,
            'nip_dosen_pembimbing' => $pem,
            'pembimbing_ke' => $pem_ke
          );
          $pem_ke++;
        }
        $insert_pembimbing = $db2->insertMulti('tb_data_pendaftaran_pembimbing',$data_pembimbing);
            if ($insert_pembimbing==false) {
              $db2->rollback();
            }
      }
  }

  $db2->commit();
    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>