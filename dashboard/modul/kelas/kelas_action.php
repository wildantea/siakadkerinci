<?php
error_reporting(0);
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {

  case 'pindah_absen_admin':

    $row = $db2->fetchCustomSingle(
    "SELECT tanggal_pertemuan,jam_mulai,jam_selesai,kehadiran_dosen 
     FROM tb_data_kelas_pertemuan 
     WHERE id_pertemuan=?",
    ['id_pertemuan' => $_POST['id_pertemuan']]
);
        // Decode JSON lama
    $kehadiran = json_decode($row->kehadiran_dosen, true);

$tanggal_pertemuan = $row->tanggal_pertemuan;
$jam_mulai         = $row->jam_mulai;
$jam_selesai       = $row->jam_selesai;

$start_datetime = strtotime($tanggal_pertemuan . ' ' . $jam_mulai);
$end_datetime   = strtotime($tanggal_pertemuan . ' ' . $jam_selesai);
    $new_tanggal_absen = $_POST['tanggal_pertemuan'].' '.$_POST['jam'];
$absen_datetime = strtotime($new_tanggal_absen);

$is_sesuai_jadwal = ($absen_datetime >= $start_datetime && $absen_datetime <= $end_datetime) ? 'Y' : 'N';




    // Selalu replace jadi object pertama
    $kehadiran[0] = [
        'nip'           => $kehadiran[0]['nip'],
        'tanggal_absen' => $_POST['tanggal_pertemuan'].' '.$_POST['jam'],
        'sesuai_jadwal' => $is_sesuai_jadwal,
        'foto_absen'    => $kehadiran[0]['foto_absen'],
    ];


    $updated_json = json_encode($kehadiran, JSON_UNESCAPED_UNICODE);
   $update = ['kehadiran_dosen' => $updated_json];
    $db2->update('tb_data_kelas_pertemuan', $update, 'id_pertemuan', $_POST['id_pertemuan']);
  action_response($db2->getErrorMessage());
    break;
  case 'pindah_jadwal_admin':
    $jam_mulai = $_POST['jam_mulai'];
    $sks       = $_POST['sks'];

    // cari id_sesi awal
    $id_awal = $db->fetch_single_row("sesi_waktu", "jam_mulai", $jam_mulai)->id_sesi;

    // hitung id_sesi akhir
    $id_akhir = $id_awal + ($sks - 1);

    // ambil jam mulai & selesai
    $sql = "SELECT MIN(jam_mulai) AS jam_mulai, MAX(jam_selesai) AS jam_selesai
            FROM sesi_waktu
            WHERE id_sesi BETWEEN $id_awal AND $id_akhir";

    $result = $db->fetch_custom_single($sql);
    $data = array(
      "jam_mulai" => $result->jam_mulai.":00",
      "jam_selesai" => $result->jam_selesai.":00",
      "tanggal_pertemuan" => $_POST["tanggal_pertemuan"]
    );

       if(isset($_POST["is_pindah"])=="on")
    {
      $aktif = array("is_pindah"=>"Y");
      $data=array_merge($data,$aktif);
    } else {
      $aktif = array("is_pindah"=>"N");
      $data=array_merge($data,$aktif);
    }


    $up = $db2->update("tb_data_kelas_pertemuan",$data,"id_pertemuan",$_POST["id_pertemuan"]);
    echo $db2->getErrorMessage();
    action_response($db2->getErrorMessage());

    break;

  case 'pindah_jadwal':
    $jam_mulai = $_POST['jam_mulai'];
    $sks       = $_POST['sks'];

    // cari id_sesi awal
    $id_awal = $db->fetch_single_row("sesi_waktu", "jam_mulai", $jam_mulai)->id_sesi;

    // hitung id_sesi akhir
    $id_akhir = $id_awal + ($sks - 1);

    // ambil jam mulai & selesai
    $sql = "SELECT MIN(jam_mulai) AS jam_mulai, MAX(jam_selesai) AS jam_selesai
            FROM sesi_waktu
            WHERE id_sesi BETWEEN $id_awal AND $id_akhir";

    $result = $db->fetch_custom_single($sql);
    $data = array(
      "jam_mulai" => $result->jam_mulai.":00",
      "jam_selesai" => $result->jam_selesai.":00",
      "tanggal_pertemuan" => $_POST["tanggal_pertemuan"],
      "is_pindah" => 'Y'
    );

    $up = $db2->update("tb_data_kelas_pertemuan",$data,"id_pertemuan",$_POST["id_pertemuan"]);
    echo $db2->getErrorMessage();
    action_response($db2->getErrorMessage());

    break;

  case "up_materi":
    
    $data = array(
      "materi" => $_POST["materi"],
      "link_materi" => $_POST["link_materi"],
    );

    $up = $db->update("rps_materi_kuliah",$data,"id_materi",$_POST["id_materi"]);
    echo $db->getErrorMessage();
    action_response($db2->getErrorMessage());
    break;


  case "input_materi":
    
    $data = array(
      "rencana_materi" => $_POST["rencana_materi"],
      "realisasi_materi" => $_POST["realisasi_materi"],
      "link_materi" => $_POST["link_materi"],
      "status_pertemuan" => $_POST['status_pertemuan']
    );

    if (isset($_FILES['file_url'])) {
        $filename = $_FILES["file_url"]["name"];
        $filename = preg_replace("#[^a-z.0-9]#i", "", $filename);
        $upload = upload_s3('file',$filename,$_FILES["file_url"]["tmp_name"],$_FILES['file_url']['type']);
        $data['lampiran_materi'] = $upload['ObjectURL'];

    }
    

    $up = $db2->update("tb_data_kelas_pertemuan",$data,"id_pertemuan",$_POST["id_pertemuan"]);
    echo $db2->getErrorMessage();
    action_response($db2->getErrorMessage());
    break;

  case "input_bukti":
    
    $data = array(
     // "rencana_materi" => $_POST["rencana_materi"],
      "realisasi_materi" => $_POST["realisasi_materi"],
      "link_materi" => $_POST["link_materi"],
      "status_pertemuan" => $_POST['status_pertemuan']
    );

    $up = $db2->update("tb_data_kelas_pertemuan",$data,"id_pertemuan",$_POST["id_pertemuan"]);
    echo $db2->getErrorMessage();
    action_response($db2->getErrorMessage());
    break;

  
     case "in_lain":
    foreach ($_POST['nilai'] as $key => $value) {
      if (is_array($value)) {
        $array_jawaban[$key] = array_map('intval', $value);
      } else {
         $array_jawaban[$key] = $value;
      }
    }
    $jawaban = json_encode($array_jawaban);
    $data = array(
      "nim" => $_SESSION['username'], 
      "id_semester" => getSemesterAktif(),
      "jenis" => 'bap_presensi',
      "id_jenis_survey" => $_POST['id_jenis_survey'], 
      "jawaban_survey" => $jawaban
    );   
      $in = $db2->insert("tb_survey_hasil",$data);
      echo $db2->getErrorMessage();
      action_response($db2->getErrorMessage());
      break;
  default:
    # code...
    break;
}

?>