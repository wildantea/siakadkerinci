<?php
session_start();
include "../../inc/config.php";

$columns = array(
  'kategori_konsultasi',
  'pertanyaan',
  'jawaban',
  'id_semester',
  'id',
);

$datatable2->setNumberingStatus(0);

$datatable2->setDebug(1);
//set group by column
//$new_table->group_by = "group by bimbingan_dosen_pa.id";
$datatable2->setOrderBy("id_semester desc");
$nip = $_SESSION['username'];
$semester = $_POST['semester'];
$dijawab = "";

if ($_POST['dijawab'] != 'all') {
  if ($_POST['dijawab'] == '1') {
    $dijawab = "and jawaban is not null";
  } else {
    $dijawab = "and jawaban is null";
  }
}

$semester = $_POST['semester'];
$query = $datatable2->execQuery("
select * from bimbingan_dosen_pa
inner join view_simple_mhs_data using(nim)
 where nip='" . $nip . "' and id_semester='$semester' $dijawab", $columns);

//buat inisialisasi array data
$data = array();

$i = 1;
foreach ($query as $value) {

  //array data
  $ResultData = array();
  $ResultData[] = $value->nim;
  $ResultData[] = $value->nama;
  if ($value->kategori_konsultasi == '1') {
    $jenis = 'Awal Semester';
  } elseif ($value->kategori_konsultasi == '2') {
    $jenis = 'Tengah Semester';
  } else {
    $jenis = 'Akhir Semester';
  }
  $ResultData[] = $jenis;
  $ResultData[] = $value->pertanyaan . "<br><span style='color:#999'>" . tgl_time($value->tanggal_tanya) . "</span>";
  if ($value->jawaban == '') {
    $ResultData[] = '<a href="' . base_index_new() . 'konsultasi/lihat/' . $value->nim . '" class="label label-warning" data-toggle="tooltip" data-title="klik untuk melihat detail dan menjawab konsultasi">Belum Jawab</a>';
  } else {
    $ResultData[] = $value->jawaban . "<br><span style='color:#999'>" . tgl_time($value->tanggal_jawab) . "</span>";
  }


  $ResultData[] = getAngkatan($value->id_semester);

  $ResultData[] = '<a href="' . base_admin() . '/modul/konsultasi/cetak_single.php?nim=' . $value->nim . '&id_semester=' . $value->id_semester . '" target="_blank" data-toggle="tooltip" title="Cetak" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>';


  $data[] = $ResultData;
  $i++;
}

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>