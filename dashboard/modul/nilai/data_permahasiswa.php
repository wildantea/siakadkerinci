<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'matkul.kode_mk',
    'kelas.kls_nama',
    'krs_detail.id_semester',
    'krs_detail.nilai_angka',
    'krs_detail.nilai_huruf',
    'krs_detail.bobot',
    'view_prodi_jenjang.nama_jurusan'
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_krs_detail.krs_id","tb_data_kelas_krs_detail.krs_detail_id");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("tb_data_kelas_krs_detail.krs_detail_id desc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_krs_detail.krs_detail_id");

$datatable2->setDebug(1);
$mulai_smt = "";
$mulai_smt_end = "";
$periode = "";
$fakultas = "";
$matakuliah = "";
$jur_kode = "";
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_kode = "and kurikulum.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and kurikulum.kode_jur in(0)";
}
$nim = "";
$status_penilaian = '';

    if ($_POST['periode']!='all') {
      $periode = ' and krs_detail.id_semester="'.$_POST['periode'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and kurikulum.kode_jur="'.$_POST['jurusan'].'"';
    }

    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and krs_detail.id_matkul="'.$_POST['matakuliah'].'"';
    }

    if ($_POST['status_penilaian']!='all') {
      if ($_POST['status_penilaian']=='sudah') {
        $status_penilaian = "and krs_detail.nilai_huruf!=''";
      } else {
        $status_penilaian = "and (krs_detail.nilai_huruf is null or krs_detail.nilai_huruf='')";
      }
      
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }

      }
if ($_POST['value_nim']!='') {
      $nim = ' and krs_detail.nim="'.$_POST['value_nim'].'"';
}
  $query = $datatable2->execQuery("select matkul.kode_mk,matkul.nama_mk,mahasiswa.nim,mahasiswa.nama,
matkul.semester,kelas.kls_nama,krs_detail.nilai_angka,krs_detail.nilai_huruf,
krs_detail.id_semester,krs_detail.bobot,view_prodi_jenjang.nama_jurusan,krs_detail.id_krs_detail 
from krs_detail left join kelas on krs_detail.id_kelas=kelas.kelas_id 
inner join matkul on krs_detail.kode_mk=matkul.id_matkul
inner join kurikulum using(kur_id)
inner join mahasiswa on krs_detail.nim=mahasiswa.nim 
inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur 
where krs_detail.id_krs_detail is not null $periode $jur_kode $matakuliah $nim $status_penilaian $mulai_smt",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
 $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->kode_mk.' - '.$value->nama_mk.' - SMT '.$value->semester;
    $ResultData[] = $value->kls_nama;
    $ResultData[] = $value->id_semester;
    $ResultData[] = $value->nilai_angka;
    $ResultData[] = $value->nilai_huruf;
    $ResultData[] = $value->bobot;
    $ResultData[] = $value->nama_jurusan;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>