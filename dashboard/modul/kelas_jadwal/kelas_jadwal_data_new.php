<?php
session_start();
include "../../inc/config.php";
//(select group_concat(distinct nama_dosen separator "#")
// from tb_master_dosen inner join tb_data_kelas_dosen on tb_master_dosen.nip=tb_data_kelas_dosen.nip_dosen where tb_data_kelas_dosen.kelas_id=tb_data_kelas.kelas_id)
$columns = array(
    'nm_matkul',
    'nama_kelas',
    'jam_mulai',
    'nm_ruang',
    '(select group_concat(distinct nama_dosen separator "#") from view_dosen_kelas where view_dosen_kelas.id_kelas=vnk.kelas_id)',
    'peserta_max',
    "(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='1' and k.id_kelas=vnk.kelas_id)",
    "(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='0' and k.id_kelas=vnk.kelas_id)",
    'jurusan',
    'vnk.kelas_id',
  );

  //if you want to exclude column for searching, put columns name in array
$datatable2->disableSearch = array(
   'waktu',
   'jurusan',
    "(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='1' and k.id_kelas=vnk.kelas_id)",
    "(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='0' and k.id_kelas=vnk.kelas_id)"

);
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->set_order_by("vnk.kelas_id");

  //set order by type
 // $datatable2->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by kelas.kelas_id";
$jur_filter = "";
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
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('vnk.kode_jur',$_POST['fakultas']);
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

$datatable2->setDebug(1);
$datatable2->setFromQuery("view_nama_kelas vnk
  left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
  inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id 
  where vnk.kelas_id is not null
$sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas $fakultas");

  $query = $datatable2->execQuery("select vj.hari,vj.jam_mulai,vj.jam_selesai, sem_matkul,nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,
       concat(jam_mulai,' - ',jam_selesai) as jam,
  vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,vnk.sks,
  vnk.kelas_id,
(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='1' and k.id_kelas=vnk.kelas_id) as jml, 
(select count(k.id_krs_detail) from krs_detail k
where k.disetujui='0' and k.id_kelas=vnk.kelas_id) as belum_disetujui,
(select group_concat(distinct nama_dosen separator '#') from view_dosen_kelas where view_dosen_kelas.id_kelas=vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
  from view_nama_kelas vnk
  left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
  inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id 
  where vnk.kelas_id is not null
 $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas $fakultas",$columns);


  //buat inisialisasi array data
  $data = array();
  $i=1;
  $dos = array();
  foreach ($query as $value) {
    //array data
    $ResultData = array();
     $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';
  
    $ResultData[] = $value->nm_matkul.'('.$value->sks.' SKS) - SMT '.$value->sem_matkul;
    $ResultData[] = $value->nama_kelas;
    
    if ($value->jam!='') {
      $ResultData[] = '<span class="btn btn-xs btn-success edit-jadwal" data-act="edit" data-toggle="tooltip" data-title="Lihat Jadwal" data-id="'.$value->kelas_id.'"><i class="fa fa-calendar"></i> '.ucwords($value->hari).', '.substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai, 0,5).'</span>';

      $ResultData[] = $value->nm_ruang;
    } else {
       $ResultData[] = '<span class="btn btn-xs btn-primary edit-jadwal" data-act="add" data-toggle="tooltip" data-title="Atur Jadwal" data-id="'.$value->kelas_id.'"><i class="fa fa-calendar"></i> Tambah Jadwal</span>';
       $ResultData[] = '';
    }
    //$ResultData[] = strtoupper($value->hari).",<br>".substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai, 0,5);
if ($value->nama_dosen != '') {
    $nama_dosen_list = array_map('trim', explode('#', $value->nama_dosen));
    
    if (count($nama_dosen_list) > 1) {
        $nama_dosen = '';
        foreach ($nama_dosen_list as $index => $nama) {
            $nama_dosen .= ($index + 1) . ". " . $nama . "<br>";
        }
    } else {
        $nama_dosen = reset($nama_dosen_list); // Get the single name without number
    }
    
    $ResultData[] = $nama_dosen;
} else {
    $ResultData[] = '';
}

    $ResultData[] = $value->peserta_max;
    $ResultData[] = '<span class="btn btn-xs btn-success peserta-kelas" data-toggle="tooltip" data-title="Lihat Peserta" data-action="yes" data-id="'.$value->kelas_id.'">'.$value->jml.'</span>';
    $ResultData[] = '<span class="btn btn-xs btn-warning peserta-kelas" data-toggle="tooltip" data-title="Lihat Peserta" data-action="no" data-id="'.$value->kelas_id.'">'.$value->belum_disetujui.'</span>';

    $ResultData[] = $value->jurusan;
    //$ResultData[] = $value->nama_jenis_kelas;
    $ResultData[] = $value->kelas_id;
    $data[] = $ResultData;
     $dos = array();
    $i++;
  }
//set data
//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>