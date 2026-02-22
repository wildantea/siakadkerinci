<?php
session_start();
include "../../inc/config.php";
session_check();

$kode_jur = "";
  $akses_prodi = get_akses_prodi();
  $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
  if ($akses_jur) {
    $kode_jur = "and ruang_ref_prodi.kode_jur in(".$akses_jur->kode_jur.")";
  } else {
  //jika tidak group tidak punya akses prodi, set in 0
    $kode_jur = "and ruang_ref_prodi.kode_jur in(0)";
  }

foreach ($db->query("select ruang_ref.ruang_id,ruang_ref.nm_ruang from ruang_ref
inner join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where  ruang_ref.is_aktif='Y' and ruang_ref.gedung_id=? $kode_jur group by ruang_ref.ruang_id",array('gedung_id' => $_POST['gedung_id'])) as $isi) {
echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
} 
?>