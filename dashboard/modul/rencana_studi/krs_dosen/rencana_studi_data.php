<?php
session_start();
include "../../../inc/config.php";


$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mulai_smt',
    'total_tagihan',
    'disetujui',
    'jatah_sks',
    'sks_diambil',
    'mahasiswa.jur_kode',
    'mahasiswa.mhs_id',
  );

 $datatable2->setDisableSearchColumn(
    'mulai_smt',
    'total_tagihan',
    'disetujui',
    'jatah_sks',
    'sks_diambil',
    'mahasiswa.jur_kode',
    'mahasiswa.mhs_id',
);

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
/*if ($_POST['status_krs']=='1') {
    $datatable2->setDisableSearchColumn('is_bayar');
} else {
    $datatable2->setDisableSearchColumn('is_bayar','disetujui','jatah_sks','sks_diambil','tb_data_kelas_krs.krs_id');
}*/

  
        //set numbering is true
$datatable2->setNumberingStatus(1);

  //set order by column,last order colum will always be called. So, it's better last order column is primary key. comment this line if you disable order by
  //$datatable2->setOrderBy("tb_data_matakuliah.nama_mk asc","tb_data_kelas.kelas_id desc");


  //set group by column
 // $datatable2->setGroupBy("tb_data_kelas_krs.nim");
  //

$jur_kode = "";

$fakultas = "";
$periode = "";
$mulai_smt = "";
$is_bayar = "";
$disetujui = "";
$mulai_smt_end = "";
$is_bayar = "";

if (isset($_POST['jurusan'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'prodi' => $_POST['jurusan'],
        'semester' => $_POST['periode'],
        'mulai_smt' => $_POST['mulai_smt'],
        'mulai_smt_end' => $_POST['mulai_smt_end'],
        'status_bayar' => $_POST['status_bayar'],
        'disetujui' => $_POST['disetujui'],
        'status_krs' => $_POST['status_krs'],
        //'input_search' => $_POST['input_search']
      );
      if (hasFakultas()) {
        $array_filter['fakultas'] = $_POST['fakultas'];
        if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
          $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
        }
      }
      //setFilter('filter_krs',$array_filter);
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = ' and (left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end.")";
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }
      }

    if ($_POST['disetujui']!='all') {
      $disetujui = ' and (select min(disetujui) from krs_detail where nim=mahasiswa.nim and id_semester="'.$_POST['periode'].'")="'.$_POST['disetujui'].'"';
    }

}

$periode = $_POST['periode'];

