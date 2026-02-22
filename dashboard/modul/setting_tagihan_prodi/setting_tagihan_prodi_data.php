<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'view_prodi_jenjang.jurusan',
    'keu_jenis_tagihan.nama_tagihan',
    'keu_tagihan.nominal_tagihan',
    'keu_tagihan.berlaku_angkatan',
    'status_aktif',
    'keu_tagihan.id',
  );

  //if you want to exclude column for searching, put columns name in array
  //$new_table->disable_search = array('berlaku_angkatan','keu_tagihan.id');
  
  //set numbering is true
  $datatable->set_numbering_status(1);

  //set order by column
  $datatable->set_order_by("keu_tagihan.id");

  //set order by type
  $datatable->set_order_type("desc");

  //set group by column
  //$new_table->group_by = "group by keu_tagihan.id";
$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and keu_tagihan.kode_prodi in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and keu_tagihan.kode_prodi in(0)";
}

$berlaku_angkatan = "";
$kode_tagihan = "";
$kode_pembayaran = "";
$fakultas = "";
  if (isset($_POST['kode_prodi'])) {

  if ($_POST['kode_prodi']!='all') {
    $jur_filter = ' and keu_tagihan.kode_prodi="'.$_POST['kode_prodi'].'"';
  }

  if ($_POST['berlaku_angkatan']!='all') {
    $berlaku_angkatan = ' and berlaku_angkatan="'.$_POST['berlaku_angkatan'].'"';
  }
    if ($_POST['kode_pembayaran']!='all') {
    $kode_pembayaran = ' and kjp.kode_pembayaran="'.$_POST['kode_pembayaran'].'"';
  }
    if ($_POST['kode_tagihan']!='all') {
    $kode_tagihan = ' and keu_tagihan.kode_tagihan="'.$_POST['kode_tagihan'].'"';
  }

    if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('keu_tagihan.kode_prodi',$_POST['fakultas']);
  }

}
  $query = $datatable->get_custom("select angkatan,view_prodi_jenjang.jurusan,keu_jenis_tagihan.nama_tagihan,keu_tagihan.nominal_tagihan,keu_tagihan.berlaku_angkatan,status_aktif,
keu_tagihan.id from keu_tagihan inner join view_prodi_jenjang on keu_tagihan.kode_prodi=view_prodi_jenjang.kode_jur
inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan
 inner join keu_jenis_pembayaran kjp on keu_jenis_tagihan.kode_pembayaran=kjp.kode_pembayaran
inner join view_semester on keu_tagihan.berlaku_angkatan=view_semester.id_semester
 where keu_tagihan.id is not null $fakultas $jur_filter $berlaku_angkatan $kode_pembayaran $kode_tagihan ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $angkatan = array();
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->jurusan;
    $ResultData[] = $value->nama_tagihan;
    $ResultData[] = 'Rp. '.rupiah($value->nominal_tagihan);
    $ResultData[] = $value->angkatan;
    if ($value->status_aktif=='1') {
       $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Tagihan Aktif"><i class="fa fa-check"></i> Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Tagihan Tidak Aktif"><i class="fa fa-close"></i> Non Aktif</span>';
    }
    $ResultData[] = $value->id;
    $data[] = $ResultData;
     $angkatan = array();
    $i++;
  }

//set data
$datatable->set_data($data);
//create our json
$datatable->create_data();

?>