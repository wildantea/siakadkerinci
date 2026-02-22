<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mhs_pindah.nim_lama',
    'mhs_pindah.nim_baru',
    'mhs_pindah.nama_mhs',
    'mhs_pindah.kampus_lama',
    'mhs_pindah.kampus_baru',
    'mhs_pindah.jurusan_lama',
    'jurusan.nama_jur',
    'mhs_pindah.tgl_pindah',
    'mhs_pindah.no_sk',
    'mhs_pindah.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('no_sk','mhs_pindah.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mhs_pindah.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mhs_pindah.id";
  $akses_prodi = get_akses_prodi();


$jur_kode = "";

//get default akses prodi 
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_kode = "and mhs_pindah.jurusan_baru in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and mhs_pindah.jurusan_baru in(0)";
}

$mulai_smt = '';
$jenis_pindah = '';

      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and jurusan_baru="'.$_POST['jur_kode'].'"';
      }

      if ($_POST['mulai_smt']!='all') {
          $mulai_smt = ' and angkatan_baru="'.$_POST['mulai_smt'].'"';
      }

      if ($_POST['jenis_pindah']!='') {
        $jenis_pindah = ' and jenis_pindah="'.$_POST['jenis_pindah'].'"';
      }

  $query = $datatable->get_custom("select jenis_pindah,angkatan_lama,angkatan_baru, jurusan_baru kode_jur, mhs_pindah.nim_lama,mhs_pindah.nim_baru,mhs_pindah.nama_mhs,mhs_pindah.kampus_lama,mhs_pindah.kampus_baru,mhs_pindah.jurusan_lama,jurusan.nama_jur,mhs_pindah.tgl_pindah,mhs_pindah.no_sk,mhs_pindah.id from mhs_pindah inner join jurusan on mhs_pindah.jurusan_baru=jurusan.kode_jur where 1=1 $jur_kode $mulai_smt $jenis_pindah",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim_lama;
    $ResultData[] = $value->nim_baru;
    $ResultData[] = $value->nama_mhs;
    $ResultData[] = $value->angkatan_lama;
    $ResultData[] = $value->angkatan_baru;
    $ResultData[] = $value->jenis_pindah;
    $ResultData[] = $value->kampus_lama;
    $ResultData[] = $value->kampus_baru;
    $ResultData[] = $value->jurusan_lama;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->tgl_pindah;
    $ResultData[] = $value->no_sk;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>