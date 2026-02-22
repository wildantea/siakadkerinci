<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran.date_created',
    'tb_data_pendaftaran.status',
    'tb_data_pendaftaran.id_pendaftaran',
  );

  $datatable2->setDisableSearchColumn(
    'tb_data_pendaftaran.date_created',
    'tb_data_pendaftaran.status',
    'tb_data_pendaftaran.id_pendaftaran'
  );
  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran.nim","tb_data_pendaftaran.id_pendaftaran");
  
        //set numbering is true
        $datatable2->setNumberingStatus(0);

  //set order by column
  $datatable2->setOrderBy("tb_data_pendaftaran.id_pendaftaran desc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran.id_pendaftaran");
  $nim = $_SESSION['username'];
  $datatable2->setDebug(1);

  $datatable2->setFromQuery("tb_data_pendaftaran 
inner join mahasiswa on tb_data_pendaftaran.nim=mahasiswa.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
left join tb_data_pendaftaran_jadwal_ujian using(id_pendaftaran)
 where tb_data_pendaftaran.nim='$nim'");

  $query = $datatable2->execQuery("select tb_data_pendaftaran.nim,mahasiswa.nama,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran.date_created,tanggal_ujian,
tb_data_pendaftaran.status,tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting,
  (
    SELECT COUNT(CASE WHEN nilai_ujian IS NOT NULL AND nilai_ujian != '' THEN 1 ELSE NULL END)
    FROM tb_data_pendaftaran_penguji
    WHERE tb_data_pendaftaran_penguji.id_jadwal_ujian = tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian
  ) AS count_nilai_penguji,
(
select count(nip_dosen) from tb_data_pendaftaran_penguji where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian
) as penguji,
(
select matkul_syarat from tb_data_pendaftaran_jenis_pengaturan_prodi where kode_jur=mahasiswa.jur_kode and id_jenis_pendaftaran_setting=tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting
) as mat_syarat

 from tb_data_pendaftaran 
inner join mahasiswa on tb_data_pendaftaran.nim=mahasiswa.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
left join tb_data_pendaftaran_jadwal_ujian using(id_pendaftaran)
 where tb_data_pendaftaran.nim='$nim'",$columns);

  //buat inisialisasi array data
  $data = array();

  $button_hapus = "";
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->nama_jenis_pendaftaran;
    $ResultData[] = tgl_indo($value->date_created);
    if ($value->status=='1') {
       $ResultData[] = '<span class="btn btn-success btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Lihat Persyaratan" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-check"></i> Sudah Acc</span>';
    } elseif ($value->status=='0') {
       $ResultData[] = '<span class="btn btn-warning btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Lihat atau Ganti Persyaratan" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-hourglass-start"></i> Belum Acc</span>';
    } elseif ($value->status=='2') {
      $ResultData[] = '<span class="btn btn-danger btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Lihat Detail Penolakan" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-times-circle"></i> Ditolak</span>';
    } elseif ($value->status=='3') {
      $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Anda Tidak Lulus Pendaftaran ini" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-close"></i> Tidak Lulus</span>';
    } elseif ($value->status=='5') {
      $ResultData[] = '<span class="btn btn-warning btn-xs status-daftar" data-toggle="tooltip" data-title="Pendaftaran Diajukan Ulang" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-refresh"></i> Diajukan Ulang</span>';
    } else {
      $ResultData[] = '<span class="btn btn-primary btn-xs" data-toggle="tooltip" data-title="Pendaftaran Selesai/Lulus" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-check-circle"></i> Selesai</span>';
    }

    if ($value->mat_syarat!="") {
      if ($value->tanggal_ujian!='' && $value->count_nilai_penguji==0) {
        $nilai = '<span class="btn  btn-sm btn-danger" data-toggle="tooltip" data-title="Penguji belum input nilai"><i class="fa fa-diamond"></i></span>';
      } elseif($value->tanggal_ujian!='' && $value->count_nilai_penguji> 0 && $value->count_nilai_penguji < $value->penguji) {
        $nilai = '<span class="btn  btn-sm btn-warning lihat-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Penguji baru sebagian input nilai, klik untuk lihat"><i class="fa fa-diamond"></i></span>';
      } elseif($value->tanggal_ujian!='' && $value->count_nilai_penguji==$value->penguji) {
        $nilai = '<span class="btn btn-sm btn-success lihat-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Semua Penguji sudah input nilai, klik untuk lihat"><i class="fa fa-diamond"></i></span>';
      }
    } else {
      $nilai = '';
    }


    if ($value->status=='0' || $value->status=='2' || $value->status=='5') {
      $ResultData[] = "<a data-id='".$value->id_pendaftaran."'  class='btn btn-primary btn-sm edit_data' data-toggle='tooltip' title='Edit'><i class='fa fa-pencil'></i></a> <button data-id='".$value->id_pendaftaran."' data-uri='".base_admin()."modul/pendaftaran/mahasiswa/pendaftaran_action.php' class='btn btn-danger hapus_dtb_notif btn-sm' data-toggle='tooltip' title='Batalkan Pendaftaran' data-variable='dtb_pendaftaran'><i class='fa fa-trash'></i></button>";
    } else if($value->status=='1') {
      if ($value->tanggal_ujian!='') {
       $button_hapus.= "<button data-id='".$value->id_pendaftaran."' class='btn btn-info lihat-jadwal btn-sm' data-toggle='tooltip' title='Lihat Jadwal Ujian'><i class='fa fa-calendar'></i></button> ".$nilai;
      }
      $ResultData[] = $button_hapus;
    } else {
      $ResultData[] = "";
    }
    
    $button_hapus = "";

    $data[] = $ResultData;
    
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>