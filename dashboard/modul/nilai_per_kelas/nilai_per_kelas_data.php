<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'view_matakuliah_kurikulum.kode_mk',
    'nama_mk',
    'fungsi_dosen_kelas(tb_data_kelas.kelas_id)',
    'krs_disetujui',
    'jml_sudah_nilai',
    'jml_belum_nilai',
    'is_umumkan',
    'is_kunci',
    'ada_komponen',
    'view_matakuliah_kurikulum.kode_jur',
    'tb_data_kelas.kelas_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  $datatable->setDisableSearchColumn("krs_disetujui","jml_sudah_nilai","jml_belum_nilai","view_matakuliah_kurikulum.kode_jur");
  
  //set numbering is true
  //$datatable->setNumberingStatus(0);

  //set order by column
 // $datatable->setOrderBy("tb_data_kelas.kelas_id desc");


  //set group by column
  //$datatable->setGroupBy("tb_data_kelas.kelas_id");

$periode = "";
$fakultas = "";
$matakuliah = "";
$status_nilai = "";
$jur_kode = aksesProdi('view_matakuliah_kurikulum.kode_jur');

  //if filter session enable
if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'prodi' => $_POST['jurusan'],
        'semester' => $_POST['periode'],
        'matakuliah' => $_POST['matakuliah'],
        'input_search' => $_POST['input_search']
      );
      if (hasFakultas()) {
        $array_filter['fakultas'] = $_POST['fakultas'];
        if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
          $fakultas = getProdiFakultas('view_matakuliah_kurikulum.kode_jur',$_POST['fakultas']);
        }
      }
      setFilter('filter_nilai',$array_filter);
    }

    if ($_POST['periode']!='all') {
      $periode = ' and tb_data_kelas.sem_id="'.$_POST['periode'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and view_matakuliah_kurikulum.kode_jur="'.$_POST['jurusan'].'"';
    }

    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and tb_data_kelas.id_matkul="'.$_POST['matakuliah'].'"';


}

if ($_POST['status_penilaian']!='all') {
  if ($_POST['status_penilaian']=='sudah') {
    $status_nilai = 'and (select count(krs_detail_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and nilai_indeks is not null) > 0';
  } elseif ($_POST['status_penilaian']=='belum') {
    $status_nilai = 'and (select count(krs_detail_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and nilai_indeks is null) > 0';
  } elseif ($_POST['status_penilaian']=='diumumkan') {
    $status_nilai = 'and is_umumkan="1"';
  } elseif ($_POST['status_penilaian']=='dikunci') {
    $status_nilai = 'and is_kunci="1"';
  }
}



