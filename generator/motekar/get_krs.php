<?php
header('Access-Control-Allow-Origin: *');

include "../pddikti/config.php";

//table_name 's_semester_prodi'
$semester_prodi = $_GET['semester'];
$nim = $_GET['nim'];
$prodi = '705';
$prodi = $_GET['kode_prodi'];

$sql = "select nim,nip,dosen as nama_dosen,id_semester as semester,vnk.kode_mk,nama_mk,kls_nama
 from view_nama_kelas vnk
inner join dosen_kelas vj on vnk.kelas_id=vj.id_kelas
inner join view_dosen on vj.id_dosen=view_dosen.nip
inner join krs_detail on vnk.kelas_id=krs_detail.id_kelas
where krs_detail.nim='$nim' and krs_detail.id_semester='$semester_prodi'";
// echo "select nim,nip,nama_gelar as nama_dosen,id_semester as semester,vnk.kode_mk,nama_mk,kls_nama
//  from view_nama_kelas vnk
// inner join dosen_kelas vj on vnk.kelas_id=vj.id_kelas
// inner join view_dosen on vj.id_dosen=view_dosen.nip
// inner join krs_detail on vnk.kelas_id=krs_detail.id_kelas
// where krs_detail.nim='$nim' and krs_detail.id_semester='$semester_prodi'"; 

// feed it the sql directly. store all returned rows in an array
$rows = $db->query($sql);

$json_response = array();


foreach($rows as $record){
    // kode_mk,semester,nama_kelas,kode_jurusan
    // mkkurKode	klsSemId	klsNama	mkkurProdiKode
      $row_array['NIM'] = $record->nim;
      $row_array['NIP'] = $record->nip;
      $row_array['NAMA_DOSEN'] = $record->nama_dosen;
      $row_array['GELAR_BELAKANG'] = '';
      $row_array['KODE_MK'] = $record->kode_mk;
      $row_array['KELAS'] = $record->kls_nama;
      $row_array['NAMA_MK'] = $record->nama_mk;
    

    //push the values in the array
    array_push($json_response,$row_array);

}

 echo json_encode($json_response);
?>


