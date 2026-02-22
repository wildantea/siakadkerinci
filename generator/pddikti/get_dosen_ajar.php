<?php
header("Access-Control-Allow-Origin: *");
include "config.php";

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

  $and_sem_id = "and kelas.sem_id=?";
	$and_kode_dikti = "and kode_dikti=?";

}


if (isset($_GET["ask"])=="jumlah") {
    $total_dosen_ajar = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM dosen_kelas 
 inner JOIN dosen ON dosen_kelas.id_dosen=dosen.nip
 inner JOIN kelas ON dosen_kelas.id_kelas=kelas.kelas_id
 inner JOIN matkul ON kelas.id_matkul=matkul.id_matkul
 inner join kurikulum on matkul.kur_id = kurikulum.kur_id
 inner join jurusan on kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti",$param);
    if ($total_dosen_ajar->jumlah>0) {
       $json_response['jumlah'] = $total_dosen_ajar->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 20;
  $offset = $_GET["offset"];
  $data_dosen_ajar = $db->query("SELECT dosen.nidn,dosen.nama_dosen,matkul.kode_mk,matkul.nama_mk,kelas.kls_nama AS nama_kelas,kelas.sem_id AS semester,dosen_kelas.jml_tm_real,dosen_kelas.jml_tm_renc,dosen_kelas.sks_ajar AS sks,jurusan.kode_dikti AS kode_jurusan FROM dosen_kelas 
 inner JOIN dosen ON dosen_kelas.id_dosen=dosen.nip
 inner JOIN kelas ON dosen_kelas.id_kelas=kelas.kelas_id
 inner JOIN matkul ON kelas.id_matkul=matkul.id_matkul
 inner join kurikulum on matkul.kur_id = kurikulum.kur_id
 inner join jurusan on kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti limit $offset,$limit",$param);
  foreach ($data_dosen_ajar as $key) {
      $data_rec = array(
          "nidn" => $key->nidn,
          "nama_dosen" => $key->nama_dosen,
          "kode_mk" => $key->kode_mk,
          "nama_mk" => $key->nama_mk,
          "nama_kelas" => $key->nama_kelas,
          "semester" => $key->semester,
          "jml_tm_real" => $key->jml_tm_real,
          "jml_tm_renc" => $key->jml_tm_renc,
          "sks" => $key->sks,
          "kode_jurusan" => $key->kode_jurusan
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);