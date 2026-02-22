<?php
session_start();
include "../../inc/config.php";
$columns = array(
    'nama_mk',
    'kelas.kls_nama',
    'nm_ruang',
    'id_hari',
    'jam_mulai',
    "(select group_concat(distinct nama_gelar separator '#')
 from view_nama_gelar_dosen inner join dosen_kelas on view_nama_gelar_dosen.nip=dosen_kelas.id_dosen where dosen_kelas.id_kelas=kelas.kelas_id)",
 "(select count(id_krs_detail) from krs_detail where kelas_id=kelas.kelas_id and disetujui=1)",
    'nama_jurusan',
    'kelas.kelas_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  $datatable2->setDisableSearchColumn('nama_jurusan','krs_disetujui',"(select count(krs_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and disetujui=1)");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column,last order colum will always be called. So, it's better last order column is primary key. comment this line if you disable order by
  $datatable2->setOrderBy("id_hari,jam_mulai asc");


  //set group by column
  $datatable2->setGroupBy("kelas.kelas_id");

$user = $_SESSION['username'];


$datatable2->setDebug(1);

$periode = "";

if (isset($_POST['periode'])) {
    if ($_POST['periode']!='all') {
      $periode = ' and kelas.sem_id="'.$_POST['periode'].'"';
    }
    $array_filter = array(
      'input_search' => $_POST['input_search']
    );
    setFilter('filter_kelas_dosen',$array_filter);
}

// fungsi_dosen_kelas(tb_data_kelas.kelas_id) as nama_dosen,
$datatable2->setDebug(1);
$i=1;
    $datatable2->setFromQuery("kelas 
INNER JOIN dosen_kelas 
    on dosen_kelas.id_kelas = kelas.kelas_id
INNER JOIN view_matakuliah_kurikulum 
    USING(id_matkul)
INNER JOIN view_jadwal_dosen_kelas 
    ON kelas.kelas_id = view_jadwal_dosen_kelas.id_kelas
INNER JOIN ruang_ref on view_jadwal_dosen_kelas.id_ruang=ruang_ref.ruang_id
WHERE kelas.kelas_id IS NOT NULL
    $periode");

  $query = $datatable2->execQuery("SELECT 
    semester,
    kode_mk,
    nama_kurikulum,
    nama_mk,
    total_sks,
    kelas.kls_nama,
    hari as nama_hari,
    jam_mulai,
    jam_selesai,
    id_ruang,
    kelas.catatan,
    kelas.peserta_max as kuota,
    nama_jurusan,
nm_ruang,
    kelas.kelas_id,
    view_jadwal_dosen_kelas.jadwal_id,
    (
        SELECT GROUP_CONCAT(DISTINCT nama_gelar SEPARATOR '#')
        FROM view_nama_gelar_dosen 
        INNER JOIN dosen_kelas 
            ON view_nama_gelar_dosen.nip = dosen_kelas.id_dosen 
        WHERE dosen_kelas.id_kelas = kelas.kelas_id 
    ) AS nama_dosen,
    (
        SELECT COUNT(id_krs_detail) 
        FROM krs_detail 
        WHERE id_kelas = kelas.kelas_id 
          AND disetujui = 1
    ) AS krs_disetujui
FROM kelas 
INNER JOIN dosen_kelas 
    on dosen_kelas.id_kelas = kelas.kelas_id
INNER JOIN view_matakuliah_kurikulum 
    USING(id_matkul)
INNER JOIN view_jadwal_dosen_kelas 
    ON kelas.kelas_id = view_jadwal_dosen_kelas.id_kelas
INNER JOIN ruang_ref on view_jadwal_dosen_kelas.id_ruang=ruang_ref.ruang_id
   
WHERE kelas.kelas_id IS NOT NULL
$periode 
",$columns);



  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {
    $cetak = '';
    //array data
    $ResultData = array();
    $ResultData[] = $datatable2->number($i);
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk.' - SMT '.$value->semester.' ('.$value->total_sks.' SKS)';
    $ResultData[] = $value->kls_nama;
    //'<a class="peserta-kelas" data-toggle="tooltip" data-title="Lihat Detail Kelas" data-id="'.$value->kelas_id.'" data-jadwal="'.$value->jadwal_id.'">'.$value->kls_nama.'</a>';
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = ucwords($value->nama_hari);
    if ($value->jam_mulai!="") {
      $ResultData[] = substr($value->jam_mulai,0,5)." - ".substr($value->jam_selesai,0,5);
    } else {
      $ResultData[] = "";
    }
    
    if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>", $nama_dosen));
        $ResultData[] = $nama_dosen;
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-success edit-jadwal" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->kelas_id.'"><i class="fa fa-user"></i> Tambah Pengajar</span>';
    }
    $ResultData[] = $value->krs_disetujui;
    //'<span class="btn btn-xs btn-success peserta-kelas" data-toggle="tooltip" data-title="Lihat Peserta" data-id="'.$value->kelas_id.'">'.$value->krs_disetujui.'</span>';
    $ResultData[] = $value->nama_jurusan;
      $cetak = '<a data-id="'.$value->kelas_id.'" data-jadwal="'.$value->jadwal_id.'" data-toggle="tooltip" title="Cetak Presensi/BAP" class="btn btn-xs btn-info cetak"><i class="fa fa-print"></i> Cetak Data</a>';
   $ResultData[] = '<a href="'.base_index_new().'kelas/detail/'.$value->kelas_id.'" data-toggle="tooltip" title="" class="btn btn-primary btn-xs" data-original-title="Lihat Detail Kelas" data-id="'.$value->kelas_id.'" data-jadwal="'.$value->jadwal_id.'"><i class="fa fa-eye"></i> Detail</a> '.$cetak;

    
    $data[] = $ResultData;
    $i++;
    
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();
/*$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
echo $execution_time;
exit();*/
?>