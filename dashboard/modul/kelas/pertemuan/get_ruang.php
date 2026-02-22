<?php
session_start();
include "../../../inc/config.php";
session_check_json();

//check ruangan tersedia
//kelas 
$kelas = $db2->fetchSingleRow("tb_data_kelas","kelas_id",$_POST['kelas_id']);
$check_jadwal_kelas = array(
'sem_id' => $kelas->sem_id,
'ruang_id' => $_POST['ruang_id']
);

function get_day_id($date) {
    $timestamp = strtotime($date);
    $day = date('N', $timestamp);
    return $day;
}

//get id hari
$id_hari = get_day_id($_POST['tanggal']);
/*dump($check_jadwal_kelas);
dump($id_hari);
$not_in_ruang = array();*/

$check_bentrok_kelas = $db2->query("select * from view_jadwal_kelas where
                                ('".$_POST['jam_mulai']."'>jam_mulai and '".$_POST['jam_mulai']."'<jam_selesai 
                                or '".$_POST['jam_selesai']."'> jam_mulai 
                                and '".$_POST['jam_selesai']."'<jam_selesai 
                                or jam_mulai > '".$_POST['jam_mulai']."' 
                                and jam_mulai <'".$_POST['jam_selesai']."' 
                                or jam_selesai>'".$_POST['jam_mulai']."' 
                                and jam_selesai<'".$_POST['jam_selesai']."' 
                                or jam_mulai='".$_POST['jam_mulai']."' 
                                and jam_selesai='".$_POST['jam_selesai']."') 
                                and id_hari='".$id_hari."' 
                                and kelas_id!='".$_POST['kelas_id']."' 
                                and sem_id=? and ruang_id=?",$check_jadwal_kelas);

foreach ($check_bentrok_kelas as $bentrok) {
	$not_in_ruang[] = $bentrok->ruang_id;
}

$and_ruang = "";
if (!empty($not_in_ruang)) {
	$implode_id = implode(",", $not_in_ruang);
	$and_ruang = "and ruang_id not in($implode_id)";
}


$ruang = $db2->query("select * from view_ruang_prodi
where is_aktif='Y' $and_ruang and kode_jur=?",array('kode_jur' => $_POST['kode_jur']));
if ($ruang->rowCount()>0) {
	echo '<optgroup label="Ruangan Tersedia">';
	foreach ($ruang as $isi) {
	if ($_POST['selected']==$isi->ruang_id) {
		echo "<option value='$isi->ruang_id' selected>$isi->nm_gedung - $isi->nm_ruang</option>";
	} else {
		echo "<option value='$isi->ruang_id'>$isi->nm_gedung - $isi->nm_ruang</option>";
	}
	}
	echo '</optgroup>'; 
} else {
	echo "<option value=''>Ruangan tidak tersedia</option>";
}

?>