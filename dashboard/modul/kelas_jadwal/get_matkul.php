<?php
      session_start();
      include "../../inc/config.php";

      $jur_filter = $_POST["jur_filter"];
      $sem_filter = $_POST['sem_filter'];
      if ($_SESSION['group_level']=='dosen') {
      	$data = $db->query("select * from view_nama_kelas vnk join dosen_kelas dk
on dk.id_kelas=vnk.kelas_id where dk.id_dosen=? and vnk.sem_id=? group by vnk.id_matkul",
    array("id_dosen" => $_SESSION['username'],'sem_id' => $sem_filter));
      	echo "<option value='all'>Semua</option>";
      	foreach ($data as $dt) {
      		echo "<option value='$dt->id_matkul'>$dt->nm_matkul - $dt->jurusan</option>";
      	}
      }else{
      	$data = $db->query("select vnk.nm_matkul,vnk.id_matkul from view_nama_kelas vnk
      		where vnk.sem_id=? and vnk.kode_jur=?
      		group by vnk.id_matkul",array("sem_id" => $sem_filter,'kode_jur' => $jur_filter));
      	echo "<option value='all'>Semua</option>";
      	foreach ($data as $dt) {
      		echo "<option value='$dt->id_matkul'>$dt->nm_matkul</option>";
      	}
      }

     
      