<?php
include "../../inc/config.php";

$columns = array(
    'skala_nilai.nilai_huruf',
    'skala_nilai.nilai_indeks',
    'skala_nilai.bobot_nilai_min',
    'skala_nilai.bobot_nilai_maks',
    'skala_nilai.tgl_mulai_efektif',
    'skala_nilai.tgl_akhir_efektif',
    'berlaku_angkatan',
    'view_prodi_jenjang.jurusan',
    'skala_nilai.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('kode_jurusan','skala_nilai.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("skala_nilai.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by skala_nilai.id";



$fakultas = "";
$jurusan = "";
$jenjang = "";
$angkatan = "";

  if (isset($_POST['fakultas'])) {

  if ($_POST['fakultas']!='all') {
    $fakultas = ' and view_prodi_jenjang.kode_fak="'.$_POST['fakultas'].'"';
  }

    if ($_POST['jurusan']!='all') {
    $jurusan = ' and view_prodi_jenjang.kode_jur="'.$_POST['jurusan'].'"';
  }

      if ($_POST['jenjang']!='all') {
    $jenjang = ' and view_prodi_jenjang.id_jenjang="'.$_POST['jenjang'].'"';
  }

  if ($_POST['angkatan']!='all') {
    $angkatan = ' and berlaku_angkatan="'.$_POST['angkatan'].'"';
  }
}

  $query = $datatable->get_custom("select skala_nilai.nilai_huruf,skala_nilai.nilai_indeks,skala_nilai.bobot_nilai_min,skala_nilai.bobot_nilai_maks,skala_nilai.tgl_mulai_efektif,skala_nilai.tgl_akhir_efektif,view_prodi_jenjang.jurusan,berlaku_angkatan,skala_nilai.id from skala_nilai inner join view_prodi_jenjang on skala_nilai.kode_jurusan=view_prodi_jenjang.kode_jur  where view_prodi_jenjang.kode_jur is not null $fakultas $jurusan $jenjang $angkatan",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nilai_huruf;
    $ResultData[] = $value->nilai_indeks;
    $ResultData[] = $value->bobot_nilai_min;
    $ResultData[] = $value->bobot_nilai_maks;
    $ResultData[] = tgl_indo($value->tgl_mulai_efektif);
    $ResultData[] = tgl_indo($value->tgl_akhir_efektif);
    $ResultData[] = $value->berlaku_angkatan;
    $ResultData[] = $value->jurusan;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>