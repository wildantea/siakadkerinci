<?php
session_start();
include "../../inc/config.php";
session_check_json();
$time_start = microtime(true); 
require('../../inc/lib/spreadsheetreader/SpreadsheetReader.php');
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

$error_data = "";
foreach ($Reader as $key => $val)
{


    if ($key>0) {

        if ($val[0]!='') {
            $nim = trimmer($val[0]);
            $jenis = trimmer($val[1]);
            $status_pendaftran = trimmer($val[4]);

            $data_mhs = $db2->fetchSingleRow("view_simple_mhs","nim",$nim);
            $id_jenis_pendaftaran_setting = $db2->fetchCustomSingle("select * from view_jenis_pendaftaran where kode_jur=? and id_jenis_pendaftaran=?",array("kode_jur" => $data_mhs->kode_jur,'id_jenis_pendaftaran' => $jenis));

            $check_pendaftaran = $db2->checkExist('tb_data_pendaftaran', array('nim' => $nim,'id_jenis_pendaftaran_setting' => $id_jenis_pendaftaran_setting->id_jenis_pendaftaran_setting,'status' => $status_pendaftran));

            if ($data_mhs==false) {
              $error_data = $nim." NIM tidak ditemukan di Sistem";
            } elseif ($id_jenis_pendaftaran_setting==false) {
              $error_data = $nim." Belum Ada Pengaturan Pendaftaran";
            } elseif ($check_pendaftaran==true) {
              $error_data = $nim." Data pendaftaran Untuk Mahasiswa dengan Status yang dipilih sudah ada";
            }
            

            if ($error_data!="") {
              $error_count++;
              $data_error[] = array(
                    $val[0],
                    $val[1],
                    $val[2],
                    $val[3],
                    $val[4],
                    $error_data
                );
              $error[] = $error_data;
            } else {
                $sukses++;
                $data_insert[] = array(
                    "id_jenis_pendaftaran_setting" => $id_jenis_pendaftaran_setting->id_jenis_pendaftaran_setting,
                    "kode_jurusan" => $data_mhs->kode_jur,
                    "nim" => $data_mhs->nim,
                    "id_semester" => trimmer($val[3]),
                    "date_created" => trimmer($val[2]),
                    "status" => trimmer($val[4]),
                    "dibuat_oleh" => $_SESSION['username'],
                    "tgl_dibuat" => date('Y-m-d H:i:s')
                );
            }

            $error_data = "";
            
        }

    }

}


if (!empty($data_error)) {
  include "download_error_import.php";
}

if (!empty($data_insert)) {
    $insert = $db2->insertMulti('tb_data_pendaftaran',$data_insert);
}

unlink("../../../upload/upload_excel/".$_FILES['semester']['name']);
$msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

if (($sukses>0) || ($error_count>0)) {
    $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" style=\"margin-bottom: 0;\" >
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
    <font color=\"#3c763d\">".$sukses." data Mahasiswa baru berhasil di import</font><br />
    <font color=\"#ce4844\" >".$error_count." data tidak bisa ditambahkan </font>";
    if (!$error_count==0) {
        $msg .= "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">Detail error</a>";
    }
    
    if ($error_count>0) {
        $msg .= "<div class=\"collapse\" id=\"collapseExample\">";
        $i=1;
        foreach ($error as $pesan) {
            $msg .= "<div class=\"bs-callout bs-callout-danger\">".$i.". ".$pesan."</div>";
            $i++;
        }
        $msg .= "</div><br><a href='".base_url()."upload/sample/pendaftaran/error_pendaftaran.xlsx' class='btn btn-sm btn-primary' style='text-decoration:none;'>Download Data Error</a><br>";
    }

    $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
    $msg .= "</div>";

}
echo $msg;
break;

  case 'ajukan_ulang':
   
   $data = array(
      "status" => 5,
      "ket_ajuan_ulang" => $_POST['ket_ajuan_ulang'],
      "status_diubah_oleh" => $_SESSION['username'],
      "tgl_status_diubah" => date('Y-m-d H:i:s'),
      "date_created" => date('Y-m-d H:i:s')
   );
    
    $up = $db2->update("tb_data_pendaftaran",$data,"id_pendaftaran",$_POST["id"]);
    
    action_response($db2->getErrorMessage());
    break;

  case 'change_status':
   
   $data = array(
      "status" => $_POST["status"],
      "status_diubah_oleh" => $_SESSION['username'],
      "tgl_status_diubah" => date('Y-m-d H:i:s')
   );
   if ($_POST['status']==2) {
     $data['ket_ditolak'] = $_POST['ket_ditolak'];
   } else {
    $data['ket_ditolak'] = '';
   }
    
    $up = $db2->update("tb_data_pendaftaran",$data,"id_pendaftaran",$_POST["id_pendaftaran"]);
    
    action_response($db2->getErrorMessage());
    break;
  case "in":
  
