<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();

$and_sem_id= "";
$and_kode_dikti= "";

if ($_POST['kode_dikti']!='all') {
  //param here
  $param = array(
    "sem_id" => $_POST["sem_id"],
		"kode_dikti" => $_POST["kode_dikti"]
  );

  $and_sem_id = "and sem_id=?";
	$and_kode_dikti = "and kode_dikti=?";

}

if (isset($_GET["ask"])=="jumlah") {
    $total_nilai_perkuliahan = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM krs_detail 
 inner JOIN mahasiswa ON krs_detail.nim=mahasiswa.nim
 inner JOIN matkul ON krs_detail.kode_mk=matkul.id_matkul
 inner JOIN kelas ON krs_detail.id_kelas=kelas.kelas_id
 inner JOIN jurusan ON matkul.jurusan_kode=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti",$param);
    if ($total_nilai_perkuliahan->jumlah>0) {
       $json_response['jumlah'] = $total_nilai_perkuliahan->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 200;
  $offset = $_GET["offset"];
  $data_nilai_perkuliahan = $db->query("SELECT krs_detail.nim,krs_detail.bobot AS nilai_indek,krs_detail.nilai_huruf,krs_detail.nilai_angka,mahasiswa.nama,matkul.kode_mk,matkul.nama_mk,kelas.sem_id AS semester,kelas.kls_nama AS nama_kelas,jurusan.kode_dikti AS kode_jurusan FROM krs_detail 
 inner JOIN mahasiswa ON krs_detail.nim=mahasiswa.nim
 inner JOIN matkul ON krs_detail.kode_mk=matkul.id_matkul
 inner JOIN kelas ON krs_detail.id_kelas=kelas.kelas_id
 inner JOIN jurusan ON matkul.jurusan_kode=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti limit $offset,$limit",$param);
  foreach ($data_nilai_perkuliahan as $key) {
      $data_rec = array(
          "nim" => $key->nim,
					"nilai_indek" => $key->nilai_indek,
					"nilai_huruf" => $key->nilai_huruf,
					"nilai_angka" => $key->nilai_angka,
					"nama" => $key->nama,
					"kode_mk" => $key->kode_mk,
					"nama_mk" => $key->nama_mk,
					"semester" => $key->semester,
					"nama_kelas" => $key->nama_kelas,
					"kode_jurusan" => $key->kode_jurusan
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);