<?php
include "../../inc/config.php";

$columns = array(
    'bm.nim_beasiswamhs',
    'm.nama',
    'b.nama_beasiswa',
    'bm.stt_beasiswamhs',
    'bm.id_beasiswamhs',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ipk_beasiswamhs','beasiswa_mhs.id_beasiswamhs');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("bm.id_beasiswamhs");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by beasiswa_mhs.id_beasiswamhs";

  $priode = "";
  $jenis = "";
  $beasiswa = "";

  if (isset($_POST['jenis'])) {

    if ($_POST['jenis']!='all') {
      $jenis = ' and bj.id_beasiswajns="'.$_POST['jenis'].'"';
    }

    if ($_POST['priode']!='all') {
      $priode = ' and s.id_semester="'.$dec->dec($_POST['priode']).'"';
    }

    if ($_POST['beasiswa']!='all') {
      $beasiswa = ' and b.id_beasiswa="'.$_POST['beasiswa'].'"';
    }
  }

  $query = $datatable->get_custom("select bm.nim_beasiswamhs,m.nama,b.nama_beasiswa,bm.stt_beasiswamhs,bm.id_beasiswamhs from beasiswa_mhs bm 
    inner join mahasiswa m on bm.nim_beasiswamhs=m.nim
    inner join fakultas f on bm.kode_fak=f.kode_fak
    inner join jurusan j on bm.kode_jur=j.kode_jur
    inner join beasiswa_jenis bj on bm.id_beasiswajns=bj.id_beasiswajns 
    inner join beasiswa b on bm.id_beasiswa=b.id_beasiswa 
    inner join semester_ref s on b.priode_beasiswa=s.id_semester
    where bm.id_beasiswamhs is not null $jenis $beasiswa $priode",$columns);

  //buat inisialisasi array data
  $data = array();
  $status="";
  $i=1;
  foreach ($query as $value) {
    if ($value->stt_beasiswamhs == 1) {
    $status="
    <div class='btn-group' id='stat_$value->id_beasiswamhs'>
          <button type='button' class='btn btn-success btn-xs'>Sudah Validasi</button>
          <button type='button' class='btn btn-success dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
          </button>
          <ul class='dropdown-menu' role='menu'>
            <li><a onclick='change_status($value->id_beasiswamhs,0)'>Belum Validasi</a></li>
          </ul>
    </div>
    ";
    }else {
        $status="
        <div class='btn-group' id='stat_$value->id_beasiswamhs'>
              <button type='button' class='btn btn-danger btn-xs'>Belum Validasi</button>
              <button type='button' class='btn btn-danger dropdown-toggle btn-xs' data-toggle='dropdown' aria-expanded='false'>
                <span class='caret'></span>
                <span class='sr-only'>Toggle Dropdown</span>
              </button>
              <ul class='dropdown-menu' role='menu'>
                <li><a onclick='change_status($value->id_beasiswamhs,1)'>Sudah Validasi</a></li>
              </ul>
        </div>
        ";
    }

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim_beasiswamhs;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->nama_beasiswa;
    $ResultData[] = $status;
    $ResultData[] = $value->id_beasiswamhs;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>