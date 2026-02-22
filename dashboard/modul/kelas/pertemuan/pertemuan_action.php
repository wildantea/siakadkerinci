<?php
session_start();
include "../../../inc/config.php";
session_check_json();
function nama_dosen($nip) {
  global $db2;
  $nama_dosen = $db2->fetchSingleRow("view_nama_gelar_dosen","nip",$nip);
  if ($nama_dosen) {
    return $nama_dosen->nama_gelar;
  } else {
    return '';
  }
}
switch ($_GET["act"]) {
  case 'in_absen':

    foreach ($_POST['nim'] as $nim => $status) {
      $array_data[] = array(
        'nim' => $nim,
        'status_absen' => $status,
        'tanggal_absen' => date('Y-m-d H:i:s')
      );
      $status_absen[$nim] = $status;
    }

    //check if absen exist
    $check = $db2->checkExist('tb_data_kelas_absensi',array('id_pertemuan' => $_POST['id_pertemuan']));
    if (!$check) {
        $isi_absensi = json_encode($array_data);
    
        $array_insert = array(
          'id_pertemuan' => $_POST['id_pertemuan'],
          'isi_absensi' => $isi_absensi
        );

    
        $in = $db2->insert("tb_data_kelas_absensi",$array_insert);
    } else {
      //if exist
      $absen = json_decode($check->getData()->isi_absensi);
      foreach ($absen as $isi_absen ) {
       $current_data[$isi_absen->nim] = $isi_absen;
      }
      foreach ($array_data as $data ) {
        if (in_array($data['nim'],array_keys($current_data))) {
          if ($data['status_absen']!=$current_data[$data['nim']]->status_absen) {
            $array_data_update[] = array(
              'nim' => $data['nim'],
              'status_absen' => $data['status_absen'],
              'tanggal_absen' => date('Y-m-d H:i:s')
            );
          } else {
            $array_data_update[] = array(
              'nim' => $current_data[$data['nim']]->nim,
              'status_absen' => $current_data[$data['nim']]->status_absen,
              'tanggal_absen' => $current_data[$data['nim']]->tanggal_absen
            );
          }
        } else {
          $array_data_update[] = array(
            'nim' => $data['nim'],
            'status_absen' => $data['status_absen'],
            'tanggal_absen' => date('Y-m-d H:i:s')
          );
        }
      }

      $isi_absensi = json_encode($array_data_update);
      $array_update = array(
        'isi_absensi' => $isi_absensi
      );
      $up = $db2->update("tb_data_kelas_absensi",$array_update,'id_pertemuan',$_POST['id_pertemuan']);
      
    }

    
    
    action_response($db2->getErrorMessage());
    break;
 case "generate":



  //get jadwal jam 
  $jam = $db2->fetchSingleRow("jadwal_kuliah","jadwal_id",$_POST['jadwal']);

  $jml_berhasil = 0;
  $counter = 0;
  for ($i=$_POST['awal_pertemuan']; $i <=$_POST['akhir_pertemuan']; $i++) { 
     //check fi pertemuan exist
      $check_pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",
        array(
          "pertemuan" => $i,
          "kelas_id" => $_POST["kelas_id"]
          )
        );
        $date = $_POST["tgl_awal"];
        // Add 7 days to the date
        $tanggal_pertemuan = date('Y-m-d', strtotime($date . ' + '.($counter*7).' days'));

      if (!$check_pertemuan) {
          $dosen = implode("#",$_POST['nip_dosen']);
          $data = array(
              "pertemuan" => $i,
              "kelas_id" => $_POST["kelas_id"],
              "id_jenis_pertemuan" => $_POST["id_jenis_pertemuan"],
              "metode_pembelajaran" => $_POST["metode_pembelajaran"],
              "tanggal_pertemuan" => $tanggal_pertemuan,
              "jam_mulai" => $jam->jam_mulai,
              "jam_selesai" => $jam->jam_selesai,
              "nip_dosen" => $dosen,
              "created_at" => date('Y-m-d H:i:s'),
              "status_pertemuan" => 'A',
              "created_by" => getUser()->first_name.' '.getUser()->last_name,
              "jadwal_id" => $_POST['jadwal']
          );

           if ($_POST['metode_pembelajaran']=='O') {
            $data["ruang_id"] = NULL;
          } else {
            $data["ruang_id"] = $_POST['ruang_id'];
          }

            $in = $db2->insert("tb_data_kelas_pertemuan",$data);
            $jml_berhasil++;
      }

      $counter++;
  }
    
    action_response($db2->getErrorMessage(),array('berhasil' => $jml_berhasil));
    break;
  case "in":
  //check fi pertemuan exist
  $check_pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",
    array(
      "pertemuan" => $_POST["pertemuan"],
      "kelas_id" => $_POST["kelas_id"]
      )
    );
  if ($check_pertemuan) {
    action_response("Pertemuan ".$_POST["pertemuan"]." sudah ada");
  }

  $dosen = implode("#",$_POST['nip_dosen']);
  
  $data = array(
      "pertemuan" => $_POST["pertemuan"],
      "kelas_id" => $_POST["kelas_id"],
      "id_jenis_pertemuan" => $_POST["id_jenis_pertemuan"],
      "tanggal_pertemuan" => $_POST["tanggal_pertemuan"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
      "nip_dosen" => $dosen,
      "created_at" => date('Y-m-d H:i:s'),
      "created_by" => getUser()->full_name
  );

    $in = $db2->insert("tb_data_kelas_pertemuan",$data);
    
    
    action_response($db2->getErrorMessage());
    break;
  case "hapus":
    $data_ids = $_REQUEST["pertemuan_id"];
    $data_id_array = explode(",", $data_ids);
    foreach ($data_id_array as $id ) {
      $db2->delete("tb_data_kelas_pertemuan","id_pertemuan",$id);
    }
    action_response($db2->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db2->delete("tb_data_kelas_pertemuan","id_pertemuan",$id);
         }
    }
    action_response($db2->getErrorMessage());
    break;
  case "up":

    foreach ($_POST['nip_dosen'] as $dos ) {
      //chek with selected date
      $dosen_pertemuan = $db2->query("select * from tb_data_kelas_pertemuan where ('".$_POST['jam_mulai']."'>jam_mulai and '".$_POST['jam_mulai']."' < jam_selesai or '".$_POST['jam_selesai']."' > jam_mulai and '".$_POST['jam_selesai']."' < jam_selesai or jam_mulai > '".$_POST['jam_mulai']."' and jam_mulai < '".$_POST['jam_selesai']."' or jam_selesai > '".$_POST['jam_mulai']."' and jam_selesai<'".$_POST['jam_selesai']."' or jam_mulai='".$_POST['jam_mulai']."' and jam_selesai='".$_POST['jam_selesai']."') and tanggal_pertemuan='".$_POST['tanggal_pertemuan']."' and nip_dosen like '%$dos%' and id_pertemuan!='".$_POST['id']."'");
      if ($dosen_pertemuan->rowCount()) {
        foreach ($dosen_pertemuan as $per ) {
          action_response("Maaf Dosen ".nama_dosen($dos)." bentrok dengan pertemuan lain");
        }
      }

    }

    //check bentrok jadwal
  //first check bentrok ruangan

