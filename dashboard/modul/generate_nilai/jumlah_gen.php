<?php
session_start();
header('Access-Control-Allow-Origin: *');
include "../../inc/config.php";

$json_response = array();

$nim = "";
$nim_delete = "";
$jumlah = 0;


$kelas = "";
$semester = "";
$fakultas = "";
$mulai_smt = "";
$jurusan = "";
$id_matkul = "";
if ($_POST['fakultas']!='all') {
  $fakultas = getProdiFakultas('view_nama_kelas.kode_jur',$_POST['fakultas']);
}

if ($_POST['semester']!='all') {
  $semester = ' and sem_id="'.$_POST['semester'].'"';
}

if ($_POST['jurusan']!='all') {
  $jurusan = ' and view_nama_kelas.kode_jur="'.$_POST['jurusan'].'"';
}
if ($_POST['id_matkul']!='all') {
  $id_matkul = ' and view_nama_kelas.id_matkul="'.$_POST['id_matkul'].'"';
}
if ($_POST['kelas']!='all') {
  $kelas = ' and view_nama_kelas.kelas_id="'.$_POST['kelas'].'"';
}



// Prepare the SQL query with a subquery  
$query = "SELECT COUNT(*) AS jml   
          FROM krs_detail   
          WHERE id_kelas IN (  
              SELECT kelas_id   
              FROM view_nama_kelas inner join view_dosen_kelas_single vd on kelas_id=vd.id_kelas   
              WHERE id_tipe_matkul!='S' and disetujui='1' and nilai_angka is null $semester $jurusan $fakultas $id_matkul $kelas  
          )
          ";

// Fetch the count  
$data = $db->fetch_custom_single($query);  
/*
$db->setDebugQuery(1);
dump($db->getErrorMessageQuery());
echo $db->getErrorMessage();*/
// Use the count as needed  
$jumlah = $data->jml;


$json_response['jumlah'] = $jumlah;

echo json_encode($json_response);

?>
