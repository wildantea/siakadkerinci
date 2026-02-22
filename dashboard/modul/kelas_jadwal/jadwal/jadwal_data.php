<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'nm_ruang',
    'id_hari',
    'jam_mulai',
    'jam_selesai',
    'jadwal_id'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nama_kec','kecamatan.id_kec');
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);

  //set order by column
  //$datatable2->set_order_by("dosen");

  //set order by type
  //$datatable2->setOrderBy("id_hari asc");

  //set group by column
  //$datatable2->setGroupBy("kelas_id");
  $kelas_id = $_POST['kelas_id'];
  //$datatable2->setDebug(1);
  $datatable2->setFromQuery("view_jadwal where jadwal_id is not null  and hari is not null and kelas_id=$kelas_id");
  $query = $datatable2->execQuery("select hari,jam_mulai,jam_selesai,kelas_id,jadwal_id,
    (select nama_dosen from view_dosen_kelas where view_dosen_kelas.id_kelas=view_jadwal.kelas_id) as nama_dosen,
(select nm_ruang from ruang_ref where ruang_id=view_jadwal.ruang_id) as nm_ruang,
    jadwal_id from view_jadwal where jadwal_id is not null and hari is not null and kelas_id=$kelas_id",$columns);

  //buat inisialisasi array data
  $data = array();


$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,peserta_max as kuota,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));


// Check current schedule
$is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $kelas_data->sem_id));

$current_data = strtotime(date("Y-m-d H:i:s"));

$contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal . " 00:00:00");
$contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal . " 23:59:59");

if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
    $is_edit = 1;
} else {
    $is_edit = 0;
} 

//also check in semeste prodi
// Check current schedule
if ($is_edit==0) {
    $is_jadwal_edit = $db->fetch_custom_single("select * from semester where id_semester=? and kode_jur=?", array('id_semester' => $kelas_data->sem_id,'kode_jur' => $kelas_data->kode_jur));

    $current_data = strtotime(date("Y-m-d H:i:s"));

    $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal . " 00:00:00");
    $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal . " 23:59:59");

    if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
        $is_edit = 1;
    } else {
        $is_edit = 0;
    }  
}



  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();

    $ResultData[] = $value->nm_ruang;
    $ResultData[] = ucwords($value->hari);
    $ResultData[] = $value->jam_mulai;
    $ResultData[] = $value->jam_selesai;
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

    if ($is_edit==1 or $_SESSION['level']=='1' or $_SESSION['group_level']=='tim_kecil') {
     $ResultData[] = '<button class="btn btn-primary edit-jadwal-modal" data-toggle="tooltip" title="Edit Jadwal" data-id="'.$value->jadwal_id.'"><i class="fa fa-pencil"></i> Edit Jadwal</button> <button class="btn btn-danger hapus_dtb_notif_jadwal" data-kelas="'.$value->kelas_id.'" data-id="'.$value->jadwal_id.'" data-uri="'.base_admin().'modul/kelas_jadwal/jadwal/jadwal_action.php" data-variable="dtb_jadwal_modal" data-toggle="tooltip" title="Hapus Jadwal" ><i class="fa fa-trash"></i> Hapus</button>';
    } else {
      $ResultData[] = '';
    }
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>