/*    $check_bentrok_kelas = $db2->fetchCustomSingle("select * from tb_data_kelas_pertemuan where
    ('".$_POST['jam_mulai']."'>jam_mulai and '".$_POST['jam_mulai']."'<jam_selesai or '".$_POST['jam_selesai']."'> jam_mulai and '".$_POST['jam_selesai']."'<jam_selesai or jam_mulai > '".$_POST['jam_mulai']."' and jam_mulai <'".$_POST['jam_selesai']."' or jam_selesai>'".$_POST['jam_mulai']."' and jam_selesai<'".$_POST['jam_selesai']."' or jam_mulai='".$_POST['jam_mulai']."' and jam_selesai='".$_POST['jam_selesai']."') and id_pertemuan!='".$_POST['id']."' and tanggal_pertemuan=? and ruang_id=?",$check_jadwal_kelas);
    //dump($check_bentrok_kelas);
    if ($check_bentrok_kelas) {
    action_response("Maaf Ruangan ini Tanggal $check_bentrok_kelas->tanggal_pertemuan Jam ".$check_bentrok_kelas->jam_mulai." S/d ".$check_bentrok_kelas->jam_selesai." Sudah digunakan Kelas/Pertemuan Lain");
    }*/

    //check jawal dosen

   $dosen = implode("#",$_POST['nip_dosen']);
   $data = array(
      "id_jenis_pertemuan" => $_POST["id_jenis_pertemuan"],
      "tanggal_pertemuan" => $_POST["tanggal_pertemuan"],
      "jam_mulai" => $_POST["jam_mulai"],
      "jam_selesai" => $_POST["jam_selesai"],
      "metode_pembelajaran" => $_POST["metode_pembelajaran"],
      "nip_dosen" => $dosen,
      "updated_at" => date('Y-m-d H:i:s'),
      "updated_by" => getUser()->full_name
   );

   if ($_POST['metode_pembelajaran']=='F') {
      $data['ruang_id'] = $_POST['ruang_id'];
   }
    
    $up = $db2->update("tb_data_kelas_pertemuan",$data,"id_pertemuan",$_POST["id"]);
    
    action_response($db2->getErrorMessage());
    break;
    case 'hapus':
      
      break;
    case 'action':
      $data_ids = $_REQUEST["pertemuan_id"];
      $data_id_array = explode(",", $data_ids);

      dump($_POST);
      break;
  default:
    # code...
    break;
}

?>