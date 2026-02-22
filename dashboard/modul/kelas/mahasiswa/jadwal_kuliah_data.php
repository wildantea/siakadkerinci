<?php
session_start();
include "../../../inc/config.php";
$columns = array(
    'view_nama_kelas.nm_matkul',
    'view_nama_kelas.kls_nama',
    'nm_ruang',
    'id_hari',
    'jam_mulai',
    "(select group_concat(distinct nama_gelar separator '#')
    from view_nama_gelar_dosen inner join dosen_kelas on view_nama_gelar_dosen.nip=dosen_kelas.id_dosen where dosen_kelas.id_kelas=view_nama_kelas.kelas_id)",
    'jurusan',
    'view_nama_kelas.kelas_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  $datatable2->setDisableSearchColumn(
     'id_hari'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column,last order colum will always be called. So, it's better last order column is primary key. comment this line if you disable order by
  $datatable2->setOrderBy("id_hari,jam_mulai asc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas.kelas_id");
  //
//$datatable2->setDebug(1);

$periode = "";

if (isset($_POST['periode'])) {
    if ($_POST['periode']!='all') {
      $periode = ' and view_nama_kelas.sem_id="'.$_POST['periode'].'"';
    }
    $array_filter = array(
      'input_search' => $_POST['input_search']
    );
    setFilter('filter_kelas_dosen',$array_filter);
}

// fungsi_dosen_kelas(tb_data_kelas.kelas_id) as nama_dosen,
$datatable2->setDebug(1);
$i=1;

    $datatable2->setFromQuery("view_nama_kelas
left join jadwal_kuliah using(kelas_id)
left join ruang_ref using(ruang_id)
where view_nama_kelas.kelas_id in(select id_kelas from krs_detail where nim='".getUser()->username."' and 
krs_detail.id_kelas=view_nama_kelas.kelas_id)
$periode");

  $query = $datatable2->execQuery("select view_nama_kelas.nm_matkul,view_nama_kelas.sem_matkul,view_nama_kelas.sks,view_nama_kelas.kls_nama,ruang_ref.nm_ruang,jadwal_kuliah.hari,jadwal_kuliah.jam_mulai,jadwal_kuliah.jam_selesai,
 (select group_concat(distinct nama_gelar separator '#')
    from view_nama_gelar_dosen inner join dosen_kelas on view_nama_gelar_dosen.nip=dosen_kelas.id_dosen where dosen_kelas.id_kelas=view_nama_kelas.kelas_id) as nama_dosen,
view_nama_kelas.jurusan as nama_jurusan,jadwal_kuliah.jadwal_id,view_nama_kelas.kelas_id

 from view_nama_kelas
left join jadwal_kuliah using(kelas_id)
left join ruang_ref using(ruang_id)
where view_nama_kelas.kelas_id in(select id_kelas from krs_detail where nim='".getUser()->username."' and 
krs_detail.id_kelas=view_nama_kelas.kelas_id)
$periode
",$columns);



  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable2->number($i);
    $ResultData[] = $value->nm_matkul.' - SMT '.$value->sem_matkul.' ('.$value->sks.' SKS)';
    if ($value->nm_ruang!='') {
      $ResultData[] = '<a class="peserta-kelas" data-toggle="tooltip" data-title="Lihat Detail Kelas" data-id="'.$value->kelas_id.'" data-jadwal="'.$value->jadwal_id.'">'.$value->kls_nama.'</a>';
    } else {
      $ResultData[] = $value->kls_nama;
    }
    
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = ucwords($value->hari);
    if ($value->nm_ruang!="") {
      $ResultData[] = substr($value->jam_mulai,0,5)." - ".substr($value->jam_selesai,0,5);
    } else {
      $ResultData[] = "";
    }
    
    if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>", $nama_dosen));
        $ResultData[] = $nama_dosen;
    } else {
      $ResultData[] = '';
    }
    $ResultData[] = $value->nama_jurusan;
     if ($value->nm_ruang!='') {
      $ResultData[] = '<a data-toggle="tooltip" title="" class="btn btn-primary btn-xs peserta-kelas" data-original-title="Lihat Detail Kelas" data-id="'.$value->kelas_id.'" data-jadwal="'.$value->jadwal_id.'"><i class="fa fa-eye"></i> Detail</a>';
    } else {
      $ResultData[] = '';
    }

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