<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'kw.nim',
    'ta.nim',
    'm.nama',
    'f.nama_resmi',
    'j.nama_jur',
    'ta.id_ta',
    'kw.detail'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nim','tugas_akhir.id_ta');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("kw.id_detail");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by tugas_akhir.id_ta";

  $priode = "";
  $jurusan = "";
  $fakultas = "";

  if (isset($_POST['jurusan'])) {

    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }

    if ($_POST['priode']!='all') {
      $priode = ' and kw.id_wisuda="'.$_POST['priode'].'"';
    }

  }

  if($_SESSION['id_fak'] != NULL){
    $fakultas = ' and f.kode_fak="'.$_SESSION['id_fak'].'"';
  }


  $query = $datatable->get_custom("select *,ta.status_ta, kw.id_wisuda,ta.nim,m.nama,f.nama_resmi,j.nama_jur,ta.id_ta 
from detail_wisuda kw inner join mahasiswa m on m.nim=kw.nim 
inner join tugas_akhir ta on ta.nim=kw.nim 
inner join fakultas f on ta.kode_fak=f.kode_fak 
inner join jurusan j on ta.kode_jurusan=j.kode_jur WHERE 
ta.status_ta=3 $jurusan $priode $fakultas order by id_detail",$columns);

  //buat inisialisasi array data
  $data = array();
  $status="";
  $i=1;
  foreach ($query as $value) {
  if ($value->status_wisuda == '1') {
    $status="
    <div class='btn-group' id='stat_$value->id_wisuda'>
          <button type='button' class='btn btn-success btn-xs'>Sudah Bayar</button>
          <button type='button' class='btn btn-success dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
          </button>
          <ul class='dropdown-menu' role='menu'>
            <li><a onclick='change_status($value->id_wisuda,3)'>Belum Bayar</a></li>
          </ul>
    </div>
    ";
  } else {
      $status="
      <div class='btn-group' id='stat_$value->id_wisuda'>
            <button type='button' class='btn btn-danger btn-xs'>Belum Bayar</button>
            <button type='button' class='btn btn-danger dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
              <span class='caret'></span>
              <span class='sr-only'>Toggle Dropdown</span>
            </button>
            <ul class='dropdown-menu' role='menu'>
              <li><a onclick='change_status($value->id_wisuda,1)'>Sudah Bayar</a></li>
            </ul>
      </div>
      ";
    }
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $status;
    $ResultData[] = $value->id_detail;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>