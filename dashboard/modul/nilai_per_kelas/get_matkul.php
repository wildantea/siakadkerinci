<?php
      session_start();
      include "../../inc/config.php";

      $data = $db2->query("select * from view_kelas where kode_jur=? and sem_id=? group by id_matkul",array("kode_jur" => $_POST['program_studi'],'sem_id' => $_POST['periode']));
       echo "<option value='all'>Semua</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id_matkul'>$dt->kode_mk - $dt->nama_mk - Semester $dt->semester</option>";
      }