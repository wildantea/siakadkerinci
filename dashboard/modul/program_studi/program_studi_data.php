<?php
include "../../inc/config.php";

$columns = array(
    'jurusan.kode_jur',
    'jurusan.nama_jur',
    'jurusan.status',
    'jenjang_pendidikan.jenjang',
    'jurusan.ketua_jurusan',
    'jurusan.kode_jur',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ketua_jurusan','jurusan.kode_jur');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("jurusan.kode_jur");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by jurusan.kode_jur";

$fakultas = "";
$jurusan = "";
$jenjang = "";
  if (isset($_POST['fakultas'])) {

  if ($_POST['fakultas']!='all') {
    $fakultas = ' and fak_kode="'.$_POST['fakultas'].'"';
  }

    if ($_POST['jurusan']!='all') {
    $jurusan = ' and jurusan.kode_jur="'.$_POST['jurusan'].'"';
  }

      if ($_POST['jenjang']!='all') {
    $jenjang = ' and jurusan.id_jenjang="'.$_POST['jenjang'].'"';
  }

}


  $query = $datatable->get_custom("select if(ketua_jurusan is not null,concat(dosen.nama_dosen),'') as nama, jurusan.kode_jur,jurusan.kode_dikti,jurusan.id_jenjang,jurusan.nama_jur,if(jurusan.status='A','Aktif','Tidak Aktif') as status,jenjang_pendidikan.jenjang,jurusan.ketua_jurusan from jurusan 
inner join jenjang_pendidikan on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
left join dosen on jurusan.ketua_jurusan=dosen.id_dosen where jurusan.kode_jur is not null $fakultas $jurusan $jenjang ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->kode_jur;
    $ResultData[] = $value->nama_jur;
    $ResultData[] = $value->status;
    $ResultData[] = $value->jenjang;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->kode_jur;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>