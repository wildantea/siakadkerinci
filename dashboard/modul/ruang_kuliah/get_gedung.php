<?php
session_start();
include "../../inc/config.php";
session_check();
echo '<option value="all">Semua</option>';
foreach ($db->query("select gedung_ref.nm_gedung,gedung_ref.kode_gedung from gedung_ref
inner join ruang_ref on gedung_ref.kode_gedung=ruang_ref.gedung_id
where ruang_ref.kode_jur='".$_POST['prodi']."'") as $isi) {
                  echo "<option value='$isi->kode_gedung'>$isi->nm_gedung</option>";
               }

?>