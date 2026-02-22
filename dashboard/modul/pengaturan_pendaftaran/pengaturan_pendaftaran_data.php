<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_data_pendaftaran_jenis.nama_jenis_pendaftaran',
    'tb_data_pendaftaran_jenis_pengaturan.diperuntukan',
    'tb_data_pendaftaran_jenis_pengaturan.ada_jadwal',
    'tb_data_pendaftaran_jenis_pengaturan.ada_judul',
    'tb_data_pendaftaran_jenis_pengaturan.ada_pembimbing',
    'tb_data_pendaftaran_jenis_pengaturan.ada_penguji',
    'fungsi_get_setting_prodi_pendaftaran(tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting)',
    'status_aktif',
    'tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable2->setDisableSearchColumn("tb_data_pendaftaran_jenis_pengaturan.bukti","tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting");
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting desc");


  //set group by column
  $datatable2->setGroupBy("tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting");

$datatable2->setDebug(1);
$array_prodi_unit = array();
$unit_data = array();
$data_prodi_unit = $db2->query("select kode_jur as kode_unit,nama_jurusan as nama_unit from view_prodi_jenjang union select kode_unit,nama_unit from tb_data_unit");
foreach ($data_prodi_unit as $prodi_unit) {
  $array_prodi_unit[$prodi_unit->kode_unit] = $prodi_unit->nama_unit;
}


$jur_kode = "";
//get default akses prodi 
$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "and tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur in(0)";
}

$periode = "";
$status = "";
$jenis_pendaftaran = "";

if (isset($_POST['jurusan'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'prodi' => $_POST['jurusan'],
        'jenis_pendaftaran' => $_POST['jenis_pendaftaran'],
        'input_search' => $_POST['input_search']
      );
      setFilter('filter_pengaturan_pendaftaran',$array_filter);
    }
    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur="'.$_POST['jurusan'].'"';
    }
    if ($_POST['jenis_pendaftaran']!='all') {
      $jenis_pendaftaran = ' and tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran="'.$_POST['jenis_pendaftaran'].'"';
    }
}


function get_unit($array_unit) {
  global $array_prodi_unit;
  $exp = explode(",", $array_unit);
  foreach ($exp as $unit) {
    $data_unit[] = $array_prodi_unit[$unit]."<br>";
  }
  return implode("", $data_unit);
}
$datatable2->setDebug(1);
$datatable2->setFromQuery("tb_data_pendaftaran_jenis_pengaturan inner join tb_data_pendaftaran_jenis on tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran=tb_data_pendaftaran_jenis.id_jenis_pendaftaran
    inner join tb_data_pendaftaran_jenis_pengaturan_prodi using(id_jenis_pendaftaran_setting)
   where tb_data_pendaftaran_jenis.id_jenis_pendaftaran is not null $jur_kode $jenis_pendaftaran"); 

  $query = $datatable2->execQuery("select tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,diperuntukan,tb_data_pendaftaran_jenis_pengaturan.ada_jadwal,ada_template_surat,tb_data_pendaftaran_jenis_pengaturan.ada_bukti,tb_data_pendaftaran_jenis_pengaturan.ada_judul,tb_data_pendaftaran_jenis_pengaturan.ada_pembimbing,tb_data_pendaftaran_jenis_pengaturan.ada_penguji,status_aktif,tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran_setting,group_concat(tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur) as for_jurusan  from tb_data_pendaftaran_jenis_pengaturan inner join tb_data_pendaftaran_jenis on tb_data_pendaftaran_jenis_pengaturan.id_jenis_pendaftaran=tb_data_pendaftaran_jenis.id_jenis_pendaftaran
    inner join tb_data_pendaftaran_jenis_pengaturan_prodi using(id_jenis_pendaftaran_setting)
   where tb_data_pendaftaran_jenis.id_jenis_pendaftaran is not null $jur_kode $jenis_pendaftaran",$columns);

  //buat inisialisasi array data
  $data = array();
  $jur_list = "";
  $i=1;
  foreach ($query as $value) {
    //array data
    $ResultData = array();
  $ResultData[] = $datatable2->number($i);
  
    $ResultData[] = $value->nama_jenis_pendaftaran;
    if ($value->diperuntukan=='dopeg') {
      $peruntukan = 'Dosen/Pegawai';
    } else {
      $peruntukan = "Mahasiswa";
    }
    $ResultData[] = $peruntukan;
    if ($value->ada_jadwal=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
   /* if ($value->ada_bukti=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }*/
    if ($value->ada_judul=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
    if ($value->ada_pembimbing=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
    if ($value->ada_penguji=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Ya</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
    
    $ResultData[] = get_unit($value->for_jurusan);
    if ($value->status_aktif=='Y') {
      $ResultData[] = '<span class="btn btn-success btn-xs">Aktif</span>';
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">Tidak</span>';
    }
    $ResultData[] = $value->id_jenis_pendaftaran_setting;
    $ResultData[] = $value->diperuntukan;
    $jur_list = "";
    $jur = array();

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>