$data_mhs = $db2->fetchSingleRow('view_simple_mhs','nim',$_POST['nim']);

//check if exist with select status
$check_pendaftaran = $db2->checkExist('tb_data_pendaftaran',array(
    'nim' => $data_mhs->nim,
    'status' => $_POST['status'],
    'id_jenis_pendaftaran_setting' => $_POST['id_jenis_pendaftaran_setting']
    )
);
if ($check_pendaftaran) {
  action_response("Data pendaftaran Untuk Mahasiswa dengan Status yang dipilih sudah ada");
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

  //directory upload
  $directory = $db2->fetchCustomSingle("select nama_directory from view_jenis_pendaftaran where id_jenis_pendaftaran_setting=?",array('id_jenis_pendaftaran_setting' => $_POST['id_jenis_pendaftaran_setting']));

  $data = array(
      "id_jenis_pendaftaran_setting" => $_POST["id_jenis_pendaftaran_setting"],
      "kode_jurusan" => $data_mhs->kode_jur,
      "nim" => $data_mhs->nim,
      "id_semester" => $_POST['id_semester'],
      "date_created" => $_POST['date_created'].' '.date('H:i:s'),
      "status" => $_POST['status'],
      "dibuat_oleh" => $_SESSION['username'],
      "tgl_dibuat" => date('Y-m-d H:i:s')
  );

    if (isset($_POST['judul'])) {
      $data['judul']= $_POST["judul"];
    }

  $db2->begin_transaction();
  $in = $db2->insert("tb_data_pendaftaran",$data);

  if ($in) {
    $id_pendaftaran = $db2->getLastInsertId();
  if (isset($_POST['id_jenis_bukti'])) {
      if (!is_dir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$data_mhs->nim)) {
          mkdir("../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$data_mhs->nim);
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

        copy($_FILES["file_name"]["tmp_name"][$bukti], "../../../../upload/pendaftaran/".$directory->nama_directory.'/'.$data_mhs->nim."/".$filename);
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
          if ($pem!="") {
            $data_pembimbing[] = array(
              'id_pendaftaran' => $id_pendaftaran,
              'nip_dosen_pembimbing' => $pem,
              'pembimbing_ke' => $pem_ke
            );
            $pem_ke++;
          }
        }
        if (!empty($data_pembimbing)) {
          $insert_pembimbing = $db2->insertMulti('tb_data_pendaftaran_pembimbing',$data_pembimbing);
              if ($insert_pembimbing==false) {
                $db2->rollback();
              }
        }

      }
  }

  $db2->commit();
    
    action_response($db2->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db2->delete("tb_data_pendaftaran","id_pendaftaran",$_GET["id"]);
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
   case "change_status_massal":
    $data_ids = $_REQUEST["id_pendaftaran"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $check_pendaftaran = array(
              'status' => $_POST['status'],
              'ket_ditolak' => $_POST['ket_ditolak']
            );
          $db2->update('tb_data_pendaftaran',$check_pendaftaran,'id_pendaftaran',$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":
    
    $data = array(
        "date_updated" => date('Y-m-d H:i:s'),
        "diubah_oleh" => $_SESSION['username'],
        "date_created" => $_POST['date_created'].' '.date('H:i:s'),
        "tgl_diubah" => date('Y-m-d H:i:s')
    );

    if (isset($_POST['judul'])) {
      $data['judul']= $_POST["judul"];
    }


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


    $up = $db2->update("tb_data_pendaftaran",$data,"id_pendaftaran",$_POST["id"]);

      if (isset($_POST['pembimbing'])) {
        $db2->delete('tb_data_pendaftaran_pembimbing','id_pendaftaran',$_POST["id"]);
        $pem_ke = 1;
        foreach ($_POST['pembimbing'] as $pem) {
          $data_pembimbing[] = array(
            'id_pendaftaran' => $_POST["id"],
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



    
    action_response($db2->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>