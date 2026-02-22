<?php
ini_set('display_error', 1);
session_start();
include "../../inc/config.php";
session_check_json();

      $kls_id = explode("===", $_POST['check_id']);
      $id_kelas = $kls_id[0];
    $bentrok = "";
      $get_kuota = $db->fetch_custom_single("select vj.kuota,fungsi_get_jml_krs_all(vj.kelas_id) as terisi,vj.hari,vj.jam_mulai,vj.jam_selesai
 from view_jadwal vj where vj.kelas_id='$id_kelas'");
      /*echo "select vj.kuota,fungsi_get_jml_krs_all(vj.kelas_id) as terisi,vj.hari,vj.jam_mulai,vj.jam_selesai
 from view_jadwal vj where vj.kelas_id='$id_kelas'";*/


      $kuota = "<span class='btn btn-info btn-xs'>".$get_kuota->terisi."/".$get_kuota->kuota." ".ucwords($get_kuota->hari)." $get_kuota->jam_mulai - $get_kuota->jam_selesai </span>";

    $id_kelas_array = $_REQUEST["id_kelas"];
    foreach ($id_kelas_array as $kel) {
    	$kels = explode("===", $kel);
    	$imps[] = $kels[0];
    }
    $final_implode = array_diff($imps, [$id_kelas]);
    if (!empty($final_implode)) {
    	    $imp = implode(",", $final_implode);
	    $data_kelas_check = $db->fetch_single_row("view_jadwal","kelas_id",$id_kelas);
	    $loop_jadwal = $db->query("select * from view_jadwal where kelas_id in($imp)");
  
	    foreach ($loop_jadwal as $jd) {
	             $check_bentrok_kelas = $db->query("select * from view_jadwal where
	        ('".$data_kelas_check->jam_mulai."'>=jam_mulai and '".$data_kelas_check->jam_mulai."'<jam_selesai or '".$data_kelas_check->jam_selesai."'> jam_mulai and '".$data_kelas_check->jam_selesai."'<jam_selesai or jam_mulai 
	        > '".$data_kelas_check->jam_mulai."' and jam_mulai <'".$data_kelas_check->jam_selesai."' or jam_selesai>'".$data_kelas_check->jam_mulai."' and jam_selesai<'".$data_kelas_check->jam_selesai."' or jam_mulai='".$data_kelas_check->jam_mulai."' and jam_selesai='".$data_kelas_check->jam_selesai."' ) and hari like '%".$data_kelas_check->hari."' and kelas_id='".$jd->kelas_id."'");
	      if ($check_bentrok_kelas->rowCount()>0) {
	      	$bentrok = "Kelas matakuliah Bentrok dengan ".$jd->matkul_jadwal;
	      } else {
	      	$bentrok = "";
	      }
	    }
    }


    $array_status = array('kuota' => $kuota,'bentrok' => $bentrok);

    action_response($db->getErrorMessage(),$array_status);

