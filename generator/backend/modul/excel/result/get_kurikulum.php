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
    $total_kurikulum = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM kurikulum 
 inner JOIN jurusan ON kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti",$param);
    if ($total_kurikulum->jumlah>0) {
       $json_response['jumlah'] = $total_kurikulum->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 2;
  $offset = $_GET["offset"];
  $data_kurikulum = $db->query("SELECT kurikulum.sem_id AS mulai_berlaku,kurikulum.nama_kurikulum AS nama_kur,kurikulum.jml_sks_wajib,kurikulum.jml_sks_pilihan,kurikulum.total_sks,jurusan.kode_dikti AS kode_jurusan FROM kurikulum 
 inner JOIN jurusan ON kurikulum.kode_jur=jurusan.kode_jur where 1=1 $and_sem_id $and_kode_dikti limit $offset,$limit",$param);
  foreach ($data_kurikulum as $key) {
      $data_rec = array(
          "mulai_berlaku" => $key->mulai_berlaku,
					"nama_kur" => $key->nama_kur,
					"jml_sks_wajib" => $key->jml_sks_wajib,
					"jml_sks_pilihan" => $key->jml_sks_pilihan,
					"total_sks" => $key->total_sks,
					"kode_jurusan" => $key->kode_jurusan
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);