<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'lokasi_ppl.nama_lokasi',
    '(select dosen from view_dosen where id_dosen=lokasi_ppl.dpl)',
    '(select dosen from view_dosen where id_dosen=lokasi_ppl.dpl2)',
    'lokasi_ppl.kuota',
    'kuota_l',
    'kuota_p',
    'id_lokasi',
    '(select count(id_kkn) from ppl where ppl.id_lokasi=lokasi_ppl.id_lokasi)',
    'lokasi_ppl.id_lokasi'
  );

  //if you want to exclude column for searching, put columns name in array
/*  $datatable2->setDisableSearchColumn(
    'keu_tagihan_mahasiswa.id_tagihan_prodi',
    'keu_tagihan.nominal_tagihan',
    'potongan',
    'keu_tagihan.nominal_tagihan-potongan',
    'keu_tagihan_mahasiswa.periode',
    'view_prodi_jenjang.jurusan',
    'keu_tagihan_mahasiswa.id'
  );
  */
  //set numbering is true
  $datatable2->setNumberingStatus(1);


  //set order by column
  $datatable2->setOrderBy("lokasi_ppl.id_lokasi desc");
  $wh = "";
  if ($_POST['priode']!='all') {
    $wh = " where id_periode='".$_POST['priode']."' ";
  }


$kode_prodi = aksesProdi('kode_jur');



  //set group by column
  //$new_table->group_by = "group by keu_tagihan_mahasiswa.id";
$datatable2->setDebug(1);
$datatable2->setFromQuery("lokasi_ppl $wh and id_lokasi in(select id_lokasi from kuota_jurusan_ppl where 1=1 $kode_prodi)");
  $query = $datatable2->execQuery("select lokasi_ppl.*,(select dosen from view_dosen where id_dosen=lokasi_ppl.dpl) as dplsatu,
    (select dosen from view_dosen where id_dosen=lokasi_ppl.dpl2) as dpldua,
    (select count(id_kkn) from ppl where ppl.id_lokasi=lokasi_ppl.id_lokasi) as jml from lokasi_ppl
     $wh
and id_lokasi in(select id_lokasi from kuota_jurusan_ppl where 1=1 $kode_prodi)
     ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  $array_allowed_login_as = array(
    'admin'
    //,'keuangan','rektorat','admin_akademik','tipd'
  );
  foreach ($query as $value) {
  $tabel = "<ul>";
                $qj = $db->query("select l.id_lokasi, l.kuota, l.kode_jur,jurusan.nama_jur 
                  from jurusan join kuota_jurusan_ppl l  on l.kode_jur=jurusan.kode_jur where id_lokasi='$value->id_lokasi' ");
                foreach ($qj as $kj) { 
                  $tabel .= "
                          <li style='list-style-type:none'>$kj->nama_jur <label class='label label-primary'>$kj->kuota</label></li>";
                }
    $tabel .="</ul>";   
    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nama_lokasi; 
   // $ResultData[] = $value->nama_jur;
    //$ResultData[] = $value->jk;
    $ResultData[] = $value->dplsatu;
     $ResultData[] = $value->dpldua;
    $ResultData[] = $value->kuota;
    $ResultData[] = $value->kuota_l;
    $ResultData[] = $value->kuota_p;
    $ResultData[] = $tabel;
    $ResultData[] = $value->jml;
    $ResultData[] = $value->id_lokasi;

    $data[] = $ResultData;
    $tabel = "";
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();
?>