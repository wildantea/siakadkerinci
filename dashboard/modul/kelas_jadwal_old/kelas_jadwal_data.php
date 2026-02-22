<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nm_matkul',
    'nama_kelas',
    'nm_ruang',
    'waktu',
    'fungsi_dosen_kelas(vnk.kelas_id)',
    'peserta_max',
    'fungsi_get_jml_krs(vnk.kelas_id)',
    'fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id)',
    'jurusan',
    'vnk.kelas_id',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable->disable_search = array('vnk.kelas_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("vnk.kelas_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by kelas.kelas_id";
$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vnk.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vnk.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and vnk.sem_id='".$semester_aktif->id_semester."'";
$matkul_filter = "";
$hari_filter = "";
$jenis_kelas = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vnk.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and vnk.sem_id="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['matkul_filter']!='all') {
    $matkul_filter = ' and vnk.id_matkul="'.$_POST['matkul_filter'].'"';
  }
  if ($_POST['hari_filter']!='all') {
    $hari_filter = ' and vj.hari="'.$_POST['hari_filter'].'"';
  }
  if ($_POST['jenis_kelas']!='all') {
    $jenis_kelas = ' and jenis_kelas.id="'.$_POST['jenis_kelas'].'"';
  }


}


  $query = $datatable->get_custom("select vj.hari,vj.jam_mulai,vj.jam_selesai, sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,
    vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,
    vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
   from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
     where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas ",$columns);
/*echo "select vj.hari,vj.jam_mulai,vj.jam_selesai, sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,
    vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,
    vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
   from view_nama_kelas vnk
left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
     where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas";*/
  //buat inisialisasi array data
  $data = array();

  $i=1;
  $dos = array();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nm_matkul.' - Semester '.$value->sem_matkul;
    $ResultData[] = $value->nama_kelas;
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = $value->hari.",<br>".substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai, 0,5);
    if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>- ", $nama_dosen));
        $ResultData[] = '- '.$nama_dosen;
    } else {
      $ResultData[] = '';
    }

    $ResultData[] = $value->peserta_max;
    $ResultData[] = $value->jml;
    $ResultData[] = $value->belum_disetujui;
    $ResultData[] = $value->jurusan;
   $ResultData[] = $value->nama_jenis_kelas;
    $ResultData[] = $value->kelas_id;

    $data[] = $ResultData;
     $dos = array();
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>