<?php
include "../../inc/config.php";

$columns = array(
    'keu_tagihan_mahasiswa.nim',
    'mahasiswa.nama',
    'keu_jenis_tagihan.nama_tagihan',
    'keu_tagihan_mahasiswa.jumlah',
    'keu_tagihan_mahasiswa.periode',
    'keu_tagihan_mahasiswa.lunas',
    'keu_tagihan_mahasiswa.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('periode','keu_tagihan_mahasiswa.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_tagihan_mahasiswa.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";

  $query = $datatable->get_custom("select keu_tagihan_mahasiswa.nim,mahasiswa.nama,keu_jenis_tagihan.nama_tagihan,keu_tagihan_mahasiswa.jumlah,keu_tagihan_mahasiswa.periode,keu_tagihan_mahasiswa.lunas,keu_tagihan_mahasiswa.id from keu_tagihan_mahasiswa inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan inner join mahasiswa on keu_tagihan_mahasiswa.nim=mahasiswa.nim",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = $value->jumlah;
    $ResultData[] = $value->periode;
    $ResultData[] = $value->lunas;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>