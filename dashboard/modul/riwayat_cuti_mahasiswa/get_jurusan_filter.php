<?php
  session_start();
  include "../../inc/config.php";

    $fakultas = $dec->dec($_POST["fakultas"]);

    $data = $db->query("select * from jurusan where fak_kode=?",array("fak_kode" => $fakultas));

    foreach ($data as $dt) {
      if ($dt->kode_jur == $jur) {
        echo "<option value='".$enc->enc($dt->kode_jur)."' selected>$dt->nama_jur</option>";
      } else{
        echo "<option value='".$enc->enc($dt->kode_jur)."'>$dt->nama_jur</option>"; 
      }
   	} 
 ?>
      