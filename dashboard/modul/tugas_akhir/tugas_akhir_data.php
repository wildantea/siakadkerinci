<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mhs.nim',
    'j.kode_jur',
    'f.kode_fak',
    'ta.status_ta',
    'ta.id_ta'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('penguji_2','ta.id_ta');

  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("ta.id_ta");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by ta.id_ta";

  $fakultas = "";
  $jurusan = "";
  $priode = "";

  if (isset($_POST['fakultas'])) {

    if ($_POST['fakultas']!='all') {
      $fakultas = ' and f.kode_fak="'.$_POST['fakultas'].'"';
    }
  }

  if(isset($_POST['jurusan'])) {
    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }
  }

  if(isset($_POST['priode'])) {
    if ($_POST['priode']!='all') {
      $priode = ' and p.id_muna="'.$_POST['priode'].'"';
    }
  }

  if($_SESSION['id_jur'] != NULL){
    $jurusan = ' and j.kode_jur="'.$_SESSION['id_jur'].'"';
  } 

  if($_SESSION['id_fak'] != NULL){
    $fakultas = ' and f.kode_fak="'.$_SESSION['id_fak'].'"';
  }


  $query = $datatable->get_custom("select *,ta.id_ta,ta.nim,ta.pembimbing_1,pem1.nama_dosen as pem_1,pem2.nama_dosen as pem_2,mhs.nama,j.nama_jur from tugas_akhir ta
  inner join mahasiswa mhs on ta.nim=mhs.nim 
  inner join dosen pem1 on ta.pembimbing_1=pem1.id_dosen
  inner join jadwal_muna p on p.id_muna=ta.priode_muna
  inner join dosen pem2 on ta.pembimbing_2=pem2.id_dosen 
  inner join fakultas f on ta.kode_fak=f.kode_fak
  inner join jurusan j on ta.kode_jurusan=j.kode_jur 
  where ta.id_ta is not null $fakultas $jurusan $priode",$columns);

  //buat inisialisasi array data
  $data = array();
  $status="";
  $i=1;
  foreach ($query as $value) {
    if ($value->status_ta == 1) {
    $status="
    <div class='btn-group' id='stat_$value->id_ta'>
          <button type='button' class='btn btn-danger btn-xs'>Tugas Akhir</button>
          <button type='button' class='btn btn-danger dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
          </button>
          <ul class='dropdown-menu' role='menu'>
            <li><a onclick='change_status($value->id_ta,2)'>Yudisium</a></li>
            <li><a onclick='change_status($value->id_ta,3)'>Lulus</a></li>
          </ul>
    </div>
    ";
  } else if($value->status_ta == 2){
      $status="
      <div class='btn-group' id=stat_$value->id_ta'>
        <button type='button' class='btn btn-warning btn-xs'>Yudisium</button>
        <button type='button' class='btn btn-warning dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
          <span class='caret'></span>
          <span class='sr-only'>Toggle Dropdown</span>
        </button>
        <ul class='dropdown-menu' role='menu'>
          <li><a onclick='change_status($value->id_ta,1)'>Tugas Akhir</a></li>
          <li><a onclick='change_status($value->id_ta,3)'>Lulus</a></li>
        </ul>
      </div>
      ";
    } else {
      $status="
      <div class='btn-group' id='stat_$value->id_ta'>
            <button type='button' class='btn btn-success btn-xs'>Lulus</button>
            <button type='button' class='btn btn-success dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
              <span class='caret'></span>
              <span class='sr-only'>Toggle Dropdown</span>
            </button>
            <ul class='dropdown-menu' role='menu'>
              <li><a onclick='change_status($value->id_ta,2)'>Yudisium</a></li>
              <li><a onclick='change_status($value->id_ta,3)'>Tugas Akhir</a></li>
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
    $ResultData[] = $value->pem_1;
    $ResultData[] = $value->pem_2;
    $ResultData[] = $status;
    $ResultData[] = $value->id_ta;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>
