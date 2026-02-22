<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'nm_matkul',
    'kls_nama',
    'fungsi_get_jml_krs(vnk.kelas_id)',
    'fungsi_sudah_dinliai(vnk.kelas_id)',
    'fungsi_belum_dinilai(vnk.kelas_id)',
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

}

// echo "select vnk.nm_matkul,vnk.kls_nama,sem_matkul,fungsi_get_jml_krs(vnk.kelas_id) as peserta,
//     fungsi_belum_dinilai(vnk.kelas_id) as belum,
//   fungsi_sudah_dinliai(vnk.kelas_id) as sudah,
//   vnk.jurusan,
// fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,
// sem_id,
//   vnk.kelas_id
//    from view_nama_kelas vnk
//      where vnk.kelas_id is not null $sem_filter $jur_filter $matkul_filter";

  $query = $datatable->get_custom("select vnk.nm_matkul,vnk.kls_nama,sem_matkul,fungsi_get_jml_krs(vnk.kelas_id) as peserta,
    fungsi_belum_dinilai(vnk.kelas_id) as belum,
  fungsi_sudah_dinliai(vnk.kelas_id) as sudah,
  vnk.jurusan,
fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,
sem_id,
  vnk.kelas_id
   from view_nama_kelas vnk
     where vnk.id_tipe_matkul='S' and vnk.kelas_id is not null $sem_filter $jur_filter $matkul_filter ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $dos = array();
  foreach ($query as $value) {
    $input='';
    //if ($value->id_tipe_matkul=='S') {
       $input ='<a href="nilai/add_nilai/'.en($value->kelas_id)."/".$value->sem_id.'" data-id="'.$value->kelas_id.'" class="btn edit_data btn-primary btn-sm"><i class="fa fa-pencil"></i> Input Nilai</a>';
    //}
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nm_matkul.' - Semester '.$value->sem_matkul;
    $ResultData[] = $value->kls_nama;
    if ($value->nama_dosen!='') {
        $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
        $nama_dosen = trim(implode("<br>- ", $nama_dosen));
        $ResultData[] = '- '.$nama_dosen;
    } else {
      $ResultData[] = '';
    }
    $ResultData[] = $value->peserta;
    $ResultData[] = $value->sudah;
    $ResultData[] = $value->belum;
    $ResultData[] = $value->jurusan;
    if (getSemesterAktif()==$value->sem_id) {
      $ResultData[] = $input.'<a target="_BLANK" href="'.base_url().'dashboard/modul/nilai/cetak_nilai.php?id_kelas='.en($value->kelas_id).'&jur=&sem" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';
    } else {
      $ResultData[] = '';
    }
    
    $data[] = $ResultData;
     $dos = array();
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>