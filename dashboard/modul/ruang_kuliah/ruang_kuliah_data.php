<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'gedung_ref.nm_gedung',
    'kode_ruang',
    'ruang_ref.nm_ruang',
    'fungsi_get_ruang_prodi(ruang_ref.ruang_id)',
    'ruang_ref.ket',
    'ruang_ref.is_aktif',
    'ruang_ref.ruang_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('ket','ruang_ref.ruang_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("ruang_ref.ruang_id");

  //set order by type
  $datatable->set_order_type("desc");

$kode_jur = "";
$gedung_id = "";

  $akses_prodi = get_akses_prodi();
  $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
  if ($akses_jur) {
    $kode_jur = "and ruang_ref_prodi.kode_jur in(".$akses_jur->kode_jur.")";
  } else {
  //jika tidak group tidak punya akses prodi, set in 0
    $kode_jur = "and ruang_ref_prodi.kode_jur in(0)";
  }


if (isset($_POST['kode_jur'])) {
    
      if ($_POST['kode_jur']!='all') {
        $kode_jur = ' and ruang_ref_prodi.kode_jur="'.$_POST['kode_jur'].'"';
      }
  
      if ($_POST['gedung_id']!='all') {
        $gedung_id = ' and ruang_ref.gedung_id="'.$_POST['gedung_id'].'"';
      }
  //set order by type
  $datatable->group_by = "group by ruang_ref.ruang_id";
      $query = $datatable->get_custom("select ruang_ref.is_aktif, fungsi_get_ruang_prodi(ruang_ref.ruang_id) jurusan,
  kode_ruang,gedung_ref.nm_gedung,ruang_ref.nm_ruang,ruang_ref.ket,ruang_ref.ruang_id 
from ruang_ref left join gedung_ref on ruang_ref.gedung_id=gedung_ref.gedung_id 
left join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where ruang_ref.ruang_id is not null $kode_jur $gedung_id",$columns);

} else {
    $datatable->group_by = "group by ruang_ref.ruang_id";
  $query = $datatable->get_custom("select ruang_ref.is_aktif, fungsi_get_ruang_prodi(ruang_ref.ruang_id) jurusan,
  kode_ruang,gedung_ref.nm_gedung,ruang_ref.nm_ruang,ruang_ref.ket,ruang_ref.ruang_id 
from ruang_ref left join gedung_ref on ruang_ref.gedung_id=gedung_ref.gedung_id 
left join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where ruang_ref.ruang_id is not null $gedung_id $kode_jur ",$columns);
}

  //buat inisialisasi array data
  $data = array();

  $i=1;
  
  foreach ($query as $value) {

    //array data
    $jur = explode(",", $value->jurusan);
    $jur_list = "";
    foreach ($jur as $kk => $v) {
      $jur_list.= $v."<br>";
    }
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nm_gedung;
    $ResultData[] = $value->kode_ruang;
    $ResultData[] = $value->nm_ruang;
    $ResultData[] = $jur_list;

    $ResultData[] = $value->ket;
    if ($value->is_aktif=='Y') {
       $ResultData[] = '<span class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Non Aktif</span>';
    }
    $ResultData[] = $value->ruang_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>