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
    $total_kelas = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM kelas 
 inner JOIN matkul ON kelas.id_matkul=matkul.id_matkul
 inner JOIN semester_ref ON kelas.sem_id=semester_ref.id_semester
 inner join kurikulum on matkul.kur_id=kurikulum.kur_id
 inner JOIN jurusan ON kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti",$param);
   
    if ($total_kelas->jumlah>0) {
       $json_response['jumlah'] = $total_kelas->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
	$limit = 20;
	$offset = $_GET["offset"];
  $data_kelas = $db->query("SELECT kelas.sem_id AS semester,nama_mk,kelas.kls_nama AS nama_kelas,matkul.kode_mk,semester_ref.tgl_mulai AS tgl_mulai_koas,semester_ref.tgl_selesai AS tgl_selesai_koas,jurusan.kode_dikti AS kode_jurusan FROM kelas 
 inner JOIN matkul ON kelas.id_matkul=matkul.id_matkul
 inner JOIN semester_ref ON kelas.sem_id=semester_ref.id_semester
inner join kurikulum on matkul.kur_id=kurikulum.kur_id
 inner JOIN jurusan ON kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti limit $offset,$limit",$param);
  //echo $db->getErrorMessage();
  foreach ($data_kelas as $key) {
      $data_rec = array(
          "semester" => $key->semester,
					"nama_kelas" => $key->nama_kelas,
					"kode_mk" => $key->kode_mk,
					"nama_mk" => $key->nama_mk,
					"tgl_mulai_koas" => $key->tgl_mulai_koas,
					"tgl_selesai_koas" => $key->tgl_selesai_koas,
					"kode_jurusan" => $key->kode_jurusan
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);