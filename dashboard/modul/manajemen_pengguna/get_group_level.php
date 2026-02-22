<?php
      session_start();
      include "../../inc/config.php";
      $data = $db->query("select * from sys_group_users where level=?",array('level' => $_POST['jenis_akun']));
      echo "<option value='all'>Semua</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id'>$dt->level_name</option>";
      }
      