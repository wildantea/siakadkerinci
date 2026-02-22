<?php
error_reporting(0);
session_start();
$time_start = microtime(true); 
include "../../../inc/config.php";
session_check();
require('../../../inc/lib/simplexlsx.class.php');
switch ($_GET["act"]) {
  case 'import_materi':
    if (!preg_match("/.(xls|xlsx)$/i", $_FILES["semester"]["name"]) ) {

              echo "pastikan file yang anda pilih xls|xlsx";
              exit();

            } else {
              $upload = move_uploaded_file($_FILES["semester"]["tmp_name"], "../../../../upload/upload_excel/".$_FILES['semester']['name']);
              $semester = array("semester"=>$_FILES["semester"]["name"]);

            }



$error_count = 0;
$error = array();
$sukses = 0;
$values = "";
$insert_data = array();
$update_data = array();

//$db2->query("delete from rps_materi_kuliah where semester=? nand id_matkul=? and id_kelas=?",array('semester' => $_POST['semester_id'],'id_matkul' => $_POST['id_matkul'],'id_kelas' => $_POST['id_kelas']));

  $Reader = new SimpleXLSX("../../../../upload/upload_excel/".$_FILES['semester']['name']);
  foreach( $Reader->rows() as $key => $val ) {
      if ($key>0) {
        if ($val[0]!="") {
          $check = $db->check_exist('rps_materi_kuliah',
            array(
              'semester' => $_POST['semester_id'],
              'pertemuan' => $val[0],
              'id_matkul'=> $_POST['id_matkul'],
              'id_kelas' => $_POST['id_kelas'],
            ));
            if ($check==true) {
              //$error_count++;
               $sukses++;
              //$error[] = $val[1]."Pertemuan ".$val[2]." Sudah Ada";
              $update_data[] = array(
                  'materi' => str_replace('"', "'", $val[1]),
                   'link_materi' => $val[2],
                  'updatedAt' => date('Y-m-d H:i:s'),
                  'updatedBy' => $_SESSION['username']
                );
              $id_materi[] = $check->getData()->id_materi;
            } else {
              $sukses++;
                $insert_data[] = array(
                  'semester' => $_POST['semester_id'],
                  'pertemuan' => $val[0],
                  'materi' => str_replace('"', "'", $val[1]),
                   'link_materi' => $val[2],
                  'id_matkul' => $_POST['id_matkul'],
                  'id_kelas' => $_POST['id_kelas'],
                  'nip' => $_POST['nip'],
                  'createdAt' => date('Y-m-d H:i:s'),
                  'createdBy' => $_SESSION['username']
                );
              }
      }
    }
}

if (!empty($update_data)) {
  $update_materi = $db->updateMulti('rps_materi_kuliah',$update_data,'id_materi',$id_materi);
  echo $db->getErrorMessage();
  if ($update_materi) {
     //generate pertemuan



  //get jadwal jam 
  $jam = $db2->fetchSingleRow("view_jadwal","kelas_id",$_POST['id_kelas']);
  //get tanggal mulai perkuliahan
  $tgl_mulai = $db2->fetchCustomSingle("select tgl_mulai_perkuliahan from semester where id_semester=? and kode_jur=?",array("id_semester" => $_POST['semester_id'],'kode_jur' => $_POST['kode_jur']));
  $dosen_kelas = $db2->fetchCustomSingle("select group_concat(id_dosen) as nips from view_dosen_kelas_single where id_kelas='".$_POST['id_kelas']."'");
  $nips = explode(",", $dosen_kelas->nips);


  $startDate = $tgl_mulai->tgl_mulai_perkuliahan;
  $targetDay = ucwords($jam->hari);     // Hari kuliah

  // Mapping hari Indonesia ke angka (0=Sunday, 6=Saturday)
  $array_day = array(
      'Minggu' => 0,
      'Senin'  => 1,
      'Selasa' => 2,
      'Rabu'   => 3,
      'Kamis'  => 4,
      'Jumat'  => 5,
      'Sabtu'  => 6
  );

  // Dapatkan index hari dari tanggal awal
  $startIndex  = date('w', strtotime($startDate)); // 0=Sunday, 6=Saturday
  $targetIndex = $array_day[$targetDay];

  // Hitung selisih hari
  $diff = $targetIndex - $startIndex;
  if ($diff < 0) {
      $diff += 7; // Kalau negatif, berarti minggu depan
  }

  // Tambahkan selisih ke tanggal awal
  $date = date('Y-m-d', strtotime("+$diff day", strtotime($startDate)));



  $jml_berhasil = 0;
  $counter = 0;
  for ($i=1; $i <=16; $i++) { 
     //check fi pertemuan exist
      $check_pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",
        array(
          "pertemuan" => $i,
          "kelas_id" => $_POST["id_kelas"]
          )
        );
        
        // Add 7 days to the date
        $tanggal_pertemuan = date('Y-m-d', strtotime($date . ' + '.($counter*7).' days'));

      if (!$check_pertemuan) {
          $dosen = implode("#",$nips);
          $data = array(
              "pertemuan" => $i,
              "kelas_id" => $_POST["id_kelas"],
              "id_jenis_pertemuan" => 1,
              "metode_pembelajaran" => 'F',
              "tanggal_pertemuan" => $tanggal_pertemuan,
              "jam_mulai" => $jam->jam_mulai,
              "jam_selesai" => $jam->jam_selesai,
              "nip_dosen" => $dosen,
              "created_at" => date('Y-m-d H:i:s'),
              "status_pertemuan" => 'A',
              "created_by" => getUser()->first_name.' '.getUser()->last_name,
              "jadwal_id" => $jam->jadwal_id
          );

            $in = $db2->insert("tb_data_kelas_pertemuan",$data);
            $jml_berhasil++;
      }

      $counter++;
  }
    
    //action_response($db2->getErrorMessage(),array('berhasil' => $jml_berhasil));
  }
}
if (!empty($insert_data)) {

  $insert_materi = $db->insertMulti('rps_materi_kuliah',$insert_data);

  echo $db->getErrorMessage();

  if ($insert_materi) {
     //generate pertemuan



  //get jadwal jam 
  $jam = $db2->fetchSingleRow("view_jadwal","kelas_id",$_POST['id_kelas']);
  //get tanggal mulai perkuliahan
  $tgl_mulai = $db2->fetchCustomSingle("select tgl_mulai_perkuliahan from semester where id_semester=? and kode_jur=?",array("id_semester" => $_POST['semester_id'],'kode_jur' => $_POST['kode_jur']));
  $dosen_kelas = $db2->fetchCustomSingle("select group_concat(id_dosen) as nips from view_dosen_kelas_single where id_kelas='".$_POST['id_kelas']."'");
  $nips = explode(",", $dosen_kelas->nips);


  $startDate = $tgl_mulai->tgl_mulai_perkuliahan;
  $targetDay = ucwords($jam->hari);     // Hari kuliah

  // Mapping hari Indonesia ke angka (0=Sunday, 6=Saturday)
  $array_day = array(
      'Minggu' => 0,
      'Senin'  => 1,
      'Selasa' => 2,
      'Rabu'   => 3,
      'Kamis'  => 4,
      'Jumat'  => 5,
      'Sabtu'  => 6
  );

  // Dapatkan index hari dari tanggal awal
  $startIndex  = date('w', strtotime($startDate)); // 0=Sunday, 6=Saturday
  $targetIndex = $array_day[$targetDay];

  // Hitung selisih hari
  $diff = $targetIndex - $startIndex;
  if ($diff < 0) {
      $diff += 7; // Kalau negatif, berarti minggu depan
  }

  // Tambahkan selisih ke tanggal awal
  $date = date('Y-m-d', strtotime("+$diff day", strtotime($startDate)));



  $jml_berhasil = 0;
  $counter = 0;
  for ($i=1; $i <=16; $i++) { 
     //check fi pertemuan exist
      $check_pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",
        array(
          "pertemuan" => $i,
          "kelas_id" => $_POST["id_kelas"]
          )
        );
        
        // Add 7 days to the date
        $tanggal_pertemuan = date('Y-m-d', strtotime($date . ' + '.($counter*7).' days'));

      if (!$check_pertemuan) {
          $dosen = implode("#",$nips);
          $data = array(
              "pertemuan" => $i,
              "kelas_id" => $_POST["id_kelas"],
              "id_jenis_pertemuan" => 1,
              "metode_pembelajaran" => 'F',
              "tanggal_pertemuan" => $tanggal_pertemuan,
              "jam_mulai" => $jam->jam_mulai,
              "jam_selesai" => $jam->jam_selesai,
              "nip_dosen" => $dosen,
              "created_at" => date('Y-m-d H:i:s'),
              "status_pertemuan" => 'A',
              "created_by" => getUser()->first_name.' '.getUser()->last_name,
              "jadwal_id" => $jam->jadwal_id
          );

            $in = $db2->insert("tb_data_kelas_pertemuan",$data);
            $jml_berhasil++;
      }

      $counter++;
  }
    
    //action_response($db2->getErrorMessage(),array('berhasil' => $jml_berhasil));
  }
 

}
    unlink("../../../../upload/upload_excel/".$_FILES['semester']['name']);
    $msg = '';
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

