<?php
/**
 * looping prodi berdasarkan hak akses untuk input dan edit form
 * @return [type] [description]
 */
function loopingProdiForm($selected="") {
  $array_selected = array();
  if ($selected!="") {
    $array_selected = explode(",", $selected);
  }
  global $db;
  $akses_prodi = get_akses_prodi();
  if ($akses_prodi) {

  $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
    if ($jurusan->rowCount()<1) {
        echo "<option value='' selected>Group User Ini Belum Punya Akses Prodi</option>";
    } else if ($jurusan->rowCount()==1) {
      foreach ($jurusan as $dt) {
        echo "<option value='$dt->kode_jur' selected>$dt->jurusan</option>";
      }
    } else {
      echo "<option value='all'>Semua</option>";
      foreach ($jurusan as $dt) {
        if (in_array($dt->kode_jur, $array_selected)) {
          echo "<option value='$dt->kode_jur' selected>$dt->jurusan</option>";
        } else {
          echo "<option value='$dt->kode_jur'>$dt->jurusan</option>";
        }
        
      }
    }

  } else {
    echo "<option value='' selected>Akun ini belum punya akses prodi</option>";
  }

}