$datatable->setDebug(1);
$datatable->setFromQuery("tb_data_kelas 
inner join view_matakuliah_kurikulum using(id_matkul)
 where tb_data_kelas.kelas_id is not null $fakultas $periode $jur_kode $matakuliah
");
$query = $datatable->execQuery("select semester,kode_mk,nama_kurikulum,nama_mk,tb_data_kelas.kls_nama,is_umumkan,is_kunci,tb_data_kelas.catatan,tb_data_kelas.kuota,nama_jurusan,tb_data_kelas.ada_komponen,tb_data_kelas.kelas_id,fungsi_jumlah_status_krs(tb_data_kelas.kelas_id,1) as krs_disetujui,fungsi_dosen_kelas(tb_data_kelas.kelas_id) as nama_dosen,
(select count(krs_detail_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and nilai_indeks is not null) as jml_sudah_nilai,
(select count(krs_detail_id) from tb_data_kelas_krs_detail where kelas_id=tb_data_kelas.kelas_id and nilai_indeks is null) as jml_belum_nilai from tb_data_kelas 
inner join view_matakuliah_kurikulum using(id_matkul)
 where tb_data_kelas.kelas_id is not null $fakultas $periode $jur_kode $matakuliah $status_nilai
",$columns);

  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  
  
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk.' - SMT '.$value->semester;
    $ResultData[] = $value->kls_nama;
    if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>- ", $nama_dosen));
        $ResultData[] = '- '.$nama_dosen;
    } else {
      $ResultData[] = '';
    }
    $ResultData[] = $value->krs_disetujui;
    $ResultData[] = $value->jml_sudah_nilai;
    $ResultData[] = $value->jml_belum_nilai;
    if ($value->is_umumkan=='1') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Ya</span>';
    } else {
       $ResultData[] = '<span class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Tidak</span>';
    }

    if ($value->is_kunci=='1') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Ya</span>';
    } else {
       $ResultData[] = '<span class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Tidak</span>';
    }
    if ($value->ada_komponen=='Y') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Ya</span>';
    } else {
       $ResultData[] = '<span class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Tidak</span>';
    }
    $ResultData[] = $value->nama_jurusan;

    if ($value->is_umumkan=='0' && $value->is_kunci=='0' && $value->jml_sudah_nilai > 0) {
      $status_umumkan = '<li><a data-id="'.$enc->enc($value->kelas_id).'" data-status="1" data-toggle="tooltip" title="Umumkan Nilai" class="umumkan"><i class="fa fa-bullhorn"></i>Umumkan Nilai</a></li>';
    } elseif ($value->is_umumkan=='1' && $value->is_kunci=='0' && $value->jml_sudah_nilai > 0) {
      $status_umumkan = '<li><a data-id="'.$enc->enc($value->kelas_id).'" data-status="0" data-toggle="tooltip" title="Sembunyikan Nilai" class="umumkan"><i class="fa fa-bullhorn"></i> Sembunyikan Nilai</a></li>';
    } else {
      $status_umumkan = '';
    }
    $status_kunci = "";
    $status_kunci_nilai = "";

    if ($value->is_kunci=='0') {
      $status_kunci = "Kunci Nilai";
      $status_kunci_nilai = "0";
    } elseif ($value->is_kunci=='1') {
      $status_kunci = "Buka Kunci Nilai";
      $status_kunci_nilai = "buka";
    }


    $edit ="";
    $hapus="";
     if ($db2->userCan("update","nilai-per-kelas")) {
        $edit = '<a data-id='.$enc->enc($value->kelas_id).' href="'.base_index().'nilai-per-kelas/add/'.$enc->enc($value->kelas_id).'" class="btn btn-primary btn-sm " data-toggle="tooltip" title="Input Nilai"><i class="fa fa-pencil"></i> Input</a> ';
     }

     if ($db2->userCan("delete","nilai-per-kelas")) {
         $hapus = '<button data-id="'.$enc->enc($value->kelas_id).'" data-uri="'.base_admin().'"modul/nilai_per_kelas/nilai_per_kelas_action.php" class="btn btn-danger delete-nilai btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_nilai_permahasiswa"><i class="fa fa-trash"></i></button>';
     }
     $ResultData[] = '<div class="btn-group" data-toggle="tooltip" data-title="Ubah Status Nilai"><button class="btn btn-sm btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" >  Status <i class="fa fa-pencil"></i> </button><ul class="dropdown-menu aksi-table" role="menu">'.$status_umumkan.'<li role="separator" class="divider"></li><li><a data-id="'.$enc->enc($value->kelas_id).'" data-status="'.$status_kunci_nilai .'" data-toggle="tooltip" title="'.$status_kunci.'" class="kunci"><i class="fa fa-lock"></i> '.$status_kunci.'</a></li></ul></div> <a href="'.base_index().'nilai-per-kelas/detail/'.$enc->enc($value->kelas_id).'"  class="btn btn-success btn-sm" data-toggle="tooltip" title="Lihat Detail Nilai"><i class="fa fa-eye"></i> Detail</a> '.$edit.$hapus;

    
    $data[] = $ResultData;
    
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>