$datatable2->setDebug(1);
if ($_POST['status_krs']=='1') {
    $datatable2->setFromQuery("mahasiswa 
where mahasiswa.dosen_pemb='".$_SESSION['username']."' and mahasiswa.nim in(select nim from krs_detail where id_semester='".$_POST['periode']."')

 $fakultas $jur_kode $mulai_smt $is_bayar $disetujui");

  $query = $datatable2->execQuery("select mahasiswa.nim,nama,mulai_smt,
IFNULL(
    (SELECT MIN(disetujui) 
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '".$_POST['periode']."'), 
    0
  ) AS disetujui,
    mahasiswa.jur_kode,
(SELECT id_krs_detail
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '$periode' limit 1
  ) AS id_krs_detail,
IFNULL(
    (SELECT SUM(sks) 
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '$periode'), 
    0
  ) AS sks_diambil,
  IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_tagihan,
  IFNULL(
    (SELECT SUM(potongan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_potongan,
  IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_dibayar,

 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim
and akm.sem_id!='".$_POST['periode']."' and akm.sem_id<='".$_POST['periode']."'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
1 as is_krs,
  IFNULL(
    (SELECT id_affirmasi 
     FROM affirmasi_krs 
     WHERE nim = mahasiswa.nim 
       AND periode = '".$_POST['periode']."' 
     LIMIT 1), 
    0
  ) as affirmasi

 from mahasiswa 
where mahasiswa.dosen_pemb='".$_SESSION['username']."' and mahasiswa.nim in(select nim from krs_detail where id_semester='".$_POST['periode']."')

 $fakultas $jur_kode $mulai_smt $is_bayar $disetujui
",$columns);
} else {

  if (substr($_POST['periode'], -1)=='3') {
    $check_jumlah = "";
  } else {
    $check_jumlah = "and IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0";
  }
  $datatable2->setFromQuery("mahasiswa 
where mahasiswa.dosen_pemb='".$_SESSION['username']."' and mahasiswa.nim not in(select nim from krs_detail where id_semester='".$_POST['periode']."')
and mahasiswa.status='M'
and nim not in(select nim from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim and '".$_POST['periode']."' > semester )
$check_jumlah
 $fakultas $jur_kode $mulai_smt $is_bayar $disetujui");

  $query = $datatable2->execQuery("select mahasiswa.nim,nama,mulai_smt,
     IFNULL(
    (SELECT MIN(disetujui) 
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '".$_POST['periode']."'), 
    0
  ) AS disetujui,mahasiswa.jur_kode,

IFNULL(
    (SELECT SUM(sks) 
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '$periode'), 
    0
  ) AS sks_diambil,

  IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_tagihan,
  IFNULL(
    (SELECT SUM(potongan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_potongan,
  IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) AS total_dibayar,

 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim
and akm.sem_id!='".$_POST['periode']."' and akm.sem_id<='".$_POST['periode']."'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks,
0 as is_krs,

  IFNULL(
    (SELECT id_affirmasi 
     FROM affirmasi_krs 
     WHERE nim = mahasiswa.nim 
       AND periode = '".$_POST['periode']."' 
     LIMIT 1), 
    0
  ) as affirmasi
 from mahasiswa 
where mahasiswa.dosen_pemb='".$_SESSION['username']."' and mahasiswa.nim not in(select nim from krs_detail where id_semester='".$_POST['periode']."') and mahasiswa.status='M'
and nim not in(select nim from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim and '".$_POST['periode']."' > semester )
$check_jumlah
$fakultas $jur_kode $mulai_smt $is_bayar $disetujui
",$columns);


}

$prodi_jenjang = getProdiJenjangOnly();
$jenjang = getJenjang();

  $i=1;

  //buat inisialisasi array data
  $data = array();
  $total_dibayar = 0;
  $login_as = "";
  $array_allowed_login_as = array(
    'admin','root'
  );
  $krs = 0;



  foreach ($query as $value) {
    $is_lunas = "";
    //$total_tagihan = $value->total_akhir_tagihan-$value->total_dibayar;
    //array data
    $ResultData = array();

$is_periode = check_current_periode('krs',$_POST['periode'],$value->jur_kode);
 $is_periode_krs = "yes";
    if ($is_periode==false) { 
      $is_periode_krs = "no";
      $is_periode_krs = "yes";
    }

    if ($value->is_krs=='1' && $is_periode_krs=='yes') {
      $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected data_selected_id" data-id="'.$value->id_krs_detail.'"> <span></span></label>';
    } else {
      $ResultData[] = '';
    }

    if (in_array($_SESSION['group_level'],$array_allowed_login_as)) {
      
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=rencana-studi" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }

    //.$datatable2->number($i);
    $ResultData[] = $value->nim.' '.$login_as;
    $ResultData[] = stripslashes(trimmer($value->nama));
    $ResultData[] = getAngkatan($value->mulai_smt);
    if ($value->total_dibayar > 0) {
      $krs = 1;
      $is_lunas = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Sudah Bayar"><i class="fa fa-check" ></i> Sudah</span>';
    } elseif ($value->affirmasi > 0) {
      $krs = 1;
      $is_lunas = '<span class="btn btn-info btn-xs" data-toggle="tooltip" data-title="Affirmasi"><i class="fa fa-unlock-alt" ></i> Affirmasi</span>';
    }/* elseif ($value->affirmasi=="" && $value->total_tagihan > 0) {
      if ($value->total_tagihan - $value->total_potongan > 0 && $total_dibayar < ($value->total_tagihan - $value->total_potongan)) {
        $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Belum Bayar"><i class="fa fa-times" ></i> Belum</span>';
      } elseif($value->total_tagihan - $value->total_potongan == 0) {
        $ResultData[] = '<span class="btn btn-info btn-xs">Bebas</span>';
      }
    }*/ elseif ($value->affirmasi < 1 && $value->total_tagihan < 1) {
      $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan tidak dibuat"><i class="fa fa-minus-square-o" ></i></span>';
    } elseif ($value->affirmasi < 1 && $value->total_dibayar < 1) {
      $krs = 0;
      $is_lunas = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Belum Bayar"><i class="fa fa-times" ></i> Belum</span>';
    } else {
      $is_lunas = 'salah';
    }

/*    if ($value->sisa_tagihan > 0 && $value->affirmasi=="" && $value->sisa_tagihan < $value->nominal_akhir_tagihan) {
      $ResultData[] = '<span class="btn btn-warning btn-xs" data-title="Baru bayar sebagian" data-toggle="tooltip">Belum Lunas</span>';
    } elseif ($value->affirmasi!="") {
      $ResultData[] = '<span class="btn btn-info btn-xs">Affirmasi</span>';
    } elseif ($value->sisa_tagihan > 0 && $value->affirmasi==""  && $value->sisa_tagihan == $value->nominal_akhir_tagihan) {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Belum Bayar</span>';
    } elseif ($value->sisa_tagihan==0 or $value->sisa_tagihan < 0) {
      $ResultData[] = '<span class="btn btn-success btn-xs">Sudah</span>';
    } else {
      $ResultData[] = 'aneh';
    }*/


    $ResultData[] = $is_lunas;
    if ($value->disetujui>0) {
      $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="KRS Sudah disetujui">Sudah</span>';
    } else {
      if ($value->sks_diambil>0) {
        $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="KRS Belum disetujui">Belum</span>';
      } else {
        $ResultData[] = ' - ';
      }
      
    }
    if ($jenjang[$value->jur_kode]=='S2') {
      $ResultData[] = 24;
    } else {
      $ResultData[] = $value->jatah_sks;
    }
    
    $ResultData[] = $value->sks_diambil;
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    if ($value->is_krs=='1') {
      $ResultData[] = '<a href="'.base_index_new().'rencana-studi/detail/?n='.$enc->enc($value->nim).'&s='.$enc->enc($periode).'" data-toggle="tooltip"  title="" class="btn btn-primary btn-xs " data-original-title="Lihat Detail Rencana Studi"><i class="fa fa-eye"></i> Detail</a>';
    } else {
      if ($krs == 1) {
          if ($is_periode_krs=='yes') {
            $ResultData[] = '<a href="'.base_index_new().'rencana-studi/detail/?n='.$enc->enc($value->nim).'&s='.$enc->enc($periode).'" data-toggle="tooltip" title="" class="btn btn-primary btn-xs " data-original-title="Tambah Rencana Studi"><i class="fa fa-plus"></i></a>';
          } else {
             $ResultData[] = '';
          }
        
      } else {
        if (in_array($_SESSION['group_level'],$array_allowed_login_as)) {
          $ResultData[] = '<a href="'.base_index_new().'rencana-studi/detail/?n='.$enc->enc($value->nim).'&s='.$enc->enc($periode).'" data-toggle="tooltip" title="" class="btn btn-primary btn-xs " data-original-title="Tambah Rencana Studi"><i class="fa fa-plus"></i></a>';
        } else {
          $ResultData[] = '';
        }
      }
      
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