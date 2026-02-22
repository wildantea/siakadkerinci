<?php
session_start();
include "../../inc/config.php";
session_check();
foreach ($db->query("select ruang_ref.ruang_id,ruang_ref.nm_ruang from ruang_ref
inner join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where  ruang_ref.is_aktif='Y' and ruang_ref.gedung_id=? and ruang_ref_prodi.kode_jur=?",array('gedung_id' => $_POST['gedung_id'],'kode_jur' => $_POST['kode_jur'])) as $isi) {
echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
} 
?>