<?php
session_start();
include "../../../inc/config.php";

$columns = array(
  'tb_data_kelas_pertemuan.pertemuan',
  'tb_data_kelas_pertemuan.tanggal_pertemuan',
  'tb_data_kelas_pertemuan.jam_mulai',
  'tb_data_kelas_pertemuan.nip_dosen',
  'tb_data_kelas_pertemuan.id_pertemuan',
);

//if you want to exclude column for searching, put columns name in quote and separate with comma if multi
//$datatable2->setDisableSearchColumn("tb_data_kelas_pertemuan.updated_by","tb_data_kelas_pertemuan.id_pertemuan");

//set numbering is true
$datatable2->setNumberingStatus(0);

//set order by column
$datatable2->setOrderBy("tb_data_kelas_pertemuan.pertemuan asc");


//set group by column
//$datatable2->setGroupBy("tb_data_kelas_pertemuan.id_pertemuan");

$datatable2->setDebug(1);
$nip = $_SESSION['username'];



//get dosen kelas
$array_dosen = array();
$kelas_id = $_POST['kelas_id'];
//cek jenjang
$jenjang = $db2->fetchCustomSingle("select jenjang from view_prodi_jenjang inner join view_nama_kelas using(kode_jur) where kelas_id=$kelas_id");
$jenjang_kelas = $jenjang->jenjang;

function get_nama_dosen($nip)
{
  global $db2;
  $dosen_kelas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip  where id_kelas='" . $_POST['kelas_id'] . "'");
  foreach ($dosen_kelas as $dosen) {
    $array_dosen[$dosen->nip] = $dosen->nama_gelar;
  }
  return $array_dosen[$nip];
}
$query = $datatable2->execQuery("select tb_data_kelas_pertemuan.pertemuan,tb_data_kelas_pertemuan.status_pertemuan,tb_data_kelas_pertemuan.tanggal_pertemuan,tb_data_jenis_pertemuan.nama_jenis_pertemuan,tb_data_kelas_pertemuan.jam_mulai,jam_selesai,
realisasi_materi,tb_data_kelas_pertemuan.nip_dosen,tb_data_kelas_pertemuan.kehadiran_dosen,tb_data_kelas_pertemuan.id_pertemuan,isi_absensi,tb_data_kelas_pertemuan.kelas_id,

kehadiran_dosen_keluar,
JSON_SEARCH(kehadiran_dosen, 'one', '" . $nip . "') as is_hadir,link_materi,is_pindah,

(
  select count(id_krs_detail) as jml_peserta FROM krs_detail where id_kelas=tb_data_kelas_pertemuan.kelas_id and disetujui='1'
) as jml_peserta,
(
  select group_concat(nim) FROM krs_detail where id_kelas=tb_data_kelas_pertemuan.kelas_id
) as nim_peserta
 from tb_data_kelas_pertemuan 
inner join tb_data_jenis_pertemuan on tb_data_kelas_pertemuan.id_jenis_pertemuan=tb_data_jenis_pertemuan.id_jenis_pertemuan

   left join tb_data_kelas_absensi using(id_pertemuan) where kelas_id='" . $kelas_id . "'
    ", $columns);

//buat inisialisasi array data
$data = array();

function get_hadir($obj, $total, $nim)
{
  // coba decode
  $decoded = json_decode($obj);

  // cek kalau JSON tidak valid
  if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded) && !is_object($decoded)) {
    return array('hadir' => 0, 'total' => $total, 'persen' => 0);
  }

  $array_nim = explode(",", $nim);
  $hadir = 0;

  foreach ($decoded as $data) {
    if (isset($data->nim, $data->status_absen) && in_array($data->nim, $array_nim)) {
      if ($data->status_absen === 'Hadir') {
        $hadir++;
      }
    }
  }

  $persen = $total > 0 ? round(($hadir / $total) * 100, 0) : 0;
  return array('hadir' => $hadir, 'total' => $total, 'persen' => $persen);
}

$i = 1;
// Current date and time
$currentDateTime = new DateTime(); // Current time: 2025-08-24 14:36:00


