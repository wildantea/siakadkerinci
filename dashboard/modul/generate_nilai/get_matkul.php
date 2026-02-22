<?php
      session_start();
      include "../../inc/config.php";

      $jur_filter = $_POST["jur_filter"];
      $sem_filter = $_POST['sem_filter'];
      	$data = $db->query("select vnk.nm_matkul,vnk.id_matkul from view_nama_kelas vnk
      		where vnk.sem_id=? and vnk.kode_jur=? and kelas_id in(select id_kelas from krs_detail)
      		group by vnk.id_matkul",array("sem_id" => $sem_filter,'kode_jur' => $jur_filter));
      	echo "<option value='all'>Semua</option>";
      	foreach ($data as $dt) {
      		echo "<option value='$dt->id_matkul'>$dt->nm_matkul</option>";
      	}

     
      