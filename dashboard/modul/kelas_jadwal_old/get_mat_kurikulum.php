<?php
      session_start();
      include "../../inc/config.php";

      $kur_id = $_POST["kur_id"];

      $data = $db->query("select matkul.id_matkul,matkul.kode_mk,semester, matkul.nama_mk from matkul
inner join kurikulum on matkul.kur_id=kurikulum.kur_id where kurikulum.kur_id=?",array("kur_id" => $kur_id));
      foreach ($data as $dt) {
        echo "<option value='$dt->id_matkul'>$dt->kode_mk - $dt->nama_mk - Semester $dt->semester</option>";
      }
      