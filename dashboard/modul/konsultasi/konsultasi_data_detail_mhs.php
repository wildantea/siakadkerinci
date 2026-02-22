<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'kategori_konsultasi',
    'pertanyaan',
    'jawaban',
    'id_semester',
    'id',
  );
  
  $datatable2->setNumberingStatus(0);

 $datatable2->setDebug(1);
  //set group by column
  //$new_table->group_by = "group by bimbingan_dosen_pa.id";
$datatable2->setOrderBy("id_semester desc");
$nim = $_POST['nim'];
$nip = $_POST['nip'];
$kategori = $_POST['jenis'];
$semester = get_sem_aktif();

  $query = $datatable2->execQuery("
select * from bimbingan_dosen_pa where nim='$nim' and nip='".$nip."' and id_semester='$semester'",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    if ($value->kategori_konsultasi=='1') {
      $jenis = 'Awal Semester';
    } elseif ($value->kategori_konsultasi=='2') {
      $jenis = 'Tengah Semester';
    } else {
      $jenis = 'Akhir Semester';
    }
    $ResultData[] = $jenis;
    $ResultData[] = $value->pertanyaan."<br><span style='color:#999'>".tgl_time($value->tanggal_tanya)."</span>";
    if ($value->jawaban=='') {
      $ResultData[] = '<a data-id="'.$value->id.'" data-kat="'.$value->kategori_konsultasi.'" class="label label-warning edit_data" data-toggle="tooltip" data-title="Klik untuk Jawab">Belum Jawab</a>';
    } else {
      $ResultData[] = $value->jawaban."<br><span style='color:#999'>".tgl_time($value->tanggal_jawab)."</span>";
    }
    

    $ResultData[] = getAngkatan($value->id_semester);

    if ($_POST['kat_aktif']==$value->kategori_konsultasi) {
      $ResultData[] = '<a data-id="'.$value->id.'" data-kat="'.$value->kategori_konsultasi.'" data-toggle="tooltip" title="Edit Data" class="btn btn-primary btn-sm edit_data data_selected_id"><i class="fa fa-pencil"></i></a>';
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