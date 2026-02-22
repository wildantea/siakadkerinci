<?php
      session_start();
      include "../../inc/config.php";

      $jur_filter = $_POST["jur_filter"];
      $sem_filter = $_POST['sem_filter'];
      	$data = $db->query("select kls_nama,kelas_id from view_nama_kelas vnk
      		where vnk.sem_id=? and vnk.kode_jur=? and vnk.id_matkul=?
      		group by vnk.id_matkul",array("sem_id" => $sem_filter,'kode_jur' => $jur_filter,'id_matkul' => $_POST['id_mat']));
      	echo "<option value='all'>Semua</option>";
      	foreach ($data as $dt) {
      		echo "<option value='$dt->kelas_id'>$dt->kls_nama</option>";
      	}

     
      