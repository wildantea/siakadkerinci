<?php
session_start();
include "../../../inc/config.php";

$columns = array(
    'view_simple_mhs.nim',
    'view_simple_mhs.nama',
    'view_simple_mhs.angkatan',
    'view_krs_mhs_kelas.krs_detail_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_kelas_krs.nim","tb_data_kelas_krs.krs_detail_id");
  $is_absen = 0;
  $check_pertemuan = $db2->checkExist("tb_data_kelas_absensi",array('id_pertemuan' => $_POST['id_pertemuan']));
  if ($check_pertemuan) {
    $is_absen = 1;
  }
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  $datatable2->setOrderBy("view_simple_mhs.nim asc");

  $kelas_id = $_POST['kelas_id'];
  $pertemuan = $_POST['id_pertemuan'];

  //set group by column
  //$datatable2->setGroupBy("tb_data_kelas_krs.krs_detail_id");
$datatable2->setDebug(1);
  $query = $datatable2->execQuery("select krs_detail.nim,view_simple_mhs.nama,view_simple_mhs.angkatan,view_simple_mhs.nama_jurusan,
  (select isi_absensi from tb_data_kelas_absensi where id_pertemuan='".$pertemuan."' ) as absen

   from krs_detail inner join view_simple_mhs on krs_detail.nim=view_simple_mhs.nim where id_kelas='$kelas_id' and disetujui='1'",$columns);

  //buat inisialisasi array data
  $data = array();

  function option($selected) {
    $select_data = "";
    $array_select = array(
      '','Hadir','Ijin','Sakit','Alpa'
    );
    foreach ($array_select as $select ) {
      if ($select == $selected) {
         $select_data.= "<option value='$select' selected>$select</option>";
      } else {
        $select_data.= "<option value='$select'>$select</option>";
      }
    }
    return $select_data;
  }
  function get_nim_absen($obj) {
    global $db;
    foreach ($obj as $key) {
      $nim[$key->nim] = $key;
    }
    return $nim;
  }
  $i=1;
  $counter = 0;
  foreach ($query as $value) {
    $values = array();
    $nim_user = array();
    if ($value->absen!="") {
      $absen = json_decode($value->absen);
      $nim_user = get_nim_absen($absen);
    }
    
    //array data
    $ResultData = array();
    $ResultData[] = $datatable2->number($i);
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    if ($is_absen) {
      if (in_array($value->nim,array_keys($nim_user))) {
        $ResultData[] = tgl_indo($nim_user[$value->nim]->tanggal_absen);
      } else {
        $ResultData[] = '';
      }
    }

    if (in_array($value->nim,array_keys($nim_user))) {
      $ResultData[] = '<select class="form-control absen-val" name="nim['.$value->nim.']" style="padding-right:20px">'.option($nim_user[$value->nim]->status_absen).'</select>';
    } else {
      $ResultData[] = '<select class="form-control absen-val" name="nim['.$value->nim.']" required data-msg-required="Pilih status" style="padding-right:20px">'.option('nothing').'</select>';
    }
    $data[] = $ResultData;
    $i++;
    $counter++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>