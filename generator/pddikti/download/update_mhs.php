<?php
header("Access-Control-Allow-Origin: *");
include "../config.php";

$json_response = array();
$param = array();

$and_mulai_smt= "";

if ($_POST['mulai_smt']!='all') {
  //param here
  $param = array(
    "mulai_smt" => $_POST["mulai_smt"]
  );

  $and_mulai_smt = "and mulai_smt=?";

}

if (isset($_GET["ask"])=="jumlah") {
    $total_download = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM view_simple_mhs  where 1=1 $and_mulai_smt and id_jenjang='30'",$param);
    if ($total_download->jumlah>0) {
       $json_response['jumlah'] = $total_download->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 5;
  $offset = $_GET["offset"];
  $data_download = $db->query("SELECT mhs_id,nim,mulai_smt FROM mahasiswa inner join jurusan on jur_kode=kode_jur  where 1=1 $and_mulai_smt  and id_jenjang='30' limit $offset,$limit",$param);
  foreach ($data_download as $key) {
      $data_rec = array(
          "nim" => $key->nim,
          "mhs_id" => $key->mhs_id,
          "mulai_smt" => $key->mulai_smt
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);