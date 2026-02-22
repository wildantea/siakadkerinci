<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_data_pendaftaran.nim',
    'mahasiswa.nama',
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran.date_created',
    'tb_data_pendaftaran.status',
    'judul',
    'id_semester',
    'tb_data_pendaftaran.id_pendaftaran',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran.nim","tb_data_pendaftaran.id_pendaftaran");
  
        //set numbering is true
$datatable2->setNumberingStatus(1);

//set order by column
$datatable2->setOrderBy("tb_data_pendaftaran.id_pendaftaran desc");


$jur_kode = aksesProdi('mahasiswa.jur_kode');

$periode = "";
$status = "";
$jenis_pendaftaran = "";
$sem_filter = "";
$fakultas = "";

if (isset($_POST['jurusan'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'fakultas' => $_POST['fakultas'],
        'sem_filter' => $_POST['sem_filter'],
        'prodi' => $_POST['jurusan'],
        'periode' => $_POST['periode'],
        'jenis_pendaftaran' => $_POST['jenis_pendaftaran'],
        'status' => $_POST['status'],
        'input_search' => $_POST['input_search']
      );
      setFilter('filter_pendaftaran',$array_filter);
    }
    if ($_POST['fakultas']!='all') {
      $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
    }
    if ($_POST['sem_filter']!='all') {
      $sem_filter = ' and tb_data_pendaftaran.id_semester="'.$_POST['sem_filter'].'"';
    }
    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and EXTRACT( YEAR_MONTH FROM tb_data_pendaftaran.date_created )="'.$_POST['periode'].'"';
    }
    if ($_POST['jenis_pendaftaran']!='all') {
      $jenis_pendaftaran = ' and tb_data_pendaftaran_jenis.id_jenis_pendaftaran="'.$_POST['jenis_pendaftaran'].'"';
    }
    if ($_POST['status']!='all') {
      $status = ' and tb_data_pendaftaran.status="'.$_POST['status'].'"';
    }
}

$datatable2->setDebug(1);

  //set group by column
  //$datatable2->setGroupBy("tb_data_pendaftaran.id_pendaftaran");
$datatable2->setFromQuery("tb_data_pendaftaran 
inner join mahasiswa on tb_data_pendaftaran.nim=mahasiswa.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran) where tb_data_pendaftaran.id_pendaftaran is not null $sem_filter $jur_kode $fakultas $periode $jenis_pendaftaran $status");

  $query = $datatable2->execQuery("select tb_data_pendaftaran.nim,has_nilai,mahasiswa.nama,ada_matkul_syarat,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran.date_created,id_semester,
tb_data_pendaftaran.status,mahasiswa.jur_kode,tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting from tb_data_pendaftaran 
inner join mahasiswa on tb_data_pendaftaran.nim=mahasiswa.nim 
inner join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran) where tb_data_pendaftaran.id_pendaftaran is not null $sem_filter $jur_kode $fakultas $periode $jenis_pendaftaran $status",$columns);

  //buat inisialisasi array data
  $data = array();
  $prodi_jenjang = getProdiJenjang();
  foreach ($query as $value) {
     $ada_matkul = '';
    //array data
    $ResultData = array();
    $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';  
    $ResultData[] = $value->nim;
    $ResultData[] = ucwords(strtolower($value->nama));
    $ResultData[] = $value->nama_jenis_pendaftaran;
    $ResultData[] = tgl_indo($value->date_created);
    if ($value->ada_matkul_syarat=='Y') {
      if ($value->has_nilai=='N') {
        $ada_matkul = '<span class="btn btn-warning btn-xs edit-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Input Nilai"><i class="fa fa-diamond"></i></span>';
      } else {
        $ada_matkul = '<span class="btn btn-success btn-xs edit-nilai" data-id="'.$value->id_pendaftaran.'" data-toggle="tooltip" data-title="Edit Nilai"><i class="fa fa-diamond"></i></span>';
      }
    }
    if ($value->status=='1') {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-check"></i> Sudah Acc</span>';
    } elseif ($value->status=='0') {
       $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-hourglass-start"></i> Belum Acc</span>';
    } elseif ($value->status=='2') {
      $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-times-circle"></i> Ditolak</span>';
    } elseif ($value->status=='3') {
      $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-close"></i> Tidak Lulus</span>';
    } elseif ($value->status=='5') {
       $ResultData[] = '<span class="btn btn-xs btn-info btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-refresh"></i> Diajukan Ulang</span>';
    } else {
      $ResultData[] = '<span class="btn btn-primary btn-xs status-daftar" data-toggle="tooltip" data-title="Klik Untuk Melihat Bukti Upload / Merubah Status" data-jenis="'.$value->id_jenis_pendaftaran_setting.'" data-id="'.$value->id_pendaftaran.'"><i class="fa fa-check-circle"></i> Selesai</span> '.$ada_matkul;
    }
    $ResultData[] = $value->id_semester;
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    $ResultData[] = $value->id_pendaftaran;

    $data[] = $ResultData;
    
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>