<?php
      session_start();
      include "../../../inc/config.php";

      $kur_id = $_POST["kur_id"];

      $current_id_mat = $_POST['id_mat_setara'];
      $not_in_idmatkul = "";
      if ($current_id_mat!="") {
      	$not_in_idmatkul = "and id_matkul not in($current_id_mat)";
      }

      $data = $db->query("select * from matkul where kur_id=? $not_in_idmatkul",array("kur_id" => $kur_id));
       echo "<option value='all'>Semua </option>";
      foreach ($data as $dt) {
      	echo "<option value='$dt->id_matkul'>$dt->kode_mk - $dt->nama_mk</option>";
        
      }
      