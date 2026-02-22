<?php
session_start();
include "../../../inc/config.php";

//get jadwal
$jadwal = $db2->fetchCustomSingle("select * from view_jadwal_kelas where jadwal_id=?",array('jadwal_id' => $_POST['id_jadwal']));

$kode_jur = $db2->fetchCustomSingle("select kode_jur,kls_nama from view_kelas where kelas_id=?",array('kelas_id' => $jadwal->kelas_id));

//check tgl awal perkuliahan
$tgl_awal = $db2->fetchCustomSingle("select tgl_awal_kuliah from tb_data_semester_prodi where id_semester=? and kode_jur=?",array('id_semester' => getSemesterAktif(),'kode_jur' => $kode_jur->kode_jur));


$array_day = array(
 'Minggu' => 0,
 'Senin' => 1,
 'Selasa' => 2,
 'Rabu' => 3,
 'Kamis' => 4,
 'Jumat' => 5,
 'Sabtu' => 6
);
//unset($array_day[$jadwal->nama_hari]);
$days = array_values($array_day);
$hari = implode(",",$days);

$date = new DateTime($tgl_awal->tgl_awal_kuliah);

$dayOfWeek = $date->format('N');
$startOfWeek = clone $date;
$startOfWeek->modify('-' . ($dayOfWeek - 0) . ' days');
$endOfWeek = clone $startOfWeek;
$endOfWeek->modify('+6 days');



$startDate = new DateTime($startOfWeek->format('Y-m-d'));
$endDate = new DateTime($endOfWeek->format('Y-m-d'));

$currentDate = clone $startDate;
while ($currentDate <= $endDate) {
    if (getHariFromDate($currentDate->format('Y-m-d'))==$jadwal->nama_hari) {
        $tgl_jadwal = $currentDate->format('Y-m-d');
    }
    $currentDate->modify('+1 day');
}
$tanggal_awal = getHariFromDate($tgl_jadwal).', '.tgl_indo($tgl_jadwal);

//pengajar
$pengajar = "";
foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen where jadwal_id=?",array('jadwal_id' => $_POST['id_jadwal'])) as $isi) {
     $pengajar.="<option value='$isi->nip' selected>$isi->nama_gelar</option>";
}
action_response('',array('tanggal_awal' => $tanggal_awal,'disabled_day' => $array_day[$jadwal->nama_hari],'lang' => 'id','pengajar' => $pengajar,'tgl_awal' => $tgl_jadwal,'ruang' => $jadwal->nm_ruang,'ruang_id' => $jadwal->ruang_id));

