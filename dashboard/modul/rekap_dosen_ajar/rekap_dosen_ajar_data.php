<?php
session_start();
include "../../inc/config.php";

$columns = array(
  'nidn',
    'nama_dosen',
    'kls_nama',
    'view_jadwal.sem_id',
    'matkul_dosen',
    'sks',
    'nm_ruang',
    'jurusan',
    'jml_dosen',
    'dosen_ke'
  );
  //if you want to exclude column for searching, put columns name in array
$datatable2->setDisableSearchColumn(
'jml_dosen',
'dosen_ke',
);
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

  //set order by column
  //$datatable2->set_order_by("dosen");

  //set order by type
  //$datatable2->setOrderBy("id_hari asc");

  //set group by column
  //$datatable2->setGroupBy("kelas_id");

$jur_filter = "";
$fakultas = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vn.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vn.kode_jur in(0)";
}


//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and vn.sem_id='".$semester_aktif->id_semester."'";
$matkul_filter = "";
$hari = "";
 $filter_ket = "";

  if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vn.kode_jur="'.$_POST['jur_filter'].'"';
  }
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('vn.kode_jur',$_POST['fakultas']);
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and vn.sem_id="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['matkul_filter']!='all') {
    $matkul_filter = ' and vn.id_matkul="'.$_POST['matkul_filter'].'"';
  }
  if ($_POST['hari']!='all') {
    $hari = ' and view_jadwal.hari="'.$_POST['hari'].'"';
  }
  if ($_POST['keterangan']!='all') {
    if ($_POST['keterangan']=='tunggal') {
      $filter_ket = "and (select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) = 1";
    } elseif ($_POST['keterangan']=='tim') {
      $filter_ket = "and (select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) > 1";
    }
  }
}

  $datatable2->setDebug(1);


  $datatable2->setFromQuery("view_jadwal_dosen_kelas vj
inner join dosen on vj.id_dosen=dosen.nip
inner join view_nama_kelas vn on vj.id_kelas=vn.kelas_id
inner join view_jadwal on vj.jadwal_id=view_jadwal.jadwal_id
     where vn.kelas_id is not null $sem_filter $jur_filter $fakultas $matkul_filter $filter_ket $hari");

  $query = $datatable2->execQuery("select view_jadwal.sem_id,kls_nama,nidn,nama_dosen,vj.matkul_dosen,sks,nm_ruang,vn.jurusan,view_jadwal.hari,vj.dosen_ke,view_jadwal.jam_mulai,view_jadwal.jam_selesai,
(select count(id_Kelas) from dosen_kelas where id_kelas=vn.kelas_id) as jml_dosen
   from view_jadwal_dosen_kelas vj
inner join dosen on vj.id_dosen=dosen.nip
inner join view_nama_kelas vn on vj.id_kelas=vn.kelas_id
inner join view_jadwal on vj.jadwal_id=view_jadwal.jadwal_id
     where vn.kelas_id is not null $sem_filter $jur_filter $fakultas $matkul_filter $filter_ket $hari",$columns);

  //buat inisialisasi array data
  $data = array();

$data_kelas = $db->fetch_single_row("kelas","kelas_id",$_POST['kelas_id']);

//check is current jadwal
$is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?",array('id_semester' => $data_kelas->sem_id));

$current_data = strtotime(date("Y-m-d"));
$contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal);
$contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal);

if($current_data > $contractDateBegin && $current_data < $contractDateEnd) {
  $is_edit = 1;
} else {
  $is_edit = 0;
}  

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->nidn;
    $ResultData[] = $value->nama_dosen;
    $ResultData[] = $value->kls_nama;
     $ResultData[] = $value->sem_id;
    $ResultData[] = $value->matkul_dosen;
    $ResultData[] = $value->sks;
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = $value->jurusan;
    if ($value->jml_dosen > 1) {
      $ResultData[] = 'Dosen Tim';
    } else {
      $ResultData[] = 'Dosen Tunggal';
    }
    $ResultData[] = $value->dosen_ke;




if(strtolower($value->hari)=='senin') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 

if(strtolower($value->hari)=='selasa') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 

if(strtolower($value->hari)=='rabu') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 

if(strtolower($value->hari)=='kamis') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 

if(strtolower($value->hari)=='jumat') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 

if(strtolower($value->hari)=='sabtu') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 
if(strtolower($value->hari)=='minggu') {
  $ResultData[]= substr($value->jam_mulai, 0,5)." - ".substr($value->jam_selesai,0,5); 
} else {
  $ResultData[]= "";
} 
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>
