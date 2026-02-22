<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'tb_data_kelas_pertemuan.pertemuan',
    'tb_data_kelas_pertemuan.tanggal_pertemuan',
    'tb_data_kelas_pertemuan.id_jenis_pertemuan',
    'tb_data_kelas_pertemuan.jam_mulai',
    'tb_data_kelas_pertemuan.nip_dosen',
    'tb_data_kelas_pertemuan.id_pertemuan',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_pertemuan.updated_by","tb_data_kelas_pertemuan.id_pertemuan");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("tb_data_kelas_pertemuan.pertemuan asc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_pertemuan.id_pertemuan");

$datatable2->setDebug(1);
  //get dosen kelas
  $array_dosen = array();
  $kelas_id = $_POST['kelas_id'];

  function get_nama_dosen($nip) {
    global $db2;
    $dosen_kelas = $db2->query("select id_dosen as nip,nama_dosen as nama_gelar from view_dosen_kelas_single where id_kelas='".$_POST['kelas_id']."'");
    foreach ($dosen_kelas as $dosen ) {
      $array_dosen[$dosen->nip] = $dosen->nama_gelar;
    }
    return $array_dosen[$nip];
  }


$query = $datatable2->execQuery("select tb_data_kelas_pertemuan.pertemuan,nm_ruang,status_pertemuan,metode_pembelajaran,tb_data_kelas_pertemuan.tanggal_pertemuan,
tb_data_jenis_pertemuan.nama_jenis_pertemuan,tb_data_kelas_pertemuan.jam_mulai,jam_selesai,tb_data_kelas_pertemuan.nip_dosen,tb_data_kelas_pertemuan.id_pertemuan from tb_data_kelas_pertemuan inner join tb_data_jenis_pertemuan on tb_data_kelas_pertemuan.id_jenis_pertemuan=tb_data_jenis_pertemuan.id_jenis_pertemuan
  left join ruang_ref using(ruang_id) where kelas_id='".$kelas_id."'",$columns);

  //buat inisialisasi array data
  $data = array();


  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" style="margin-bottom: 15px;"> <input type="checkbox" class="check-selected-pertemuan data_selected_id" data-id="'.$value->id_pertemuan.'"> <span></span></label>';
    
    $ResultData[] = $value->pertemuan;
    $ResultData[] = getHariFromDate($value->tanggal_pertemuan).', '.tgl_indo($value->tanggal_pertemuan);
    $ResultData[] = $value->jam_mulai.' s/d '.$value->jam_selesai;
    if ($value->metode_pembelajaran=='O') {
      $ResultData[] = 'Online';
    } else {
      $ResultData[] = $value->nm_ruang;
    }
    $ResultData[] = $value->nama_jenis_pertemuan;
    $nama_dosen = array_map('get_nama_dosen', explode('#', $value->nip_dosen));
    $nama_dosen = trim(implode("<br>", $nama_dosen));
    $ResultData[] = $nama_dosen;

    if ($value->status_pertemuan=='A') {
      $ResultData[] = '<span class="btn btn-primary"><i class="fa fa-check"></i> Aktif</span>';
    } else {
      $ResultData[] = '<span class="btn btn-success"><i class="fa fa- fa-star"></i> Selesai</span>';
    }
    $ResultData[] = "<a data-id='$value->id_pertemuan'  class='btn btn-primary edit_data' data-toggle='tooltip' title='Edit Pertemuan'><i class='fa fa-pencil'></i></a>";
    //<a data-id='$value->id_pertemuan'  class='btn btn-success btn-xs input-absen' data-toggle='tooltip' title='Input Rencana'><i class='fa fa-user-plus'></i></a> <a data-id='$value->id_pertemuan'  class='btn btn-success btn-xs input-absen' data-toggle='tooltip' title='Isi Presensi'><i class='fa fa-user-plus'></i></a>";
    

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>