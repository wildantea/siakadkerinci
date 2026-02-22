<?php
session_start();
include "../../inc/config.php";

if ($_POST['id_fakultas']!='all') {
	$fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['id_fakultas']);
} else {
	$fakultas = aksesProdi('mahasiswa.jur_kode');
}

$kode_jur = "";
if (isset($_POST['kode_jur'])) {
	if ($_POST['kode_jur']!='all') {
		$kode_jur = "and mahasiswa.jur_kode='".$_POST['kode_jur']."'";
	}
}

$angkatan_exist = $db->query("select periode from keu_tagihan_mahasiswa
inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim
where keu_tagihan_mahasiswa.id is not null $fakultas $kode_jur
group by keu_tagihan_mahasiswa.periode
order by periode desc");
echo "<option value='all'>Semua</option>";
foreach ($angkatan_exist as $ak) {
  if (get_sem_aktif()==$ak->periode) {
    echo "<option value='$ak->periode' selected>".ganjil_genap($ak->periode)."</option>";
  } else {
    echo "<option value='$ak->periode'>".ganjil_genap($ak->periode)."</option>";
  }
  
}
?>