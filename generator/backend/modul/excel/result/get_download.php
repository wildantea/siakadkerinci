<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

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
    $total_download = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM mhs_pt  where 1=1 $and_mulai_smt",$param);
    if ($total_download->jumlah>0) {
       $json_response['jumlah'] = $total_download->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
  $limit = 5;
  $offset = $_GET["offset"];
  $data_download = $db->query("SELECT mhs_pt.mulai_smt,mhs_pt.tgl_masuk_sp,mhs_pt.id_jalur_masuk FROM mhs_pt  where 1=1 $and_mulai_smt limit $offset,$limit",$param);
  foreach ($data_download as $key) {
      $data_rec = array(
          "mulai_smt" => $key->mulai_smt,
					"tgl_masuk_sp" => $key->tgl_masuk_sp,
					"id_jalur_masuk" => $key->id_jalur_masuk
      );
      array_push($json_response, $data_rec);
  }
}

echo json_encode($json_response);