$array_login_as = array('admin');

$pertemuan_array = array(1, 2, 3, 4);

foreach ($query as $value) {
  $hadir = '';
  $persen = '';
  if ($value->isi_absensi != "") {
    $stat = get_hadir($value->isi_absensi, $value->jml_peserta, $value->nim_peserta);
    $hadir = $stat['hadir'];
    $total = $stat['total'];
    $persen = $stat['persen'] . '%';
  }

  //array data
  $ResultData = array();
  $ResultData[] = $value->pertemuan;
  $ResultData[] = getHariFromDate($value->tanggal_pertemuan) . ', ' . tgl_indo($value->tanggal_pertemuan);

  $ResultData[] = $value->jam_mulai . ' s/d ' . $value->jam_selesai;

  $nama_dosen = array_map('get_nama_dosen', explode('#', $value->nip_dosen));
  $nama_dosen = trim(implode("<br>", $nama_dosen));
  $ResultData[] = $nama_dosen;
  /*    if ($value->realisasi_materi!='') {
        $ResultData[] = "<span class='btn btn-success' data-toggle='tooltip' title='Materi sudah ditambahkan'><i class='fa fa-check'></i></span>";
      } else {
        $ResultData[] = "<span class='btn btn-danger' data-toggle='tooltip' title='Materi belum di tambahkan'><i class='fa fa-close'></i></span>";
      }*/
  $ResultData[] = $value->jml_peserta;
  $ResultData[] = $hadir;
  $ResultData[] = $persen;
  $materi = '';

  if ($value->kehadiran_dosen != '') {
    $ResultData[] = "<a target='_blank' href='https://siakad.iainkerinci.ac.id/status_absen.php?id=" . $value->id_pertemuan . "' class='btn btn-success' data-toggle='tooltip' title='Dosen Sudah Absen Masuk'><i class='fa fa-check'></i></a>";
    //$materi = "<a data-id='$value->id_pertemuan' data-kelas='$value->kelas_id'  class='btn btn-primary input-materi' data-toggle='tooltip' title='Isi Materi'><i class='fa fa-book'></i> Input Bukti Ajar</a>";
  } else {
    $ResultData[] = "<span class='btn btn-danger' data-toggle='tooltip' title='Dosen Belum Absen Masuk'><i class='fa fa-close'></i></span>";
  }

  if ($value->kehadiran_dosen_keluar != '') {
    $ResultData[] = "<a target='_blank' href='https://siakad.iainkerinci.ac.id/status_absen.php?id=" . $value->id_pertemuan . "' class='btn btn-success' data-toggle='tooltip' title='Dosen Sudah Absen Keluar'><i class='fa fa-check'></i></a>";
    //$materi = "<a data-id='$value->id_pertemuan' data-kelas='$value->kelas_id'  class='btn btn-primary input-materi' data-toggle='tooltip' title='Isi Materi'><i class='fa fa-book'></i> Input Bukti Ajar</a>";
  } else {
    $ResultData[] = "<span class='btn btn-danger' data-toggle='tooltip' title='Dosen Belum Absen Keluar'><i class='fa fa-close'></i></span>";
  }


  $link_materi = "";
  /* if ($value->link_materi!='') {
     $link_materi = "<a target='_blank' href='".$value->link_materi."' class='btn btn-info' data-toggle='tooltip' data-title='Lihat Materi'><i class='fa fa-book'></i></a>";
   }*/
  if ($value->status_pertemuan == 'A') {
    $ResultData[] = '<span class="btn btn-primary"><i class="fa fa-check"></i> Aktif</span> ' . $link_materi;
  } else {
    $ResultData[] = '<span class="btn btn-success"><i class="fa fa- fa-star"></i> Selesai</span> ' . $link_materi;
  }


  $status_absen = "no";
  $pindah = "";

  $status_materi = "no";

  if ($jenjang_kelas == 'S1') {

    // waktu sekarang

    // waktu sekarang
    $now = new DateTime();

    // awal & akhir minggu
    $startOfWeek = (new DateTime())->modify('monday this week')->setTime(0, 0, 0);
    $endOfWeek = (new DateTime())->modify('sunday this week')->setTime(23, 59, 59);

    // contoh ambil dari database
    // misal $value->tanggal_mulai = "2025-08-23", $value->jam_mulai = "10:00:00", $value->jam_selesai = "12:00:00"
    $tanggalMulai = new DateTime($value->tanggal_pertemuan . ' ' . $value->jam_mulai);
    $tanggalSelesai = new DateTime($value->tanggal_pertemuan . ' ' . $value->jam_selesai);

    if ($tanggalMulai >= $startOfWeek && $tanggalSelesai <= $endOfWeek && $value->is_pindah == 'N') {
      $pindah = "<a data-id='$value->id_pertemuan' data-kelas='$value->kelas_id' class='btn btn-primary pindah-jadwal' data-toggle='tooltip' title='Pindah Jadwal'><i class='fa fa-calendar'></i> Pindah Jadwal</a>";

    }

    // Database record values
    $tanggal_pertemuan = $value->tanggal_pertemuan;
    $jam_mulai = $value->jam_mulai;
    $jam_selesai = $value->jam_selesai;
    // Combine date and times to create DateTime objects for comparison
    $meetingStart = DateTime::createFromFormat(
      'Y-m-d H:i:s',
      "$tanggal_pertemuan $jam_mulai",
      new DateTimeZone('Asia/Jakarta')
    );
    $meetingEnd = DateTime::createFromFormat(
      'Y-m-d H:i:s',
      "$tanggal_pertemuan $jam_selesai",
      new DateTimeZone('Asia/Jakarta')
    );

    $tambah_30_menit = $meetingEnd->add(new DateInterval('PT30M'));

    /* $tambah_8_jam = $meetingEnd->add(new DateInterval('PT8H'));

     // tambah 7 hari
      $tambah_7_hari = $meetingEnd->add(new DateInterval('P14D'));*/



    // Check if current time is within the meeting range
    if ($currentDateTime >= $meetingStart && $currentDateTime <= $meetingEnd) {
      $status_absen = "yes";
      $status_materi = "yes";
    } else {
      // Optional: Provide reason why it's not within the range
      if ($currentDateTime->format('Y-m-d') !== $meetingStart->format('Y-m-d')) {
        // $status = "The current date (" . $currentDateTime->format('Y-m-d') . ") does not match the meeting date ($tanggal_pertemuan).";
        $status_absen = "no";
      } else {
        // $status = "The current time (" . $currentDateTime->format('H:i:s') . ") is outside the meeting time range ($jam_mulai to $jam_selesai).";
        $status_absen = "no";
      }
    }
    $tambah_30_menit = $meetingEnd->add(new DateInterval('PT30M'));

    if ($currentDateTime >= $meetingStart && $currentDateTime <= $tambah_30_menit) {
      $status_materi = "yes";
    }

    if (in_array($value->pertemuan, $pertemuan_array)) {
      $status_absen = 'yes';
      $status_materi = "yes";
    }


  } else {
    $status_absen = 'yes';
    $status_materi = "yes";

  }


  if (in_array($_SESSION['group_level'], $array_login_as)) {
    $pindah = "<a data-id='$value->id_pertemuan' data-kelas='$value->kelas_id' class='btn btn-primary pindah-jadwal-admin' data-toggle='tooltip' title='Pindah Jadwal'><i class='fa fa-calendar'></i> Pindah Jadwal</a>";
  }

  if ($value->status_pertemuan == 'A') {
    if ($status_absen == 'yes') {
      $ResultData[] = "<a data-id='$value->id_pertemuan' data-kelas='$value->kelas_id'  class='btn btn-success input-absen' data-toggle='tooltip' title='Isi Presensi'><i class='fa fa-user-plus'></i> Input Presensi</a> $materi $pindah";
    } else {
      $ResultData[] = "$pindah";
    }
  } else {
    $ResultData[] = "$pindah";
  }






  $data[] = $ResultData;
  $i++;
}

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>