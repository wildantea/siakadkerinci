<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'k.nim',
    'm.nama',
    'k.sk_yudisium',
    'k.no_seri_ijasah',
    'k.judul_ta',
    'j.nama_jur',
    'k.id_ta'
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('keterangan','kelulusan.id');

  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("k.id_ta");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by kelulusan.id";

  $fakultas = "";
  $jurusan = "";
  $user_fakultas = "";
  $user_jurusan = "";
  $keluar = "";
  $skripsi = "";

  if(isset($_POST['keluar_filter'])){
    if($_POST['keluar_filter'] != 'all'){
      $keluar = ' and k.id_jenis_keluar="'.$_POST['keluar_filter'].'"';
    }
  }

  if(isset($_POST['skripsi_filter'])){
    if($_POST['skripsi_filter']!='all'){
      $skripsi = ' and k.jalur_skripsi="'.$_POST['skripsi_filter'].'"';
    }
  }

  if (isset($_POST['fakultas'])) {

    if ($_POST['fakultas']!='all') {
      $fakultas = ' and f.kode_fak="'.$_POST['fakultas'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan'].'"';
    }

  }

  if($_SESSION['id_fak'] != NULL){
    $user_fakultas = ' and f.kode_fak="'.$_SESSION['id_fak'].'"';
  }

  if($_SESSION['id_jur'] != NULL){
    $user_jurusan = ' and j.kode_jur="'.$_SESSION['id_jur'].'"';
  }

  $query = $datatable->get_custom("select k.*,k.nim,m.nama,k.sk_yudisium,k.no_seri_ijasah,k.judul_ta,j.nama_jur,k.id_ta 
    from tugas_akhir k
    inner join mahasiswa m on k.nim=m.nim
    inner join jurusan j on k.kode_jurusan=j.kode_jur
    inner join fakultas f on j.fak_kode=f.kode_fak 
    where k.status_ta=2 or k.status_ta=3 
    $skripsi $keluar $fakultas $jurusan $user_fakultas $user_jurusan order by k.id_ta",$columns);

  //buat inisialisasi array data
  $data = array();
  $status="";
  $i=1;
  foreach ($query as $value) {

      if($value->status_ta == 2){
        $status="
        <div class='btn-group' id=stat_$value->id_ta'>
          <button type='button' class='btn btn-warning btn-xs'>Yudisium</button>
          <button type='button' class='btn btn-warning dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
          </button>
          <ul class='dropdown-menu' role='menu'>
            <li><a onclick='change_status($value->id_ta,3)'>Lulus</a></li>
          </ul>
        </div>
        ";
      } else if($value->status_ta == 3) {
        $status="
        <div class='btn-group' id='stat_$value->id_ta'>
              <button type='button' class='btn btn-danger btn-xs'>Lulus</button>
              <button type='button' class='btn btn-danger dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
                <span class='caret'></span>
                <span class='sr-only'>Toggle Dropdown</span>
              </button>
              <ul class='dropdown-menu' role='menu'>
                <li><a onclick='change_status($value->id_ta,2)'>Yudisium</a></li>
              </ul>
        </div>
        ";
      }

      if ($value->sk_yudisium != NULL) {
        $sk_yudisium = $value->sk_yudisium;
      } else{
        $sk_yudisium = "No SK belum ada";
      }

      if ($value->no_seri_ijasah != NULL) {
        $no_ijasah = $value->no_seri_ijasah;
      } else{
        $no_ijasah = "No SK belum ada";
      }


    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $sk_yudisium;
    $ResultData[] = $no_ijasah;
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
