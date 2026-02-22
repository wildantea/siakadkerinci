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
    $total_krs = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM krs_detail 
 inner JOIN matkul ON krs_detail.kode_mk=matkul.id_matkul
left join kurikulum on matkul.kur_id = kurikulum.kur_id
 inner JOIN kelas ON krs_detail.id_kelas=kelas.kelas_id
 inner join jurusan on kurikulum.kode_jur= jurusan.kode_jur
 inner JOIN mahasiswa ON krs_detail.nim=mahasiswa.nim where 1=1 $and_sem_id $and_kode_dikti",$param);
    if ($total_krs->jumlah>0) {
       $json_response['jumlah'] = $total_krs->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 50;
  $offset = $_GET["offset"];
  $data_krs = $db->query("SELECT krs_detail.nim,matkul.kode_mk,kelas.sem_id,kelas.kls_nama,jurusan.kode_dikti,mahasiswa.nama FROM krs_detail 
 inner JOIN matkul ON krs_detail.kode_mk=matkul.id_matkul
left join kurikulum on matkul.kur_id = kurikulum.kur_id
 inner JOIN kelas ON krs_detail.id_kelas=kelas.kelas_id
 inner join jurusan on kurikulum.kode_jur= jurusan.kode_jur
 inner JOIN mahasiswa ON krs_detail.nim=mahasiswa.nim where 1=1 $and_sem_id $and_kode_dikti limit $offset,$limit",$param);
  foreach ($data_krs as $key) {
      $data_rec = array(
          "nim" => $key->nim,
          "kode_mk" => $key->kode_mk,
          "sem_id" => $key->sem_id,
          "kls_nama" => $key->kls_nama,
          "kode_dikti" => $key->kode_dikti,
          "nama" => $key->nama
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);