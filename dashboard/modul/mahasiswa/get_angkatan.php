<?php
      session_start();
      include "../../inc/config.php";

      $jur_kode = $_POST["jur_kode"];

      $data = $db->query("select left(v.mulai_smt,4) as mulai_smt,v.angkatan from view_simple_mhs_data v
where jur_kode=?
group by left(v.mulai_smt,4)
order by v.mulai_smt desc",array("jur_kode" => $jur_kode));
       echo "<option value='all'>Semua</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->mulai_smt'>$dt->mulai_smt</option>";
      }
      