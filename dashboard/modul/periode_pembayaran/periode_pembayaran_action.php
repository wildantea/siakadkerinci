<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  //check exist
  $check_periode_ada = $db->fetch_custom_single("select * from periode_pembayaran where periode_bayar=?",array('periode_bayar' => $_POST["tahun"].$_POST["semester"]));

  if ($check_periode_ada) {
    action_response("Periode ini Sudah ada");
  }
  
  
  $data = array(
      "periode_bayar" => $_POST["tahun"].$_POST["semester"],
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
      "date_created" => date('Y-m-d H:i:s')
  );
  
  
   if(isset($_POST["is_active"])=="on")
    {
      $aktif = array("is_active"=>"Y");
      $data=array_merge($data,$aktif);
      $db->query("update periode_pembayaran set is_active='N'");
    } else {
      $aktif = array("is_active"=>"N");
      $data=array_merge($data,$aktif);
    }


  
   
    $in = $db->insert("periode_pembayaran",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("periode_pembayaran","id_periode_pembayaran",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("periode_pembayaran","id_periode_pembayaran",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "tgl_mulai" => $_POST["tgl_mulai"],
      "tgl_selesai" => $_POST["tgl_selesai"],
   );

   if(isset($_POST["is_active"])=="on")
    {
      $aktif = array("is_active"=>"Y");
      $data=array_merge($data,$aktif);
      $db->query("update periode_pembayaran set is_active='N'");
    } else {
      $aktif = array("is_active"=>"N");
      $data=array_merge($data,$aktif);
    }


  $db->query("update keu_tagihan_mahasiswa set tanggal_awal='".$_POST["tgl_mulai"]." 00:00:00',tanggal_akhir='".$_POST["tgl_selesai"]." 23:59:59' where periode='".$_POST["periode"]."'");
  // echo $db->getErrorMessage();

    
    $up = $db->update("periode_pembayaran",$data,"id_periode_pembayaran",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>