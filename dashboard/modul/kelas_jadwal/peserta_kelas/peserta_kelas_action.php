<?php
session_start();
include "../../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "disapprove-krs":

    $db->update("tb_data_kelas_krs_detail",array('disetujui' => 0), "krs_detail_id",$_POST["id"]);
      $get_kuota = $db->fetchCustomSingle("select fungsi_jumlah_status_krs(kelas_id,1) as disetujui,fungsi_jumlah_status_krs(kelas_id,0) as pending from tb_data_kelas_krs_detail where krs_detail_id='".$_POST["id"]."'");
    $response_kuota = array('approved' => $get_kuota->disetujui,'pending' => $get_kuota->pending);
    action_response($db->getErrorMessage(),$response_kuota);

    break;
  case "approve-krs":
    $db->update("tb_data_kelas_krs_detail",array('disetujui' => 1), "krs_detail_id",$_POST["id"]);
      $get_kuota = $db->fetchCustomSingle("select fungsi_jumlah_status_krs(kelas_id,1) as disetujui,fungsi_jumlah_status_krs(kelas_id,0) as pending from tb_data_kelas_krs_detail where krs_detail_id='".$_POST["id"]."'");
    $response_kuota = array('approved' => $get_kuota->disetujui,'pending' => $get_kuota->pending);
    action_response($db->getErrorMessage(),$response_kuota);

    break;
  case "delete":
    $kelas_id = $db->fetchSingleRow("tb_data_kelas_krs_detail","krs_detail_id",$_POST["id"])->kelas_id;
    $db->delete("tb_data_kelas_krs_detail","krs_detail_id",$_POST["id"]);
    $get_kuota = $db->fetchCustomSingle("select fungsi_jumlah_status_krs($kelas_id,1) as disetujui,fungsi_jumlah_status_krs($kelas_id,0) as pending from tb_data_kelas where kelas_id='$kelas_id'");
    $response_kuota = array('approved' => $get_kuota->disetujui,'pending' => $get_kuota->pending);
    action_response($db->getErrorMessage(),$response_kuota);
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_data_kelas_krs_detail","krs_detail_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nim" => $_POST["nim"],
   );
   
   
   

    
    
    $up = $db->update("tb_data_kelas_krs",$data,"krs_detail_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>