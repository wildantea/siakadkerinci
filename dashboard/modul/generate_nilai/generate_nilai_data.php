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
$kelas = "";
$semester = "";
$fakultas = "";
$mulai_smt = "";
$jurusan = "";
$id_matkul = "";
if ($_POST['fakultas'] != 'all') {
  $fakultas = getProdiFakultas('vnk.kode_jur', $_POST['fakultas']);
}

if ($_POST['semester'] != 'all') {
  $semester = ' and krs_detail.id_semester="' . $_POST['semester'] . '"';
}

if ($_POST['jurusan'] != 'all') {
  $jurusan = ' and mahasiswa.jur_kode="' . $_POST['jurusan'] . '"';
}
if ($_POST['matakuliah'] != 'all') {
  $id_matkul = ' and matkul.id_matkul="' . $_POST['matakuliah'] . '"';
}
if ($_POST['kelas'] != 'all') {
  $kelas = ' and kelas.kelas_id="' . $_POST['kelas'] . '"';
}

$query = $datatable2->execQuery("select matkul.kode_mk,matkul.nama_mk,mahasiswa.nim,mahasiswa.nama,
matkul.semester,kelas.kls_nama,krs_detail.nilai_angka,krs_detail.nilai_huruf,
krs_detail.id_semester,krs_detail.bobot,view_prodi_jenjang.nama_jurusan,krs_detail.id_krs_detail 
from krs_detail left join kelas on krs_detail.id_kelas=kelas.kelas_id 
inner join matkul on krs_detail.kode_mk=matkul.id_matkul
inner join kurikulum using(kur_id)
inner join mahasiswa on krs_detail.nim=mahasiswa.nim 
inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur 
where 
(matkul.nama_mk not like '%skrip%' and matkul.nama_mk not like '%ppl%'
           and matkul.nama_mk not like '%kuliah%' and matkul.nama_mk not like '%kkn%' and matkul.nama_mk not like '%kuker%' and matkul.nama_mk not like '%Pengabdian%' 
          and matkul.nama_mk not like '%kompre%' and matkul.nama_mk not like '%tesis%' ) 
           and krs_detail.nilai_angka is null

and krs_detail.id_krs_detail is not null $semester $jurusan $id_matkul $kelas", $columns);

//buat inisialisasi array data
$data = array();

$i = 1;
foreach ($query as $value) {

  //array data
  $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected data_selected_id" data-id="' . $value->id_krs_detail . '"> <span></span></label>';
  $ResultData[] = $value->nim;
  $ResultData[] = $value->nama;
  $ResultData[] = $value->kode_mk . ' - ' . $value->nama_mk . ' - SMT ' . $value->semester;
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