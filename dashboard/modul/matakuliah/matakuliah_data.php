<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'kurikulum.nama_kurikulum',
    'sem_id',
    'matkul.kode_mk',
    'matkul.nama_mk',
    'matkul.semester',
    'total_sks',
    'jml_syarat',
    'jml_setara',
    'a_wajib',
    'view_prodi_jenjang.nama_jurusan',
    'matkul.id_matkul',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  $datatable2->setDisableSearchColumn("total_sks","matkul.id_matkul");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //disable ordering by default on first draw
  //$datatable2->setOrderingStatus(0);


  //second priority order by after default datatable2 $_POST['order'] request, datatable2 ordering default is false, then this will be the only order by
//$datatable2->setOrderBy("id_matkul desc");

$jur_kode = aksesProdi('view_prodi_jenjang.kode_jur');

$kur_id = "";
$sifat_mk = "";
$semester = "";
$fakultas = "";
  
  if (isset($_POST['jurusan'])) {

  if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
    $fakultas = getProdiFakultas('view_prodi_jenjang.kode_jur',$_POST['fakultas']);
  }

  if ($_POST['jurusan']!='all') {
    $jur_kode = ' and view_prodi_jenjang.kode_jur="'.$_POST['jurusan'].'"';
  }

  if ($_POST['kurikulum']!='all') {
    $kur_id = ' and kurikulum.kur_id="'.$_POST['kurikulum'].'"';
  }
  if ($_POST['sifat_mk']!='all') {
    $sifat_mk = ' and a_wajib="'.$_POST['sifat_mk'].'"';
  }
  if ($_POST['semester']!='all') {
    $semester = ' and matkul.semester="'.$_POST['semester'].'"';
  }
}

function getJenisMk() {
  global $db2;
  $jns_keluars = $db2->query("select * from tipe_matkul");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_tipe_matkul] = $jns_keluar->tipe_matkul;
  }
  return $data_jenis_keluar;
}

$jenis_mk = getJenisMk();
$datatable2->setDebug(1);
  //set group by column
  //$datatable2->setGroupBy("matkul.id_matkul");

  $query = $datatable2->execQuery("select jml_setara as setara,id_tipe_matkul,jml_syarat as prasyarat,sem_id,a_wajib,kurikulum.nama_kurikulum,matkul.kode_mk,matkul.nama_mk,matkul.semester,matkul.sks_prak,matkul.sks_prak_lap,matkul.sks_sim,
    (matkul.sks_tm+matkul.sks_prak+matkul.sks_prak_lap+matkul.sks_sim) as total_sks,view_prodi_jenjang.nama_jurusan,matkul.id_matkul from matkul inner join kurikulum on matkul.kur_id=kurikulum.kur_id inner join view_prodi_jenjang on kurikulum.kode_jur=view_prodi_jenjang.kode_jur
  left join view_syarat_matkul on matkul.id_matkul=view_syarat_matkul.id_mk
  left join view_matkul_setara on matkul.id_matkul=view_matkul_setara.id_mk where matkul.id_matkul is not null $fakultas $jur_kode $kur_id $sifat_mk $semester",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable2->number($i);
  
    $ResultData[] = $value->nama_kurikulum;
    $ResultData[] = $value->sem_id;

    $ResultData[] = '<a data-id="'.$value->id_matkul.'"  data-toggle="tooltip" data-title="Lihat Detail Matakuliah" style="cursor:pointer" class="lihat-matkul">'.$value->kode_mk.'</a>';
    $ResultData[] = $value->nama_mk;
     $ResultData[] = (in_array($value->id_tipe_matkul,array_keys($jenis_mk)))?$jenis_mk[$value->id_tipe_matkul]:'';
    $ResultData[] = $value->semester;
    $ResultData[] = $value->total_sks;
    $ResultData[] = $value->prasyarat;
    $ResultData[] = $value->setara;
    if ($value->a_wajib=='1') {
      $ResultData[] = '<span class="btn btn-xs btn-success">Wajib</span>';
    } else {
      $ResultData[] = '<span class="btn btn-xs btn-warning">Pilihan</span>';
    }
    $ResultData[] = $value->nama_jurusan;
    $ResultData[] = $value->id_matkul;

    $data[] = $ResultData;
    $i++;
  }



//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>