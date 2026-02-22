<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();

$and_kode_dikti= "";
$and_sem_id= "";

if ($_POST['sem_id']!='all') {
  //param here
  $param = array(
    "kode_dikti" => $_POST["kode_dikti"],
		"sem_id" => $_POST["sem_id"]
  );

  $and_kode_dikti = "and kode_dikti=?";
	$and_sem_id = "and sem_id=?";

}

if (isset($_GET["ask"])=="jumlah") {
    $total_matakuliah = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM matkul 
 inner JOIN jurusan ON matkul.jurusan_kode=jurusan.kode_jur
 inner JOIN kurikulum ON matkul.kur_id=kurikulum.kur_id where 1=1 $and_kode_dikti $and_sem_id",$param);
    if ($total_matakuliah->jumlah>0) {
       $json_response['jumlah'] = $total_matakuliah->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 20;
  $offset = $_GET["offset"];
  $data_matakuliah = $db->query("SELECT matkul.id_tipe_matkul AS jns_mk,matkul.kode_mk,matkul.semester,matkul.nama_mk,matkul.sks_tm,matkul.sks_prak,matkul.sks_prak_lap,matkul.sks_sim,matkul.tgl_mulai_efektif,matkul.tgl_akhir_efektif,matkul.a_wajib AS wajib,matkul.metode_pelaksanaan_kuliah,jurusan.kode_dikti AS kode_jurusan,kurikulum.sem_id AS tahun FROM matkul 
 inner JOIN jurusan ON matkul.jurusan_kode=jurusan.kode_jur
 inner JOIN kurikulum ON matkul.kur_id=kurikulum.kur_id where 1=1 $and_kode_dikti $and_sem_id limit $offset,$limit",$param);
  foreach ($data_matakuliah as $key) {
      $data_rec = array(
          "jns_mk" => $key->jns_mk,
					"kode_mk" => $key->kode_mk,
					"semester" => $key->semester,
					"nama_mk" => $key->nama_mk,
					"sks_tm" => $key->sks_tm,
					"sks_prak" => $key->sks_prak,
					"sks_prak_lap" => $key->sks_prak_lap,
					"sks_sim" => $key->sks_sim,
					"tgl_mulai_efektif" => $key->tgl_mulai_efektif,
					"tgl_akhir_efektif" => $key->tgl_akhir_efektif,
					"wajib" => $key->wajib,
					"metode_pelaksanaan_kuliah" => $key->metode_pelaksanaan_kuliah,
					"kode_jurusan" => $key->kode_jurusan,
					"tahun" => $key->tahun
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);