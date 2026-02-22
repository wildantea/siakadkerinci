<?php
session_start();
include "../../inc/config.php";
$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'view_semester.angkatan',
    'status_mhs.ket',
    'view_prodi_jenjang.jurusan',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('nm_ayah','mahasiswa.mhs_id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("mahasiswa.mhs_id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";
$jur_filter = "";
 $akses_prodi = get_akses_prodi();
 // echo "$akses_prodi";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and kode_jur in(0)";
}

  $query = $datatable->get_custom("select mahasiswa.nim,mahasiswa.nama,view_semester.angkatan,status_mhs.ket,view_prodi_jenjang.jurusan,mahasiswa.mhs_id from mahasiswa inner join view_semester on mahasiswa.mulai_smt=view_semester.id_semester inner join view_prodi_jenjang on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur left join status_mhs on mahasiswa.stat_pd=status_mhs.kode where mahasiswa.nim is not null $jur_filter ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->angkatan;
    $ResultData[] = $value->ket;
    $ResultData[] = $value->jurusan;
    $ResultData[] = '<a href="nilai-permahasiswa/show-nilai/'.en($value->nim).'" data-id="'.$value->nim.'" class="btn edit_data btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat Nilai</a>';

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>