if ($sukses>0) {
  action_response('');
} else {
  action_response('Error, Gagal diimport');
}
/*if (($sukses>0) || ($error_count>0)) {
  $msg =  "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\" >
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
      <font color=\"#3c763d\">".$sukses." data Rencana Materi Pembelajaran baru berhasil di import</font><br />
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
      $msg .= "</div>
    </div>";
            $msg .= "<p>Total Waktu Import : ". waktu_import($execution_time);
}*/
  echo $msg;
    break;
  case 'in':
 $data = array(
      "id_matkul" => $_POST["id_matkul"],
      "semester" => $_POST['semester_id'],
      "createdAt" => date('Y-m-d H:i:s'),
      "createdBy" => getUser()->first_name.' '.getUser()->last_name,
      "nip" => getUser()->username
  );


 //check file size
  
   if($_FILES['file_url']['size'] > 2000000) { //10 MB (size is also in bytes)
        action_response("File tidak boleh lebih dari 2Mb");
    }

  $filename = $_FILES["file_url"]["name"];
  $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
  $upload = upload_s3('file',$filename,$_FILES["file_url"]["tmp_name"],$_FILES['file_url']['type']);
  $data['file_rps'] = $upload['ObjectURL'];


 	$in = $db2->insert("rps_file",$data);
    action_response($db2->getErrorMessage());
  break;
  case 'up':
    $file_name = $db2->fetchSingleRow("rps_file","id_rps",$_POST["id_rps"]);
      $exp = explode("/",$file_name->file_rps);
      $delete_s3 = delete_s3('file',$exp[4]);

    $data = array(
      "updatedAt" => date('Y-m-d H:i:s'),
      "updatedBy" => getUser()->first_name.' '.getUser()->last_name,
      "nip" => getUser()->username
  );


  $filename = $_FILES["file_url"]["name"];
  $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
  $upload = upload_s3('file',$filename,$_FILES["file_url"]["tmp_name"],$_FILES['file_url']['type']);
  $data['file_rps'] = $upload['ObjectURL'];
 	$in = $db2->update("rps_file",$data,'id_rps',$_POST['id_rps']);

    action_response($db2->getErrorMessage());

  break;
}