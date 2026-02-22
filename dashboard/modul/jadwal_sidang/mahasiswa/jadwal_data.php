<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'view_simple_mhs.nim',
    'view_simple_mhs.nama',
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran.date_created',
    'tb_data_pendaftaran_jadwal_ujian.tanggal_ujian',
    'fungsi_pendaftaran_penguji(tb_data_pendaftaran.id_pendaftaran)',
    'fungsi_pendaftaran_pembimbing(tb_data_pendaftaran.id_pendaftaran)',
    'tb_data_pendaftaran_jadwal_ujian.id_pendaftaran',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran_jadwal_ujian.jam_selesai","tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian");
  
        //set numbering is true
        $datatable2->setNumberingStatus(0);

  //set order by column
  $datatable2->setOrderBy("tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian desc");


$jur_kode = "";
//get default akses prodi 
$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "and view_simple_mhs.kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and view_simple_mhs.kode_jur in(0)";
}

$periode = "";
$status = "";
$jenis_pendaftaran = "";

if (isset($_POST['jurusan'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'prodi' => $_POST['jurusan'],
        'periode' => $_POST['periode'],
        'jenis_pendaftaran' => $_POST['jenis_pendaftaran'],
        'input_search' => $_POST['input_search']
      );
      setFilter('filter_pendaftaran',$array_filter);
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and view_simple_mhs.kode_jur="'.$_POST['jurusan'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = " and DATE_FORMAT(tb_data_pendaftaran.date_created,'%Y%m')='".$_POST['periode']."'";
    }
    if ($_POST['jenis_pendaftaran']!='all') {
      $jenis_pendaftaran = ' and tb_data_pendaftaran_jenis.id_jenis_pendaftaran="'.$_POST['jenis_pendaftaran'].'"';
    }
}

  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian");

//$datatable2->setDebug(1);
  
  $query = $datatable2->execQuery("select view_simple_mhs.nim,view_simple_mhs.nama,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jadwal_ujian.tanggal_ujian,tb_data_pendaftaran_jadwal_ujian.jam_mulai,tb_data_pendaftaran_jadwal_ujian.jam_selesai,tb_data_pendaftaran.date_created,id_ruang,tb_data_pendaftaran.id_pendaftaran,tempat,fungsi_pendaftaran_penguji(tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian) as penguji,fungsi_pendaftaran_pembimbing(tb_data_pendaftaran.id_pendaftaran) as pembimbing  from tb_data_pendaftaran_jadwal_ujian right join tb_data_pendaftaran on tb_data_pendaftaran_jadwal_ujian.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran inner join view_simple_mhs on tb_data_pendaftaran.nim=view_simple_mhs.nim inner join tb_data_pendaftaran_jenis_pengaturan on tb_data_pendaftaran.id_jenis_pendaftaran_setting=tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting inner join tb_data_pendaftaran_jenis on tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran=tb_data_pendaftaran_jenis.id_jenis_pendaftaran where tb_data_pendaftaran.status='1' $jur_kode $periode $jenis_pendaftaran",$columns);

  //buat inisialisasi array data
  $data = array();

  $ruang = "";
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jenis_pendaftaran;
    $ResultData[] = tgl_indo($value->date_created);
    if ($value->tempat=='Ruangan') {
      $ruang = getRuang($value->id_ruang);
    } elseif($value->tempat=='Daring') {
      $ruang = 'Daring';
    }
    if ($value->tanggal_ujian!="") {
    $ResultData[] = getHariFromDate($value->tanggal_ujian).', '.tgl_indo($value->tanggal_ujian).', '.$value->jam_mulai.'-'.$value->jam_selesai.' @ '.$ruang;  
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-primary edit_data" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-calendar"></i> Tambah Jadwal</span>';
    }
    if ($value->penguji!='') {
        $nama_dosen = array_map('trim', explode('#', $value->penguji));
        foreach ($nama_dosen as $index => $dosen_uji) {
          $dosen_penguji[] = ($index+1).'. '.$dosen_uji;
        }
        $nama_dosen = trim(implode("<br>", $dosen_penguji));
        $ResultData[] = $nama_dosen;
    } else {
      if ($value->tanggal_ujian!="") {
        $ResultData[] = '<span class="btn btn-xs btn-success edit_data" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-user"></i> Tambah Dosen</span>';
      } else {
         $ResultData[] = '';
      }
      
    }
    if ($value->pembimbing!='') {
        $nama_dosen_pembimbing = array_map('trim', explode('#', $value->pembimbing));
        foreach ($nama_dosen_pembimbing as $nomor => $dosen) {
          $dosen_pembimbing[] = ($nomor+1).'. '.$dosen;
        }
        $nama_dosen_pembimbing = trim(implode("<br>", $dosen_pembimbing));
        $ResultData[] = $nama_dosen_pembimbing;
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-warning" data-toggle="tooltip" data-title="Sidang ini tidak Ada Dosen pembimbing">None</span>';
    }
    $ResultData[] = $value->id_pendaftaran;

    $data[] = $ResultData;
    $dosen_penguji = array();
    $dosen_pembimbing = array();
    
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>