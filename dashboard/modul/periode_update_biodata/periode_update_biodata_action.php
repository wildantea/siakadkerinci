<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "up":
   $data = array(
     // "tgl_awal_update" => $_POST["tgl_awal_update"].' 00:00:00',
    //  "tgl_akhir_update" => $_POST["tgl_akhir_update"].' 23:59:59',
   );

 if(isset($_POST["is_submit_biodata"])=="on")
    {
      $aktif = array("is_submit_biodata"=>"Y");
      $data=array_merge($data,$aktif);
    } else {
      $aktif = array("is_submit_biodata"=>"N");
      $data=array_merge($data,$aktif);
    }

    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;

    case 'up_tanggal_massal':
      $data_ids = $_REQUEST["id"];
      $data_id_array = explode(",", $data_ids);
      if(!empty($data_id_array)) {
             if(isset($_POST["is_submit_biodata"])=="on")
              {
                $aktif = 'Y';
              } else {
                $aktif = 'N';
              }

          foreach($data_id_array as $id) {
            $array_update[] = array(
             // 'tgl_awal_update' => $_POST['tgl_awal_update'].' 00:00:00',
            //  'tgl_akhir_update' => $_POST['tgl_akhir_update'].' 23:59:59'
                'is_submit_biodata' => $aktif
            );
            $data_id_update[] = $id;
           }
           $db->updateMulti('mahasiswa',$array_update,'mhs_id',$data_id_update);
      }
      action_response($db->getErrorMessage());
      break;
}

?>