<?php
header('Access-Control-Allow-Origin: *');

include "../pddikti/config.php";

$sem_aktif = $db->fetchCustomSingle("select id_semester from semester_ref where aktif='1'");

//table_name 's_semester_prodi'
$semester_prodi = $sem_aktif->id_semester;

$sql = "select mahasiswa.nim,mahasiswa.nama,mulai_smt,jur_kode from mahasiswa 
where
nim in(select nim from krs_detail where id_semester='$semester_prodi')";
// feed it the sql directly. store all returned rows in an array
$rows = $db->query($sql);

$json_response = array();

$json_response['data'] = array();

$json_response['id_semester'] = $semester_prodi;

foreach($rows as $record){
    // kode_mk,semester,nama_kelas,kode_jurusan
    // mkkurKode	klsSemId	klsNama	mkkurProdiKode
      $row_array['nim'] = $record->nim;
      $row_array['nama'] = $record->nama;
      $row_array['angkatan'] = $record->mulai_smt;
      $row_array['jur_kode'] = $record->jur_kode;
    //push the values in the array
    array_push($json_response['data'],$row_array);

}

 echo json_encode($json_response);
?>


