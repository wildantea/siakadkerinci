<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'tb_master_mahasiswa.nim',
    'tb_master_mahasiswa.nama',
    'view_kelas.kode_mk',
    'view_kelas.kls_nama',
    'tb_data_kelas_krs.id_semester',
    'tb_data_kelas_krs_detail.nilai_angka',
    'tb_data_kelas_krs_detail.nilai_huruf',
    'tb_data_kelas_krs_detail.nilai_indeks',
    'view_prodi_jenjang.nama_jurusan',
    'tb_data_kelas_krs_detail.krs_detail_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_data_kelas_krs_detail.krs_id","tb_data_kelas_krs_detail.krs_detail_id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_data_kelas_krs_detail.krs_detail_id desc");


  //set group by column
  //$datatable->setGroupBy("tb_data_kelas_krs_detail.krs_detail_id");

//$datatable->setDebug(1);
  $periode = "";
$fakultas = "";
$matakuliah = "";
$jur_kode = aksesProdi('tb_master_mahasiswa.jur_kode');
$nim = "";
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
      $periode = ' and view_kelas.sem_id="'.$_POST['periode'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and tb_master_mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and tb_data_matakuliah.id_matkul="'.$_POST['matakuliah'].'"';
    }

if ($_POST['value_nim']!='') {
      $nim = ' and tb_data_kelas_krs.nim="'.$_POST['value_nim'].'"';
      $periode = '';
}


  $query = $datatable->execQuery("select view_kelas.kode_mk,view_kelas.nama_mk,tb_master_mahasiswa.nim,tb_master_mahasiswa.nama,view_kelas.nama_mk,view_kelas.semester,view_kelas.kls_nama,tb_data_kelas_krs_detail.nilai_angka,tb_data_kelas_krs_detail.nilai_huruf,tb_data_kelas_krs.id_semester,tb_data_kelas_krs_detail.nilai_indeks,view_prodi_jenjang.nama_jurusan,tb_data_kelas_krs_detail.krs_detail_id from tb_data_kelas_krs_detail inner join view_kelas on tb_data_kelas_krs_detail.kelas_id=view_kelas.kelas_id inner join tb_data_matakuliah on tb_data_kelas_krs_detail.matkul_id=tb_data_matakuliah.id_matkul inner join tb_data_kelas_krs on tb_data_kelas_krs_detail.krs_id=tb_data_kelas_krs.krs_id inner join tb_master_mahasiswa on tb_data_kelas_krs.nim=tb_master_mahasiswa.nim inner join view_prodi_jenjang on tb_master_mahasiswa.jur_kode=view_prodi_jenjang.kode_jur where tb_data_kelas_krs_detail.nilai_huruf!='' $fakultas $periode $jur_kode $matakuliah $nim",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk.' - SMT '.$value->semester;
    $ResultData[] = $value->kls_nama;
    $ResultData[] = $value->id_semester;
    $ResultData[] = $value->nilai_angka;
    $ResultData[] = $value->nilai_huruf;
    $ResultData[] = $value->nilai_indeks;
    $ResultData[] = $value->nama_jurusan;
    $ResultData[] = $value->krs_detail_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>